<?php

namespace App\Http\Controllers;

use App\Models\TipoDieta;
use Illuminate\Http\Request;

class TipoDietaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tipos = TipoDieta::withCount(['subtipos', 'dietas'])->orderBy('nombre')->paginate(20);
        return view('tipos_dieta.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipos_dieta.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:tipos_dieta,nombre',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        TipoDieta::create($data);
        return redirect()->route('tipos-dieta.index')->with('success', 'Tipo de dieta creado exitosamente.');
    }

    public function edit(TipoDieta $tipos_dieta)
    {
        return view('tipos_dieta.edit', ['tipo' => $tipos_dieta]);
    }

    public function update(Request $request, TipoDieta $tipos_dieta)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:tipos_dieta,nombre,' . $tipos_dieta->id,
            'descripcion' => 'nullable|string|max:1000',
        ]);

        $tipos_dieta->update($data);
        return redirect()->route('tipos-dieta.index')->with('success', 'Tipo de dieta actualizado.');
    }

    public function destroy(TipoDieta $tipos_dieta)
    {
        if ($tipos_dieta->dietas()->count() > 0 || $tipos_dieta->subtipos()->count() > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar el tipo porque tiene dietas o subtipos asociados.']);
        }
        
        $tipos_dieta->delete();
        return redirect()->route('tipos-dieta.index')->with('success', 'Tipo de dieta eliminado.');
    }
}
