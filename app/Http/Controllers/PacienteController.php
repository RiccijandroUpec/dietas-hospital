<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Servicio;
use App\Models\Cama;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PacienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $pacientes = Paciente::with(['servicio', 'cama', 'createdBy', 'updatedBy'])->paginate(15);
        return view('pacientes.index', compact('pacientes'));
    }

    public function reporte(Request $request)
    {
        $estado = $request->input('estado');
        $servicio_id = $request->input('servicio_id');
        $search = trim((string) $request->input('q', ''));

        $query = Paciente::with(['servicio', 'cama', 'createdBy', 'updatedBy']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('cedula', 'like', "%{$search}%");
            });
        }

        if ($estado && \Schema::hasColumn('pacientes', 'estado')) {
            $query->where('estado', $estado);
        }
        if ($servicio_id) {
            $query->where('servicio_id', $servicio_id);
        }

        $pacientes = $query->orderBy('nombre')->paginate(25)->withQueryString();
        $servicios = Servicio::orderBy('nombre')->get();

        // Contar pacientes por estado (con verificación de columna)
        $totales = [];
        if (\Schema::hasColumn('pacientes', 'estado')) {
            $totales = [
                'hospitalizado' => Paciente::where('estado', 'hospitalizado')->count(),
                'alta' => Paciente::where('estado', 'alta')->count(),
            ];
        } else {
            $totales = ['hospitalizado' => 0, 'alta' => 0];
        }

        return view('pacientes.reporte', compact('pacientes', 'servicios', 'totales'));
    }

    // Devuelve camas disponibles para un servicio (no asignadas a pacientes hospitalizados)
    public function availableCamas($servicioId)
    {
        $camas = Cama::where('servicio_id', $servicioId)
            ->whereDoesntHave('pacientes', function($query) {
                $query->where('estado', 'hospitalizado');
            })
            ->get(['id', 'codigo']);

        return response()->json($camas);
    }

    public function create()
    {
        if (Auth::user() && Auth::user()->role === 'usuario') {
            return redirect()->route('pacientes.index')->with('error', 'No tienes permiso para crear pacientes.');
        }
        $servicios = Servicio::all();
        $camas = Cama::all();
        return view('pacientes.create', compact('servicios', 'camas'));
    }

    // Comprueba si existe un paciente por cédula (AJAX)
    public function checkCedula(Request $request)
    {
        $cedula = $request->query('cedula');
        if (!$cedula) {
            return response()->json(['exists' => false]);
        }

        $paciente = Paciente::where('cedula', $cedula)->first();
        if ($paciente) {
            return response()->json([
                'exists' => true,
                'id' => $paciente->id,
                'edit_url' => route('pacientes.edit', $paciente),
            ]);
        }

        return response()->json(['exists' => false]);
    }

    // Búsqueda AJAX por nombre o cédula
    public function search(Request $request)
    {
        $q = trim((string) $request->query('q'));
        $limit = (int) ($request->query('limit', 50));
        $limit = $limit > 0 && $limit <= 50 ? $limit : 50;

        $estado = $request->query('estado');
        $servicioId = $request->query('servicio_id');

        $query = Paciente::query()->with(['servicio', 'cama'])->select('*');
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('nombre', 'like', "%$q%")
                    ->orWhere('apellido', 'like', "%$q%")
                    ->orWhere('cedula', 'like', "%$q%");
            });
        }

        if ($estado) {
            $query->where('estado', $estado);
        }

        if ($servicioId) {
            $query->where('servicio_id', $servicioId);
        }

        // Por defecto prioriza hospitalizados (si la columna existe)
        if (\Schema::hasColumn('pacientes', 'estado')) {
            $query->orderByRaw("estado='hospitalizado' DESC")->orderBy('nombre');
        } else {
            $query->orderBy('nombre');
        }

        $results = $query->limit($limit)->get();
        
        return response()->json([
            'pacientes' => $results->map(function ($p) {
                return [
                    'id' => $p->id,
                    'nombre' => $p->nombre,
                    'apellido' => $p->apellido,
                    'cedula' => $p->cedula,
                    'estado' => $p->estado,
                    'edad' => $p->edad,
                    'condicion' => $p->condicion,
                    'servicio' => $p->servicio ? $p->servicio->nombre : null,
                    'cama' => $p->cama ? $p->cama->codigo : null,
                    'edit_url' => auth()->user()->role !== 'usuario' ? route('pacientes.edit', $p) : null,
                    'delete_url' => auth()->user()->role === 'admin' ? route('pacientes.destroy', $p) : null,
                ];
            })
        ]);
    }

    public function show(Paciente $paciente)
    {
        $paciente->load(['servicio', 'cama', 'createdBy', 'updatedBy']);
        return view('pacientes.show', compact('paciente'));
    }

    public function store(Request $request)
    {
        if (Auth::user() && Auth::user()->role === 'usuario') {
            return redirect()->route('pacientes.index')->with('error', 'El paciente ya está creado, no puedes volver a crearlo.');
        }
        // Verificar si ya existe un paciente con la misma cédula y devolver mensaje claro
        $cedulaIn = $request->input('cedula');
        if ($cedulaIn && Paciente::where('cedula', $cedulaIn)->exists()) {
            return back()->with('error', 'El paciente ya está creado, no puedes volver a crearlo.')->withInput();
        }

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'cedula' => 'required|string|max:100|unique:pacientes,cedula',
            'estado' => 'required|in:hospitalizado,alta',
            'edad' => 'nullable|integer|min:0',
            'condicion' => 'nullable|array',
            'condicion.*' => 'in:normal,diabetico,hiposodico',
            'servicio_id' => $request->estado === 'hospitalizado' ? 'required|exists:servicios,id' : 'nullable|exists:servicios,id',
            'cama_id' => 'nullable|exists:camas,id',
        ]);

        // Si está de alta, limpiar servicio y cama
        if ($data['estado'] === 'alta') {
            $data['servicio_id'] = null;
            $data['cama_id'] = null;
        }

        // Si el servicio es Diálisis, no guardar cama
        if (!empty($data['servicio_id'])) {
            $servicio = \App\Models\Servicio::find($data['servicio_id']);
            if ($servicio && strtolower($servicio->nombre) === 'diálisis') {
                unset($data['cama_id']);
            } else if (!empty($data['cama_id'])) {
                $exists = Paciente::where('cama_id', $data['cama_id'])->exists();
                if ($exists) {
                    return back()->withErrors(['cama_id' => 'La cama está ocupada.'])->withInput();
                }
            }
        }

        // Añadir información de auditoría
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        // Normalizar condicion: guardar como cadena separada por comas si viene como array
        if ($request->has('condicion')) {
            $cond = $request->input('condicion');
            if (is_array($cond)) {
                $data['condicion'] = implode(',', $cond);
            } else {
                $data['condicion'] = $cond;
            }
        }

        Paciente::create($data);
        
        // Registrar auditoría
        AuditService::log('create', "Paciente creado: {$data['nombre']} {$data['apellido']}", 'Paciente', null);
        
        return redirect()->route('pacientes.index')->with('success', 'Paciente creado.');
    }

    public function edit(Paciente $paciente)
    {
        if (Auth::user() && Auth::user()->role === 'usuario') {
            return redirect()->route('pacientes.index')->with('error', 'No tienes permiso para editar pacientes.');
        }
        $servicios = Servicio::all();
        $camas = Cama::all();
        return view('pacientes.edit', compact('paciente', 'servicios', 'camas'));
    }

    public function update(Request $request, Paciente $paciente)
    {
        if (Auth::user() && Auth::user()->role === 'usuario') {
            return redirect()->route('pacientes.index')->with('error', 'No tienes permiso para actualizar pacientes.');
        }
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'cedula' => 'required|string|max:100|unique:pacientes,cedula,' . $paciente->id,
            'estado' => 'required|in:hospitalizado,alta',
            'edad' => 'nullable|integer|min:0',
            'condicion' => 'nullable|array',
            'condicion.*' => 'in:normal,diabetico,hiposodico',
            'servicio_id' => $request->estado === 'hospitalizado' ? 'required|exists:servicios,id' : 'nullable|exists:servicios,id',
            'cama_id' => 'nullable|exists:camas,id',
        ]);

        // Si está de alta, limpiar servicio y cama
        if ($data['estado'] === 'alta') {
            $data['servicio_id'] = null;
            $data['cama_id'] = null;
        } else {
            // Si está hospitalizado, validar que no se asigne una cama ocupada por otro paciente
            if (!empty($data['cama_id'])) {
                $camaOcupada = Paciente::where('cama_id', $data['cama_id'])
                    ->where('id', '!=', $paciente->id)
                    ->where('estado', 'hospitalizado')
                    ->exists();
                
                if ($camaOcupada) {
                    return back()->withErrors(['cama_id' => 'La cama está ocupada por otro paciente.'])->withInput();
                }
            }
        }

        // Añadir auditoría de actualización
        $data['updated_by'] = auth()->id();

        // Normalizar condicion para guardado
        if ($request->has('condicion')) {
            $cond = $request->input('condicion');
            if (is_array($cond)) {
                $data['condicion'] = implode(',', $cond);
            } else {
                $data['condicion'] = $cond;
            }
        }

        $paciente->update($data);
        
        // Registrar auditoría
        AuditService::log('update', "Paciente actualizado: {$paciente->nombre} {$paciente->apellido}", 'Paciente', $paciente->id);
        
        return redirect()->route('pacientes.index')->with('success', 'Paciente actualizado.');
    }

    public function destroy(Paciente $paciente)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('pacientes.index')->with('error', 'No tienes permiso para eliminar pacientes.');
        }
        
        // Registrar auditoría antes de eliminar
        $nombrePaciente = "{$paciente->nombre} {$paciente->apellido}";
        AuditService::log('delete', "Paciente eliminado: {$nombrePaciente}", 'Paciente', $paciente->id);
        
        $paciente->delete();
        return redirect()->route('pacientes.index')->with('success', 'Paciente eliminado.');
    }
}
