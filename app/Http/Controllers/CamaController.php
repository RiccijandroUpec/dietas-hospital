<?php

namespace App\Http\Controllers;

use App\Models\Cama;
use App\Models\Servicio;
use Illuminate\Http\Request;

class CamaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || auth()->user()->role !== 'admin') {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        $camas = Cama::with('servicio')->paginate(30);
        return view('camas.index', compact('camas'));
    }

    public function create()
    {
        $servicios = Servicio::all();
        return view('camas.create', compact('servicios'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo' => 'required|string|max:100|unique:camas,codigo',
            'servicio_id' => 'nullable|exists:servicios,id',
        ]);

        Cama::create($data);
        return redirect()->route('camas.index')->with('success', 'Cama creada.');
    }

    public function edit(Cama $cama)
    {
        $servicios = Servicio::all();
        return view('camas.edit', compact('cama', 'servicios'));
    }

    public function update(Request $request, Cama $cama)
    {
        $data = $request->validate([
            'codigo' => 'required|string|max:100|unique:camas,codigo,' . $cama->id,
            'servicio_id' => 'nullable|exists:servicios,id',
        ]);

        $cama->update($data);
        return redirect()->route('camas.index')->with('success', 'Cama actualizada.');
    }

    public function destroy(Cama $cama)
    {
        $cama->delete();
        return redirect()->route('camas.index')->with('success', 'Cama eliminada.');
    }
}
