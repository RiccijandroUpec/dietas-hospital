<?php

namespace App\Http\Controllers;

use App\Models\Refrigerio;
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Refrigerio $refrigerio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Refrigerio $refrigerio)
    {
        $refrigerio->delete();

        return redirect()->route('refrigerios.index')->with('success', 'Refrigerio eliminado exitosamente.');
    }
}
