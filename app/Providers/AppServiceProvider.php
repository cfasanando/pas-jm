<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Carga settings una sola vez por request
        $settings = DB::table('settings')->pluck('value','key')->toArray();

        $name   = $settings['app.name']    ?? config('app.name');
        $logo   = trim($settings['app.logo'] ?? '');
        $primary= $settings['app.primary'] ?? '#0d6efd';

        // Fallback de logo a archivo local
        if ($logo === '') {
            $logo = asset('img/logo-jm.svg');
        } elseif (str_starts_with($logo, '/')) {
            // Permitir rutas relativas tipo /img/logo.svg
            $logo = asset(ltrim($logo, '/'));
        }

        $brand = [
            'name'    => $name,
            'logo'    => $logo,
            'primary' => $primary,
        ];

        View::share('brand', $brand);
    }
}
