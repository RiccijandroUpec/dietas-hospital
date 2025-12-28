<?php

namespace App\Http\Controllers;

use App\Models\Cama;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
            'cantidad_camas' => 'nullable|integer|min:0|max:500',
        ]);

        $cantidadCamas = (int) ($data['cantidad_camas'] ?? 0);

        $servicio = DB::transaction(function () use ($data, $cantidadCamas) {
            $servicio = Servicio::create([
                'nombre' => $data['nombre'],
                'descripcion' => $data['descripcion'] ?? null,
            ]);

            if ($cantidadCamas > 0) {
                $prefix = strtoupper(Str::slug($servicio->nombre, '_'));
                $prefix = $prefix !== '' ? $prefix : 'SERVICIO_' . $servicio->id;

                $start = Cama::where('servicio_id', $servicio->id)->count() + 1;

                for ($i = 0; $i < $cantidadCamas; $i++) {
                    $counter = $start + $i;
                    $codigo = $prefix . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);

                    // Garantiza unicidad global del código de cama
                    while (Cama::where('codigo', $codigo)->exists()) {
                        $counter++;
                        $codigo = $prefix . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT);
                    }

                    Cama::create([
                        'codigo' => $codigo,
                        'servicio_id' => $servicio->id,
                    ]);
                }
            }

            return $servicio;
        });

        return redirect()
            ->route('servicios.index')
            ->with('success', "Servicio creado con {$cantidadCamas} cama(s).");
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
