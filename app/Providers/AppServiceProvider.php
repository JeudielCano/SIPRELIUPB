<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;
use Illuminate\Foundation\Vite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // LÓGICA DINÁMICA: Detectar estructura de carpetas
        
        // 1. Si existe el archivo index.php en la raíz del proyecto (Caso Hostinger "Todo en Uno")
        if (file_exists(base_path('index.php'))) {
            // Le decimos a Laravel que la carpeta pública es la raíz
            $this->app->usePublicPath(base_path());
        }
        
        // 2. Ajustes para Producción (Solo si estamos en el servidor)
        if ($this->app->environment('production')) {
            // Forzamos HTTPS y la URL correcta del subdominio
            URL::forceRootUrl(config('app.url'));
            URL::forceScheme('https');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}