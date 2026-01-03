<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed users
        $this->call(\Database\Seeders\UsersSeeder::class);

        // Seed servicios and camas
        $this->call(\Database\Seeders\ServiciosCamasSeeder::class);

        // Seed registration schedules
        $this->call(\Database\Seeders\RegistrationScheduleSeeder::class);

    }
}
