<?php

namespace App\Http\Controllers;

use App\Models\SubtipoDieta;
use App\Models\TipoDieta;
use Illuminate\Http\Request;

class SubtipoDietaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $subtipos = SubtipoDieta::with('tipo')->withCount('dietas')->orderBy('nombre')->paginate(20);
        return view('subtipos_dieta.index', compact('subtipos'));
    }

    public function create()
    {
        $tipos = TipoDieta::orderBy('nombre')->get();
        return view('subtipos_dieta.create', compact('tipos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipo_dieta_id' => 'required|exists:tipos_dieta,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        SubtipoDieta::create($data);
        return redirect()->route('subtipos-dieta.index')->with('success', 'Subtipo de dieta creado exitosamente.');
    }

    public function edit(SubtipoDieta $subtipos_dieta)
    {
        $tipos = TipoDieta::orderBy('nombre')->get();
        return view('subtipos_dieta.edit', ['subtipo' => $subtipos_dieta, 'tipos' => $tipos]);
    }

    public function update(Request $request, SubtipoDieta $subtipos_dieta)
    {
        $data = $request->validate([
            'tipo_dieta_id' => 'required|exists:tipos_dieta,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        $subtipos_dieta->update($data);
        return redirect()->route('subtipos-dieta.index')->with('success', 'Subtipo de dieta actualizado.');
    }

    public function destroy(SubtipoDieta $subtipos_dieta)
    {
        if ($subtipos_dieta->dietas()->count() > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar el subtipo porque tiene dietas asociadas.']);
        }
        
        $subtipos_dieta->delete();
        return redirect()->route('subtipos-dieta.index')->with('success', 'Subtipo de dieta eliminado.');
    }
}
