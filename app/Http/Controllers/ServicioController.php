<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (!$user) {
                abort(403);
            }

            // Usuarios pueden ver (index/show); solo admin crea/edita/borra.
            $viewActions = ['index', 'show'];
            $action = $request->route()->getActionMethod();
            $isViewAction = in_array($action, $viewActions, true);

            if ($user->role !== 'admin' && !$isViewAction) {
                abort(403);
            }

            return $next($request);
        });
    }

    public function index()
    {
        $servicios = Servicio::paginate(20);
        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        return view('servicios.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:servicios,nombre',
            'descripcion' => 'nullable|string|max:1000',
        ]);

        Servicio::create($data);
        return redirect()->route('servicios.index')->with('success', 'Servicio creado.');
    }

    public function show(Servicio $servicio)
    {
        return view('servicios.show', compact('servicio'));
    }

    public function edit(Servicio $servicio)
    {
        return view('servicios.edit', compact('servicio'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:servicios,nombre,' . $servicio->id,
            'descripcion' => 'nullable|string|max:1000',
        ]);

        $servicio->update($data);
        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado.');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();
        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado.');
    }
}
