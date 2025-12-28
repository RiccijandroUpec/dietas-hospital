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
    protected $signature = 'users:reset-passwords {--password=123456} {--no-interaction}';

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
        
        $this->info('Reseteando contraseñas de usuarios...');
        
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->warn('No hay usuarios en la base de datos.');
            return 0;
        }
        
        $updated = 0;
        
        foreach ($users as $user) {
            try {
                // Intentar verificar si la contraseña ya está hasheada
                // Si no lo está, Hash::check lanzará una excepción o devolverá false
                $user->password = Hash::make($password);
                $user->save();
                $updated++;
            } catch (\Exception $e) {
                $this->error("Error al actualizar usuario {$user->email}: " . $e->getMessage());
            }
        }
        
        $this->info("✓ {$updated} usuarios actualizados correctamente.");
        $this->info("Contraseña por defecto: {$password}");
        
        // Crear un usuario admin si no existe (solo si no estamos en modo no-interactivo)
        if (!User::where('role', 'admin')->exists() && !$this->option('no-interaction')) {
            if ($this->confirm('¿Deseas crear un usuario administrador?', true)) {
                $name = $this->ask('Nombre del administrador', 'Administrador');
                $email = $this->ask('Email del administrador', 'admin@hospital.com');
                
                User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'role' => 'admin',
                ]);
                
                $this->info("✓ Usuario administrador creado: {$email}");
            }
        }
        
        return 0;
    }
}
