<?php

namespace App\Http\Controllers;

use App\Models\RegistrationSchedule;
use Illuminate\Http\Request;

class ScheduleConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin'); // Solo admins
    }

    /**
     * Display all schedules
     */
    public function index()
    {
        $schedules = RegistrationSchedule::all()->keyBy('meal_type');
        return view('schedule-config.index', compact('schedules'));
    }

    /**
     * Show edit form for all schedules
     */
    public function edit()
    {
        $schedules = RegistrationSchedule::all()->keyBy('meal_type');
        $mealTypes = ['desayuno', 'almuerzo', 'merienda', 'refrigerio_mañana', 'refrigerio_tarde'];
        return view('schedule-config.edit', compact('schedules', 'mealTypes'));
    }

    /**
     * Update schedule
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'desayuno_start' => 'required|date_format:H:i',
            'desayuno_end' => 'required|date_format:H:i|after:desayuno_start',
            'almuerzo_start' => 'required|date_format:H:i',
            'almuerzo_end' => 'required|date_format:H:i|after:almuerzo_start',
            'merienda_start' => 'required|date_format:H:i',
            'merienda_end' => 'required|date_format:H:i|after:merienda_start',
            'refrigerio_manana_start' => 'required|date_format:H:i',
            'refrigerio_manana_end' => 'required|date_format:H:i|after:refrigerio_manana_start',
            'refrigerio_tarde_start' => 'required|date_format:H:i',
            'refrigerio_tarde_end' => 'required|date_format:H:i|after:refrigerio_tarde_start',
        ]);

        // Mapear los nombres de los campos del formulario a los tipos de comida en la DB
        $mealMappings = [
            'desayuno' => 'desayuno',
            'almuerzo' => 'almuerzo',
            'merienda' => 'merienda',
            'refrigerio_manana' => 'refrigerio_mañana',
            'refrigerio_tarde' => 'refrigerio_tarde'
        ];
        
        foreach ($mealMappings as $formField => $dbMealType) {
            RegistrationSchedule::updateOrCreate(
                ['meal_type' => $dbMealType],
                [
                    'start_time' => $validated["{$formField}_start"],
                    'end_time' => $validated["{$formField}_end"],
                ]
            );
        }

        return redirect()->route('schedule-config.index')->with('success', 'Horarios actualizados correctamente');
    }

    /**
     * Toggle allow out of schedule setting
     */
    public function toggleOutOfSchedule(Request $request)
    {
        $validated = $request->validate([
            'meal_type' => 'required|string|exists:registration_schedules,meal_type',
        ]);

        $schedule = RegistrationSchedule::where('meal_type', $validated['meal_type'])->firstOrFail();
        $schedule->allow_out_of_schedule = !$schedule->allow_out_of_schedule;
        $schedule->save();

        $status = $schedule->allow_out_of_schedule ? 'habilitado' : 'deshabilitado';
        return back()->with('success', "Registro fuera de horario {$status} para {$schedule->meal_type}");
    }
}
