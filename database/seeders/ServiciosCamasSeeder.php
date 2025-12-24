<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicio;
use App\Models\Cama;

class ServiciosCamasSeeder extends Seeder
{
    public function run()
    {
        // Definir 7 servicios, el primero será UCI con 5 camas
        $servicios = [
            ['nombre' => 'UCI', 'camas' => 5],
            ['nombre' => 'Servicio 2', 'camas' => 42],
            ['nombre' => 'Servicio 3', 'camas' => 42],
            ['nombre' => 'Servicio 4', 'camas' => 42],
            ['nombre' => 'Servicio 5', 'camas' => 42],
            ['nombre' => 'Servicio 6', 'camas' => 42],
            ['nombre' => 'Servicio 7', 'camas' => 42],
        ];

        foreach ($servicios as $sIndex => $sData) {
            $servicio = Servicio::firstOrCreate(['nombre' => $sData['nombre']]);

            // Crear camas si no existen suficientes
            $existing = Cama::where('servicio_id', $servicio->id)->count();
            for ($c = $existing + 1; $c <= $sData['camas']; $c++) {
                // Usar números simples como código (1,2,3...)
                Cama::create([
                    'codigo' => (string)$c,
                    'servicio_id' => $servicio->id,
                ]);
            }
        }
    }
}
