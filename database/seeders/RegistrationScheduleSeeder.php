<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegistrationScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Solo crear si no existen los registros
        \App\Models\RegistrationSchedule::firstOrCreate(
            ['meal_type' => 'desayuno'],
            [
                'start_time' => '06:00',
                'end_time' => '08:00',
                'description' => 'Horario de desayuno',
            ]
        );

        \App\Models\RegistrationSchedule::firstOrCreate(
            ['meal_type' => 'almuerzo'],
            [
                'start_time' => '06:00',
                'end_time' => '11:00',
                'description' => 'Horario de almuerzo',
            ]
        );

        \App\Models\RegistrationSchedule::firstOrCreate(
            ['meal_type' => 'merienda'],
            [
                'start_time' => '00:00',
                'end_time' => '16:00',
                'description' => 'Horario de merienda/cena',
            ]
        );

        \App\Models\RegistrationSchedule::firstOrCreate(
            ['meal_type' => 'refrigerio_mañana'],
            [
                'start_time' => '06:00',
                'end_time' => '10:00',
                'description' => 'Horario de refrigerio en la mañana',
            ]
        );

        \App\Models\RegistrationSchedule::updateOrCreate(
            ['meal_type' => 'refrigerio_tarde'],
            [
                'start_time' => '06:00',
                'end_time' => '14:00',
                'description' => 'Horario de refrigerio en la tarde',
            ]
        );
    }
}
