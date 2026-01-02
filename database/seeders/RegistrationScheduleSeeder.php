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
        \App\Models\RegistrationSchedule::updateOrCreate(
            ['meal_type' => 'desayuno'],
            [
                'start_time' => '06:00',
                'end_time' => '09:00',
                'description' => 'Horario de desayuno',
            ]
        );

        \App\Models\RegistrationSchedule::updateOrCreate(
            ['meal_type' => 'almuerzo'],
            [
                'start_time' => '11:00',
                'end_time' => '14:00',
                'description' => 'Horario de almuerzo',
            ]
        );

        \App\Models\RegistrationSchedule::updateOrCreate(
            ['meal_type' => 'merienda'],
            [
                'start_time' => '16:00',
                'end_time' => '18:00',
                'description' => 'Horario de merienda/cena',
            ]
        );

        \App\Models\RegistrationSchedule::updateOrCreate(
            ['meal_type' => 'refrigerio'],
            [
                'start_time' => '08:00',
                'end_time' => '18:00',
                'description' => 'Horario de refrigerio (todo el día)',
            ]
        );
    }
}
