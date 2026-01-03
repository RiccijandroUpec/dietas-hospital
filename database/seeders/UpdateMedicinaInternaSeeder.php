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
        // Buscar servicio MEDICINA INTERNA
        $servicio = Servicio::where('nombre', 'LIKE', '%MEDICINA%INTERNA%')
            ->orWhere('nombre', 'LIKE', '%Medicina%Interna%')
            ->first();

        if (!$servicio) {
            $this->command->warn('Servicio MEDICINA INTERNA no encontrado');
            return;
        }

        $this->command->info("Actualizando servicio: {$servicio->nombre}");

        // Actualizar prefijo del servicio
        $servicio->prefijo = 'MI';
        $servicio->save();

        // Obtener todas las camas del servicio ordenadas por ID
        $camas = Cama::where('servicio_id', $servicio->id)
            ->orderBy('id')
            ->get();

        $this->command->info("Camas encontradas: " . $camas->count());

        // Renumerar camas con nuevo prefijo
        $counter = 1;
        foreach ($camas as $cama) {
            $oldCodigo = $cama->codigo;
            $cama->codigo = 'MI' . $counter;
            $cama->save();
            
            $this->command->line("  {$oldCodigo} → {$cama->codigo}");
            $counter++;
        }

        $this->command->info("✓ Servicio y camas actualizados correctamente");
    }
}
