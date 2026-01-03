<?php

namespace Database\Seeders;

use App\Models\Paciente;
use Illuminate\Database\Seeder;

class RemoveNormalCondicionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Paciente::whereNotNull('condicion')
            ->orderBy('id')
            ->chunkById(500, function ($pacientes) {
                foreach ($pacientes as $paciente) {
                    $conds = collect(explode(',', (string) $paciente->condicion))
                        ->map(fn ($c) => trim($c))
                        ->filter()
                        ->reject(fn ($c) => strtolower($c) === 'normal')
                        ->unique()
                        ->values();

                    $paciente->condicion = $conds->isEmpty() ? null : $conds->implode(',');
                    $paciente->save();
                }
            });
    }
}
