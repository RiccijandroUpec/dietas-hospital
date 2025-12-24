<?php

namespace App\Http\Controllers;

use App\Models\PacienteDieta;
use App\Models\Paciente;
use App\Models\Dieta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PacienteDietaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = PacienteDieta::with(['paciente', 'dieta', 'createdBy', 'updatedBy'])->orderByDesc('created_at')->paginate(25);
        return view('paciente_dietas.index', compact('items'));
    }

    public function create()
    {
        $dietas = Dieta::orderBy('nombre')->get();
        $pacientes = Paciente::orderBy('nombre')->get();
        return view('paciente_dietas.create', compact('dietas','pacientes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'dieta_id' => 'required|exists:dietas,id',
        ]);

        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        PacienteDieta::create($data);
        return redirect()->route('paciente-dietas.index')->with('success', 'Registro guardado.');
    }

    public function show(PacienteDieta $paciente_dieta)
    {
        $item = $paciente_dieta->load(['paciente','dieta','createdBy','updatedBy']);
        return view('paciente_dietas.show', compact('item'));
    }

    public function edit(PacienteDieta $paciente_dieta)
    {
        $dietas = Dieta::orderBy('nombre')->get();
        $pacientes = Paciente::orderBy('nombre')->get();
        return view('paciente_dietas.edit', ['item' => $paciente_dieta, 'dietas' => $dietas, 'pacientes' => $pacientes]);
    }

    public function update(Request $request, PacienteDieta $paciente_dieta)
    {
        $data = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'dieta_id' => 'required|exists:dietas,id',
        ]);

        $data['updated_by'] = Auth::id();
        $paciente_dieta->update($data);
        return redirect()->route('paciente-dietas.index')->with('success', 'Registro actualizado.');
    }

    public function destroy(PacienteDieta $paciente_dieta)
    {
        $paciente_dieta->delete();
        return redirect()->route('paciente-dietas.index')->with('success', 'Registro eliminado.');
    }
}
