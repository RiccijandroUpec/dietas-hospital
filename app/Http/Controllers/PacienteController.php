<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Servicio;
use App\Models\Cama;
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

    // Devuelve camas disponibles para un servicio (no asignadas a pacientes)
    public function availableCamas($servicioId)
    {
        $camas = Cama::where('servicio_id', $servicioId)
            ->whereDoesntHave('pacientes')
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
            'edad' => 'nullable|integer|min:0',
            'condicion' => 'nullable|array',
            'condicion.*' => 'in:normal,diabetico,hiposodico',
            'servicio_id' => 'nullable|exists:servicios,id',
            'cama_id' => 'nullable|exists:camas,id',
        ]);

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
            'edad' => 'nullable|integer|min:0',
            'condicion' => 'nullable|array',
            'condicion.*' => 'in:normal,diabetico,hiposodico',
            'servicio_id' => 'nullable|exists:servicios,id',
            'cama_id' => 'nullable|exists:camas,id',
        ]);

        // Verificar que la cama no esté ocupada por otro paciente
        if (!empty($data['cama_id'])) {
            $exists = Paciente::where('cama_id', $data['cama_id'])->where('id', '!=', $paciente->id)->exists();
            if ($exists) {
                return back()->withErrors(['cama_id' => 'La cama está ocupada por otro paciente.'])->withInput();
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
        return redirect()->route('pacientes.index')->with('success', 'Paciente actualizado.');
    }

    public function destroy(Paciente $paciente)
    {
        if (Auth::user() && Auth::user()->role === 'usuario') {
            return redirect()->route('pacientes.index')->with('error', 'No tienes permiso para eliminar pacientes.');
        }
        $paciente->delete();
        return redirect()->route('pacientes.index')->with('success', 'Paciente eliminado.');
    }
}
