<?php

namespace App\Console\Commands;

use App\Models\Cama;
use Illuminate\Console\Command;

class DeleteBedsCommand extends Command
{
    protected $signature = 'camas:delete-pattern {pattern}';
    protected $description = 'Delete beds matching a pattern in codigo';

    public function handle()
    {
        $pattern = $this->argument('pattern');
        
        $camas = Cama::where('codigo', 'like', "%{$pattern}%")->get();
        
        if ($camas->isEmpty()) {
            $this->info("No se encontraron camas con el patrón '{$pattern}'");
            return 0;
        }

        $this->info("Camas encontradas: " . $camas->count());
        
        foreach ($camas as $cama) {
            $this->line("ID: {$cama->id} - Código: {$cama->codigo} - Servicio: {$cama->servicio_id}");
        }

        if ($this->confirm('¿Deseas eliminar estas camas?', false)) {
            $deleted = Cama::where('codigo', 'like', "%{$pattern}%")->delete();
            $this->info("Se eliminaron {$deleted} cama(s)");
        } else {
            $this->info('Operación cancelada');
        }

        return 0;
    }
}
