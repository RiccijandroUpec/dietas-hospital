<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicio;
use App\Models\Cama;

class NormalizeCamasSeeder extends Seeder
{
    public function run()
    {
        $servicios = Servicio::all();

        foreach ($servicios as $servicio) {
            $desired = ($servicio->nombre === 'UCI') ? 5 : 42;

            // Obtener camas existentes ordenadas por id
            $camas = Cama::where('servicio_id', $servicio->id)->orderBy('id')->get();

            // Si hay menos camas, crear las faltantes
            if ($camas->count() < $desired) {
                $start = $camas->count() + 1;
                for ($i = $start; $i <= $desired; $i++) {
                    Cama::create([
                        'codigo' => (string)$i,
                        'servicio_id' => $servicio->id,
                    ]);
                }
                $camas = Cama::where('servicio_id', $servicio->id)->orderBy('id')->get();
            }

            // Si hay más camas de las deseadas y es UCI, eliminar extras (las últimas)
            if ($camas->count() > $desired) {
                if ($servicio->nombre === 'UCI') {
                    $toDelete = $camas->slice($desired);
                    foreach ($toDelete as $del) {
                        $del->delete();
                    }
                    $camas = Cama::where('servicio_id', $servicio->id)->orderBy('id')->get();
                }
                // For other services, keep extras but we'll re-number
            }

            // Re-enumerar códigos como números 1..N
            $i = 1;
            foreach ($camas as $cama) {
                $cama->codigo = (string)$i;
                $cama->save();
                $i++;
            }
        }
    }
}
