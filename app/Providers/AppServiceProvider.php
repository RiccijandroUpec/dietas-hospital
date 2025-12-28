<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Establecer el idioma de Carbon para formatos traducidos
        Carbon::setLocale(config('app.locale', 'es'));

        // Forzar HTTPS en producción (se deja como está en el proyecto)
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Ensure test user exists on Railway deployment
        if (app()->environment('production') || app()->environment('staging')) {
            try {
                $testUserEmail = 'test@example.com';
                if (!User::where('email', $testUserEmail)->exists()) {
                    User::create([
                        'name' => 'Test User',
                        'email' => $testUserEmail,
                        'password' => Hash::make('password'),
                        'role' => 'usuario',
                        'email_verified_at' => now(),
                    ]);
                }
            } catch (\Exception $e) {
                // Silenciosamente ignorar si hay error (ej: tabla no existe aún)
            }
        }
    }
}
