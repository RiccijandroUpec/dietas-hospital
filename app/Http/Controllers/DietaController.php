<?php

namespace App\Http\Controllers;

use App\Models\Dieta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DietaController extends Controller
{
    public function __construct()
    {
        // Allow public access to index and show; require auth for other actions
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $dietas = Dieta::orderBy('nombre')->paginate(15);
        return view('dietas.index', compact('dietas'));
    }

    public function show(Dieta $dieta)
    {
        return view('dietas.show', compact('dieta'));
    }

    public function create()
    {
        if (Auth::user() && Auth::user()->role !== 'admin') {
            return redirect()->route('dietas.index')->with('error', 'No tienes permiso para crear dietas.');
        }
        return view('dietas.create');
    }

    public function store(Request $request)
    {
        if (Auth::user() && Auth::user()->role !== 'admin') {
            return redirect()->route('dietas.index')->with('error', 'No tienes permiso para crear dietas.');
        }
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        Dieta::create($data);
        return redirect()->route('dietas.index')->with('success', 'Dieta creada.');
    }

    public function edit(Dieta $dieta)
    {
        if (Auth::user() && Auth::user()->role !== 'admin') {
            return redirect()->route('dietas.index')->with('error', 'No tienes permiso para editar dietas.');
        }
        return view('dietas.edit', compact('dieta'));
    }

    public function update(Request $request, Dieta $dieta)
    {
        if (Auth::user() && Auth::user()->role !== 'admin') {
            return redirect()->route('dietas.index')->with('error', 'No tienes permiso para actualizar dietas.');
        }
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $dieta->update($data);
        return redirect()->route('dietas.index')->with('success', 'Dieta actualizada.');
    }

    public function destroy(Dieta $dieta)
    {
        if (Auth::user() && Auth::user()->role !== 'admin') {
            return redirect()->route('dietas.index')->with('error', 'No tienes permiso para eliminar dietas.');
        }
        $dieta->delete();
        return redirect()->route('dietas.index')->with('success', 'Dieta eliminada.');
    }

    // Live search endpoint
    public function search(Request $request)
    {
        $q = $request->query('q');
        $dietas = Dieta::query()
            ->when($q, function ($query) use ($q) {
                $query->where('nombre', 'like', "%{$q}%")
                      ->orWhere('descripcion', 'like', "%{$q}%");
            })
            ->orderBy('nombre')
            ->limit(50)
            ->get()
            ->map(function ($d) {
                return [
                    'id' => $d->id,
                    'nombre' => $d->nombre,
                    'descripcion' => $d->descripcion,
                    'show_url' => route('dietas.show', $d),
                    'edit_url' => (Auth::check() && Auth::user()->role === 'admin') ? route('dietas.edit', $d) : null,
                ];
            });

        return response()->json($dietas);
    }
}
