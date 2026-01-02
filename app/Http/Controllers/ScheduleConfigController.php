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
        $mealTypes = ['desayuno', 'almuerzo', 'merienda', 'refrigerio'];
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
            'refrigerio_start' => 'required|date_format:H:i',
            'refrigerio_end' => 'required|date_format:H:i|after:refrigerio_start',
        ]);

        $mealTypes = ['desayuno', 'almuerzo', 'merienda', 'refrigerio'];
        
        foreach ($mealTypes as $meal) {
            RegistrationSchedule::updateOrCreate(
                ['meal_type' => $meal],
                [
                    'start_time' => $validated["{$meal}_start"],
                    'end_time' => $validated["{$meal}_end"],
                ]
            );
        }

        return redirect()->route('schedule-config.index')->with('success', 'Horarios actualizados correctamente');
    }
}
