<?php

namespace App\Http\Controllers;

use App\Models\TipoDieta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function edit(TipoDieta $tipo_dieta)
    {
        return view('tipos_dieta.edit', ['tipo' => $tipo_dieta]);
    }

    public function update(Request $request, TipoDieta $tipo_dieta)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:tipos_dieta,nombre,' . $tipo_dieta->id,
            'descripcion' => 'nullable|string|max:1000',
        ]);

        $tipo_dieta->update($data);
        return redirect()->route('tipos-dieta.index')->with('success', 'Tipo de dieta actualizado.');
    }

    public function destroy(TipoDieta $tipo_dieta)
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            return redirect()->route('tipos-dieta.index')->with('error', 'No tienes permiso para eliminar tipos de dieta.');
        }

        if ($tipo_dieta->dietas()->count() > 0 || $tipo_dieta->subtipos()->count() > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar el tipo porque tiene dietas o subtipos asociados.']);
        }
        
        $tipo_dieta->delete();
        return redirect()->route('tipos-dieta.index')->with('success', 'Tipo de dieta eliminado.');
    }
}
