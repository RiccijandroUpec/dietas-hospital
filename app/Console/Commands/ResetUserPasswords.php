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
    protected $signature = 'users:reset-passwords {--password=123456}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resetea todas las contraseñas de usuarios a una contraseña segura hasheada con Bcrypt';

    /**
     * Execute the console command.
     */
    public function handle()
    {
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
        
        if (!$this->option('quiet')) {
            $this->info("✓ {$updated} usuarios actualizados correctamente.");
            $this->info("Contraseña por defecto: {$password}");
        }
        
        return 0;
    }
}
