<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador
        User::updateOrCreate(
            ['email' => 'admin@hospital.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('123456'),
                'role' => 'admin',
            ]
        );

        // Crear nutricionista
        User::updateOrCreate(
            ['email' => 'nutricionista@hospital.com'],
            [
                'name' => 'Nutricionista',
                'password' => Hash::make('123456'),
                'role' => 'nutricionista',
            ]
        );

        // Crear enfermero
        User::updateOrCreate(
            ['email' => 'enfermero@hospital.com'],
            [
                'name' => 'Enfermero',
                'password' => Hash::make('123456'),
                'role' => 'enfermero',
            ]
        );

        // Crear usuario normal
        User::updateOrCreate(
            ['email' => 'usuario@hospital.com'],
            [
                'name' => 'Usuario',
                'password' => Hash::make('123456'),
                'role' => 'usuario',
            ]
        );

        echo "\n✓ Usuarios creados correctamente.\n";
        echo "Contraseña por defecto: 123456\n\n";
        echo "Usuarios disponibles:\n";
        echo "- admin@hospital.com (admin)\n";
        echo "- nutricionista@hospital.com (nutricionista)\n";
        echo "- enfermero@hospital.com (enfermero)\n";
        echo "- usuario@hospital.com (usuario)\n";
    }
}
