<?php

namespace App\Http\Controllers;

use App\Models\RegistroDieta;
use App\Models\Paciente;
use App\Models\Dieta;
use App\Models\Servicio;
use App\Models\Cama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistroDietaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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

    public function index()
    {
        $registros = RegistroDieta::with(['paciente', 'dietas', 'servicio', 'cama', 'createdBy', 'updatedBy'])
            ->orderByDesc('created_at')
            ->paginate(25);

        return view('registro_dietas.index', compact('registros'));
    }

    public function create()
    {
        $pacientes = Paciente::orderBy('nombre')->get();
        $dietas = Dieta::orderBy('nombre')->get();
        return view('registro_dietas.create', compact('pacientes', 'dietas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'dieta_id' => 'required|array|min:1',
            'dieta_id.*' => 'required|exists:dietas,id',
            'tipo_comida' => 'required|in:desayuno,almuerzo,merienda',
            'fecha' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);
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
            'fecha' => $data['fecha'],
            'observaciones' => $data['observaciones'] ?? null,
            'created_by' => \Auth::id(),
            'updated_by' => \Auth::id(),
        ]);
        $registro->dietas()->sync($data['dieta_id']);
        return redirect()->route('registro-dietas.index')->with('success', 'Registro de dieta creado.');
    }

    public function show(RegistroDieta $registro_dieta)
    {
        return view('registro_dietas.show', ['registro' => $registro_dieta->load(['paciente','dietas','servicio','cama','createdBy','updatedBy'])]);
    }

    public function edit(RegistroDieta $registro_dieta)
    {
        $pacientes = Paciente::orderBy('nombre')->get();
        $dietas = Dieta::orderBy('nombre')->get();
        return view('registro_dietas.edit', compact('registro_dieta', 'pacientes', 'dietas'));
    }

    public function update(Request $request, RegistroDieta $registro_dieta)
    {
        $data = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'dieta_id' => 'required|exists:dietas,id',
            'tipo_comida' => 'required|in:desayuno,almuerzo,merienda',
            'fecha' => 'required|date',
            'observaciones' => 'nullable|string',
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
            'fecha' => $data['fecha'],
            'observaciones' => $data['observaciones'] ?? null,
            'updated_by' => \Auth::id(),
        ]);
        $registro_dieta->dietas()->sync($data['dieta_id']);
        return redirect()->route('registro-dietas.index')->with('success', 'Registro de dieta actualizado.');
    }

    public function destroy(RegistroDieta $registro_dieta)
    {
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
