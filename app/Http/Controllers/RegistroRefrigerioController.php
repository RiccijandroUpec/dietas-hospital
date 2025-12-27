<?php

namespace App\Http\Controllers;

use App\Models\RegistroRefrigerio;
use App\Models\Paciente;
use App\Models\Refrigerio;
use Illuminate\Http\Request;

class RegistroRefrigerioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = RegistroRefrigerio::query()->with(['paciente', 'refrigerio', 'createdBy', 'updatedBy'])->latest();

        if (request('fecha')) {
            $query->whereDate('fecha', request('fecha'));
        }
        if (request('momento')) {
            $query->where('momento', request('momento'));
        }
        if (request('buscar')) {
            $buscar = request('buscar');
            $query->where(function ($q) use ($buscar) {
                // Paciente nombre/apellido/cedula
                $q->whereHas('paciente', function ($qP) use ($buscar) {
                    $qP->where('nombre', 'like', "%{$buscar}%")
                       ->orWhere('apellido', 'like', "%{$buscar}%")
                       ->orWhere('cedula', 'like', "%{$buscar}%");
                })
                // Refrigerio nombre
                ->orWhereHas('refrigerio', function ($qR) use ($buscar) {
                    $qR->where('nombre', 'like', "%{$buscar}%");
                })
                // Momento
                ->orWhere('momento', 'like', "%{$buscar}%");
            });
        }

        $registros = $query->paginate(10)->appends(request()->query());
        return view('registro_refrigerios.index', compact('registros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pacientes = Paciente::where('estado', 'hospitalizado')->orderBy('nombre')->get();
        $refrigerios = Refrigerio::orderBy('nombre')->get();
        $momentos = ['mañana', 'tarde', 'noche'];
        return view('registro_refrigerios.create', compact('pacientes', 'refrigerios', 'momentos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'refrigerio_ids' => 'required|array|min:1',
            'refrigerio_ids.*' => 'exists:refrigerios,id',
            'fecha' => 'required|date',
            'momento' => 'required|in:mañana,tarde,noche',
            'observacion' => 'nullable|string',
        ]);

        $paciente = Paciente::findOrFail($validated['paciente_id']);
        if ($paciente->estado !== 'hospitalizado') {
            return back()->with('error', 'Solo pacientes hospitalizados pueden recibir refrigerios.')->withInput();
        }

        // Crear un registro por cada refrigerio seleccionado
        foreach ($validated['refrigerio_ids'] as $refrigerio_id) {
            RegistroRefrigerio::create([
                'paciente_id' => $validated['paciente_id'],
                'refrigerio_id' => $refrigerio_id,
                'fecha' => $validated['fecha'],
                'momento' => $validated['momento'],
                'observacion' => $validated['observacion'],
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);
        }
        
        return redirect()->route('registro-refrigerios.index')->with('success', 'Refrigerio(s) registrado(s) exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RegistroRefrigerio $registroRefrigerio)
    {
        $registroRefrigerio->load(['paciente', 'refrigerio', 'createdBy', 'updatedBy']);

        // Obtener todos los refrigerios del mismo paciente en la misma fecha y momento
        $refrigeriosDelMomento = RegistroRefrigerio::with('refrigerio')
            ->where('paciente_id', $registroRefrigerio->paciente_id)
            ->whereDate('fecha', $registroRefrigerio->fecha)
            ->where('momento', $registroRefrigerio->momento)
            ->get()
            ->pluck('refrigerio')
            ->filter()
            ->unique('id')
            ->values();

        return view('registro_refrigerios.show', compact('registroRefrigerio', 'refrigeriosDelMomento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RegistroRefrigerio $registroRefrigerio)
    {
        $pacientes = Paciente::where('estado', 'hospitalizado')->orderBy('nombre')->get();
        $refrigerios = Refrigerio::orderBy('nombre')->get();
        $momentos = ['mañana', 'tarde', 'noche'];
        return view('registro_refrigerios.edit', compact('registroRefrigerio', 'pacientes', 'refrigerios', 'momentos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RegistroRefrigerio $registroRefrigerio)
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'refrigerio_id' => 'required|exists:refrigerios,id',
            'fecha' => 'required|date',
            'momento' => 'required|in:mañana,tarde,noche',
            'observacion' => 'nullable|string',
        ]);
        
        $validated['updated_by'] = auth()->id();
        
        $registroRefrigerio->update($validated);
        return redirect()->route('registro-refrigerios.index')->with('success', 'Registro actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RegistroRefrigerio $registroRefrigerio)
    {
        $registroRefrigerio->delete();
        return redirect()->route('registro-refrigerios.index')->with('success', 'Registro eliminado.');
    }

    public function reporte()
    {
        $total = RegistroRefrigerio::count();
        $hoy = RegistroRefrigerio::whereDate('created_at', today())->count();
        $porMomento = RegistroRefrigerio::select('momento')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('momento')
            ->get();

        $registros = RegistroRefrigerio::with(['paciente','refrigerio'])
            ->latest()->paginate(15);
        return view('registro_refrigerios.reporte', compact('total','hoy','porMomento','registros'));
    }
}
