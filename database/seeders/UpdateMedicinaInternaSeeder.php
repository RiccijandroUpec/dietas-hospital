<?php

namespace Database\Seeders;

use App\Models\Servicio;
use App\Models\Cama;
use Illuminate\Database\Seeder;

class UpdateMedicinaInternaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mapeo de servicios a prefijos
        $servicios = [
            'MEDICINA INTERNA' => 'MI',
            'CIRUGIA' => 'C',
            'PEDIATRIA' => 'P',
            'GINECOLOGIA' => 'GO',
            'TRAUMATOLOGIA' => 'T',
            'DIALISIS' => 'D',
            'EMERGENCIA' => 'E',
        ];

        foreach ($servicios as $nombreServicio => $prefijo) {
            $servicio = Servicio::where('nombre', 'LIKE', "%{$nombreServicio}%")
                ->orWhere('nombre', 'LIKE', "%" . ucfirst(strtolower($nombreServicio)) . "%")
                ->first();

            if (!$servicio) {
                $this->command->warn("Servicio {$nombreServicio} no encontrado");
                continue;
            }

            $this->command->info("Actualizando: {$servicio->nombre} → {$prefijo}");

            // Actualizar prefijo del servicio
            $servicio->prefijo = $prefijo;
            $servicio->save();

            // Obtener todas las camas del servicio ordenadas por ID
            $camas = Cama::where('servicio_id', $servicio->id)
                ->orderBy('id')
                ->get();

            if ($camas->isEmpty()) {
                $this->command->line("  Sin camas para actualizar");
                continue;
            }

            // Renumerar camas con nuevo prefijo
            $counter = 1;
            foreach ($camas as $cama) {
                $oldCodigo = $cama->codigo;
                $cama->codigo = $prefijo . $counter;
                $cama->save();
                
                $this->command->line("  {$oldCodigo} → {$cama->codigo}");
                $counter++;
            }

            $this->command->info("✓ {$camas->count()} cama(s) actualizadas");
        }

        $this->command->info("✓✓✓ Proceso completado");
    }
}
