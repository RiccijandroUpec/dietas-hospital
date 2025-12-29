<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // Solo administradores pueden ver el historial de auditoría
        if (auth()->user()->role !== 'administrador' && auth()->user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'No tienes permiso para acceder al historial de auditoría.');
        }

        $query = Audit::with('user')->latest();

        // Filtros
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        if ($request->has('model_type') && $request->model_type) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('fecha_desde') && $request->fecha_desde) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->has('fecha_hasta') && $request->fecha_hasta) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $audits = $query->paginate(20);

        // Obtener lista de usuarios y acciones para los filtros
        $usuarios = \App\Models\User::select('id', 'name')->orderBy('name')->get();
        $acciones = Audit::select('action')->distinct()->orderBy('action')->pluck('action');
        $modelos = Audit::select('model_type')->distinct()->whereNotNull('model_type')->orderBy('model_type')->pluck('model_type');

        return view('audits.index', compact('audits', 'usuarios', 'acciones', 'modelos'));
    }

    public function show(Audit $audit)
    {
        // Solo administradores
        if (auth()->user()->role !== 'administrador' && auth()->user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'No tienes permiso.');
        }

        return view('audits.show', compact('audit'));
    }
}
