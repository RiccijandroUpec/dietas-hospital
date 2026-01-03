<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Cama;
use App\Models\Paciente;
use Illuminate\Http\Request;

class CamasGraficaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $servicios = Servicio::orderBy('nombre')->get();
        $servicioId = $request->query('servicio_id', null);
        $camas = [];
        $pacientesPorCama = [];

        if ($servicioId) {
            $camas = Cama::where('servicio_id', $servicioId)
                ->orderByRaw('CAST(REGEXP_REPLACE(codigo, "[^0-9]", "") AS UNSIGNED)')
                ->get();
            
            // Obtener pacientes hospitalizados por cama
            $pacientesPorCama = Paciente::where('servicio_id', $servicioId)
                ->where('estado', 'hospitalizado')
                ->get()
                ->keyBy('cama_id');
        }

        return view('camas.grafica', compact('servicios', 'servicioId', 'camas', 'pacientesPorCama'));
    }
}
