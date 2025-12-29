<?php

namespace App\Http\Controllers;

use App\Models\Refrigerio;
use App\Services\AuditService;
use Illuminate\Http\Request;

class RefrigerioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $refrigerios = Refrigerio::latest()->paginate(10);
        return view('refrigerios.index', compact('refrigerios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('refrigerios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        Refrigerio::create($validated);

        // Registrar auditoría
        AuditService::log('create', "Refrigerio creado: {$validated['nombre']}", 'Refrigerio', null);

        return redirect()->route('refrigerios.index')->with('success', 'Refrigerio creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Refrigerio $refrigerio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Refrigerio $refrigerio)
    {
        return view('refrigerios.edit', compact('refrigerio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Refrigerio $refrigerio)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $refrigerio->update($validated);

        // Registrar auditoría
        AuditService::log('update', "Refrigerio actualizado: {$refrigerio->nombre}", 'Refrigerio', $refrigerio->id);

        return redirect()->route('refrigerios.index')->with('success', 'Refrigerio actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Refrigerio $refrigerio)
    {
        // Registrar auditoría antes de eliminar
        AuditService::log('delete', "Refrigerio eliminado: {$refrigerio->nombre}", 'Refrigerio', $refrigerio->id);

        $refrigerio->delete();

        return redirect()->route('refrigerios.index')->with('success', 'Refrigerio eliminado exitosamente.');
    }
}
