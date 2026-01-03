<?php

namespace App\Console\Commands;

use App\Models\Servicio;
use App\Models\Cama;
use Illuminate\Console\Command;

class UpdateBedsPrefix extends Command
{
    protected $signature = 'camas:update-prefix {servicio_id} {prefijo}';
    protected $description = 'Update bed codes prefix for a service';

    public function handle()
    {
        $servicioId = $this->argument('servicio_id');
        $prefijo = strtoupper($this->argument('prefijo'));
        
        $servicio = Servicio::find($servicioId);
        
        if (!$servicio) {
            $this->error("Servicio no encontrado");
            return 1;
        }

        $this->info("Servicio: {$servicio->nombre}");
        $this->info("Nuevo prefijo: {$prefijo}");
        
        // Update servicio prefijo
        $servicio->prefijo = $prefijo;
        $servicio->save();
        
        $camas = Cama::where('servicio_id', $servicioId)->orderBy('id')->get();
        
        $this->info("Camas encontradas: " . $camas->count());
        
        $counter = 1;
        foreach ($camas as $cama) {
            $oldCodigo = $cama->codigo;
            $newCodigo = $prefijo . $counter;
            
            $cama->codigo = $newCodigo;
            $cama->save();
            
            $this->line("Actualizada: {$oldCodigo} → {$newCodigo}");
            $counter++;
        }

        $this->info("✓ Actualización completada");
        return 0;
    }
}
