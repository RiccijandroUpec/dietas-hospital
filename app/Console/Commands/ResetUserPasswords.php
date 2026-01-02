<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetUserPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:reset-passwords {--password=123456} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resetea todas las contraseñas de usuarios a una contraseña segura hasheada con Bcrypt (solo en primer deploy)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Archivo para rastrear si ya se hizo el primer deploy
        $deployMarkerFile = 'app/.deploy-initialized';
        $hasBeenDeployed = file_exists(base_path($deployMarkerFile));
        $force = $this->option('force');

        // Si ya se hizo el deploy y no se usa --force, salir
        if ($hasBeenDeployed && !$force) {
            if (!$this->option('quiet')) {
                $this->info('✓ Sistema ya fue inicializado. Las contraseñas no serán reseteadas.');
                $this->info('  Usa --force para resetear todas las contraseñas.');
            }
            return 0;
        }

        $password = $this->option('password');
        
        if (!$this->option('quiet')) {
            $this->info('Reseteando contraseñas de usuarios...');
        }
        
        $users = User::all();
        
        if ($users->isEmpty()) {
            if (!$this->option('quiet')) {
                $this->warn('No hay usuarios en la base de datos.');
            }
            return 0;
        }
        
        $updated = 0;
        
        foreach ($users as $user) {
            try {
                $user->password = Hash::make($password);
                $user->save();
                $updated++;
            } catch (\Exception $e) {
                $this->error("Error al actualizar usuario {$user->email}: " . $e->getMessage());
            }
        }
        
        // Crear archivo marcador para indicar que el deploy ya se inicializó
        if (!file_exists(base_path('app'))) {
            mkdir(base_path('app'), 0755, true);
        }
        file_put_contents(base_path($deployMarkerFile), date('Y-m-d H:i:s'));

        if (!$this->option('quiet')) {
            $this->info("✓ {$updated} usuarios actualizados correctamente.");
            $this->info("Contraseña por defecto: {$password}");
            $this->info("Sistema inicializado. El próximo deploy no reseteará las contraseñas.");
        }
        
        return 0;
    }
}
