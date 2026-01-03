<?php

namespace App\Http\Controllers;

use App\Models\RegistroDieta;
use App\Models\Paciente;
use App\Models\Dieta;
use App\Models\Servicio;
use App\Models\Cama;
use App\Models\RegistrationSchedule;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistroDietaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Solo admin puede eliminar
        $this->middleware('admin')->only(['destroy']);
    }

    public function reporte(Request $request)
    {
        $fecha = $request->input('fecha');
        $servicio_id = $request->input('servicio_id');
        $tipo_comida = $request->input('tipo_comida');

        $query = RegistroDieta::with(['paciente.servicio', 'paciente.cama', 'dietas']);
        if ($fecha) {
            $query->where('fecha', $fecha);
        }
        if ($servicio_id) {
            $query->whereHas('paciente', function ($q) use ($servicio_id) {
                $q->where('servicio_id', $servicio_id);
            });
        }
        if ($tipo_comida) {
            $query->where('tipo_comida', $tipo_comida);
        }
        $registros = $query->orderByDesc('fecha')->paginate(25);

        // Totales por tipo de comida
        $totales = RegistroDieta::select('tipo_comida')
            ->selectRaw('count(*) as total')
            ->groupBy('tipo_comida')
            ->get();

        $servicios = Servicio::orderBy('nombre')->get();

        return view('registro_dietas.reporte', compact('registros', 'totales', 'servicios'));
    }

    public function index(Request $request)
    {
        $query = RegistroDieta::with(['paciente', 'dietas', 'servicio', 'cama', 'createdBy', 'updatedBy'])
            ->orderByDesc('created_at');

        // Filtros
        if ($search = $request->input('search')) {
            $query->whereHas('paciente', function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('cedula', 'like', "%{$search}%");
            });
        }
        if ($tipo = $request->input('tipo_comida')) {
            $query->where('tipo_comida', $tipo);
        }
        if ($fecha = $request->input('fecha')) {
            $query->whereDate('fecha', $fecha);
        }

        $registros = $query->paginate(25)->appends($request->query());
        return view('registro_dietas.index', compact('registros'));
    }

    public function dashboard(Request $request)
    {
        $fecha = $request->input('fecha', now()->toDateString());
        $tipo = $request->input('tipo_comida', 'desayuno');
        $servicioId = $request->input('servicio_id');

        $query = RegistroDieta::with(['paciente.servicio','paciente.cama','dietas.tipo','dietas.subtipos','createdBy','updatedBy'])
            ->whereDate('fecha', $fecha)
            ->where('tipo_comida', $tipo);

        if ($servicioId) {
            $query->whereHas('paciente', function ($q) use ($servicioId) {
                $q->where('servicio_id', $servicioId);
            });
            // Ordenar por cama cuando se filtra por servicio
            $registros = $query->get()->sortBy(function($r) {
                return optional(optional($r->paciente)->cama)->codigo;
            })->values();
        } else {
            $registros = $query->orderBy('paciente_id')->get();
        }

        // Excluir NPO (Nada por vía oral) de los totales de dietas
        $todasDietas = $registros->pluck('dietas')->flatten();
        $dietasFiltradas = $todasDietas->filter(function ($d) {
            $n = strtolower($d->nombre ?? '');
            return !(
                str_contains($n, 'npo') ||
                str_contains($n, 'n.p.o') ||
                str_contains($n, 'nada por via oral') ||
                str_contains($n, 'nada por vía oral')
            );
        });
        $dietasNpo = $todasDietas->filter(function ($d) {
            $n = strtolower($d->nombre ?? '');
            return (
                str_contains($n, 'npo') ||
                str_contains($n, 'n.p.o') ||
                str_contains($n, 'nada por via oral') ||
                str_contains($n, 'nada por vía oral')
            );
        });

        $totales = [
            'registros' => $registros->count(),
            'pacientes_unicos' => $registros->pluck('paciente_id')->unique()->count(),
            'dietas_total' => $dietasFiltradas->count(),
            'dietas_por_nombre' => $dietasFiltradas->groupBy('nombre')->map->count()->sortDesc(),
            'dietas_npo' => $dietasNpo->count(),
            // Conteo de pacientes por servicio (usar countBy para colecciones de strings)
            'servicios' => $registros
                ->map(fn($r) => optional($r->paciente->servicio)->nombre)
                ->filter()
                ->countBy()
                ->sortDesc(),
            // Conteo por tipo de vajilla
            'vajilla_descartable' => $registros->where('vajilla', 'descartable')->count(),
            'vajilla_normal' => $registros->where('vajilla', 'normal')->count(),
            // Conteo por tipo y subtipo de dieta
            'dietas_por_tipo' => $dietasFiltradas->groupBy(fn($d) => optional($d->tipo)->nombre ?? 'Sin tipo')->map->count()->sortDesc(),
            'dietas_por_subtipo' => $dietasFiltradas->flatMap(fn($d) => $d->subtipos)->groupBy(fn($s) => optional($s)->nombre ?? 'Sin subtipo')->map->count()->sortDesc(),
        ];

        $servicios = Servicio::orderBy('nombre')->get();

        return view('registro_dietas.dashboard', compact('registros','totales','fecha','tipo','servicios'));
    }

    public function dialisis(Request $request)
    {
        $fecha = $request->input('fecha', now()->toDateString());

        // Obtener todos los registros del día donde el paciente esté en servicio de Diálisis
        $registros = RegistroDieta::with(['paciente.servicio','paciente.cama','dietas.tipo','dietas.subtipos','createdBy','updatedBy'])
            ->whereDate('fecha', $fecha)
            ->whereHas('paciente.servicio', function($q) {
                $q->where('nombre', 'like', '%diálisis%')
                  ->orWhere('nombre', 'like', '%dialisis%');
            })
            ->orderBy('tipo_comida')
            ->orderBy('paciente_id')
            ->get();

        // Agrupar por tipo de comida
        $porTipoComida = $registros->groupBy('tipo_comida');

        // Calcular totales
        $totales = [
            'registros' => $registros->count(),
            'pacientes_unicos' => $registros->pluck('paciente_id')->unique()->count(),
            'desayuno' => $registros->where('tipo_comida', 'desayuno')->count(),
            'almuerzo' => $registros->where('tipo_comida', 'almuerzo')->count(),
            'merienda' => $registros->where('tipo_comida', 'merienda')->count(),
        ];

        return view('registro_dietas.dialisis', compact('registros','porTipoComida','totales','fecha'));
    }

    public function create()
    {
        $query = Paciente::query();
        if (\Schema::hasColumn('pacientes', 'estado')) {
            $query->where('estado', 'hospitalizado');
        }
        $pacientes = $query->orderBy('nombre')->get();
        $tipos = \App\Models\TipoDieta::with(['subtipos.dietas'])->orderBy('nombre')->get();
        return view('registro_dietas.create', compact('pacientes', 'tipos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'dieta_id' => 'required|array|min:1',
            'dieta_id.*' => 'required|exists:dietas,id',
            'tipo_comida' => 'required|in:desayuno,almuerzo,merienda',
            'vajilla' => 'required|in:descartable,normal',
            'fecha' => 'required|date',
            'observaciones' => 'nullable|string',
            'es_tardia' => 'nullable|boolean',
        ]);

        // Validar horario de registro
        $schedule = RegistrationSchedule::getByMealType($data['tipo_comida']);
        if ($schedule && !$schedule->allow_out_of_schedule && !$schedule->isCurrentTimeAllowed()) {
            $startTime = $schedule->start_time->format('H:i');
            $endTime = $schedule->end_time->format('H:i');
            return back()->withErrors([
                'tipo_comida' => "Registro fuera de horario permitido. {$data['tipo_comida']}: {$startTime} - {$endTime}"
            ])->withInput();
        }

        // Validar que el paciente esté hospitalizado (si la columna existe)
        $paciente = Paciente::find($data['paciente_id']);
        if (!$paciente || (\Schema::hasColumn('pacientes', 'estado') && $paciente->estado !== 'hospitalizado')) {
            return back()->withErrors(['paciente_id' => 'El paciente debe estar hospitalizado para registrar una dieta.'])->withInput();
        }

        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        // Validar que no exista ya un registro para ese paciente, fecha y tipo_comida
        $existe = \App\Models\RegistroDieta::where('paciente_id', $data['paciente_id'])
            ->where('fecha', $data['fecha'])
            ->where('tipo_comida', $data['tipo_comida'])
            ->exists();
        if ($existe) {
            return back()->withErrors(['paciente_id' => 'Ya existe un registro de dieta para este paciente, fecha y tipo de comida.'])->withInput();
        }
        $registro = \App\Models\RegistroDieta::create([
            'paciente_id' => $data['paciente_id'],
            'tipo_comida' => $data['tipo_comida'],
            'vajilla' => $data['vajilla'],
            'fecha' => $data['fecha'],
            'observaciones' => $data['observaciones'] ?? null,
            'es_tardia' => $request->has('es_tardia') ? true : false,
            'created_by' => \Auth::id(),
            'updated_by' => \Auth::id(),
        ]);
        $registro->dietas()->sync($data['dieta_id']);
        
        // Registrar auditoría
        AuditService::log('create', "Registro de dieta creado para {$paciente->nombre} {$paciente->apellido} - {$data['tipo_comida']}", 'RegistroDieta', $registro->id);
        
        return redirect()->route('registro-dietas.index')->with('success', 'Registro de dieta creado.');
    }

    public function show(RegistroDieta $registro_dieta)
    {
        return view('registro_dietas.show', ['registro' => $registro_dieta->load(['paciente','dietas','servicio','cama','createdBy','updatedBy'])]);
    }

    public function edit(RegistroDieta $registro_dieta)
    {
        $pacientes = Paciente::orderBy('nombre')->get();
        $tipos = \App\Models\TipoDieta::with(['subtipos.dietas'])->orderBy('nombre')->get();
        return view('registro_dietas.edit', compact('registro_dieta', 'pacientes', 'tipos'));
    }

    public function update(Request $request, RegistroDieta $registro_dieta)
    {
        $data = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'dieta_id' => 'required|array|min:1',
            'dieta_id.*' => 'required|exists:dietas,id',
            'tipo_comida' => 'required|in:desayuno,almuerzo,merienda',
            'vajilla' => 'required|in:descartable,normal',
            'fecha' => 'required|date',
            'observaciones' => 'nullable|string',
            'es_tardia' => 'nullable|boolean',
        ]);
        $data['updated_by'] = Auth::id();
        // Validar que no exista ya un registro para ese paciente, fecha y tipo_comida (excepto el actual)
        $existe = \App\Models\RegistroDieta::where('paciente_id', $data['paciente_id'])
            ->where('fecha', $data['fecha'])
            ->where('tipo_comida', $data['tipo_comida'])
            ->where('id', '!=', $registro_dieta->id)
            ->exists();
        if ($existe) {
            return back()->withErrors(['paciente_id' => 'Ya existe un registro de dieta para este paciente, fecha y tipo de comida.'])->withInput();
        }
        $registro_dieta->update([
            'paciente_id' => $data['paciente_id'],
            'tipo_comida' => $data['tipo_comida'],
            'vajilla' => $data['vajilla'],
            'fecha' => $data['fecha'],
            'observaciones' => $data['observaciones'] ?? null,
            'es_tardia' => $request->has('es_tardia') ? true : false,
            'updated_by' => \Auth::id(),
        ]);
        $registro_dieta->dietas()->sync($data['dieta_id']);
        
        // Registrar auditoría
        $paciente = Paciente::find($data['paciente_id']);
        AuditService::log('update', "Registro de dieta actualizado para {$paciente->nombre} {$paciente->apellido}", 'RegistroDieta', $registro_dieta->id);
        
        return redirect()->route('registro-dietas.index')->with('success', 'Registro de dieta actualizado.');
    }

    public function destroy(RegistroDieta $registro_dieta)
    {
        // Solo administradores pueden eliminar registros
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('registro-dietas.index')->with('error', 'No tienes permiso para eliminar registros.');
        }

        // Registrar auditoría antes de eliminar
        $paciente = $registro_dieta->paciente;
        AuditService::log('delete', "Registro de dieta eliminado para {$paciente->nombre} {$paciente->apellido}", 'RegistroDieta', $registro_dieta->id);

        $registro_dieta->delete();
        return redirect()->route('registro-dietas.index')->with('success', 'Registro de dieta eliminado.');
    }

    // Autocomplete endpoints
    public function searchPacientes(Request $request)
    {
        $q = $request->query('q');
        if (!$q) return response()->json([]);

        $results = Paciente::where('nombre', 'like', "%{$q}%")
            ->orWhere('apellido', 'like', "%{$q}%")
            ->orWhere('cedula', 'like', "%{$q}%")
            ->limit(10)
            ->get()
            ->map(function($p) {
                return ['id' => $p->id, 'label' => $p->nombre . ' ' . $p->apellido . ' (' . $p->cedula . ')'];
            });

        return response()->json($results);
    }

    public function searchDietas(Request $request)
    {
        $q = $request->query('q');
        if (!$q) return response()->json([]);

        $results = Dieta::where('nombre', 'like', "%{$q}%")
            ->limit(10)
            ->get()
            ->map(function($d) {
                return ['id' => $d->id, 'label' => $d->nombre];
            });

        return response()->json($results);
    }

    // Live search registros by paciente name or cedula
    public function search(Request $request)
    {
        $q = $request->query('q');
        $query = RegistroDieta::with(['paciente', 'dieta', 'servicio', 'cama', 'createdBy', 'updatedBy'])->orderByDesc('created_at');

        if ($q) {
            $query->whereHas('paciente', function ($qP) use ($q) {
                $qP->where('nombre', 'like', "%{$q}%")
                   ->orWhere('apellido', 'like', "%{$q}%")
                   ->orWhere('cedula', 'like', "%{$q}%");
            });
        }

        $results = $query->limit(200)->get()->map(function ($r) {
            return [
                'id' => $r->id,
                'paciente' => optional($r->paciente)->nombre . ' ' . optional($r->paciente)->apellido . ' (' . optional($r->paciente)->cedula . ')',
                'dieta' => optional($r->dieta)->nombre,
                'servicio' => optional($r->servicio)->nombre,
                'cama' => optional($r->cama)->codigo,
                'created_by' => optional($r->createdBy)->name,
                'updated_by' => optional($r->updatedBy)->name,
                'created_at' => $r->created_at->format('Y-m-d H:i'),
                'show_url' => route('registro-dietas.show', $r),
                'edit_url' => route('registro-dietas.edit', $r),
                'delete_url' => route('registro-dietas.destroy', $r),
            ];
        });

        return response()->json($results);
    }
}
