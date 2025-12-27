<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;


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
    }
}
