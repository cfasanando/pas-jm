PAS-JM — AUTH PACK
======================

Archivos incluidos:
- resources/views/layouts/guest.blade.php
- resources/views/auth/login.blade.php
- resources/views/auth/register.blade.php
- database/seeders/AdminUserSeeder.php

Comandos sugeridos (ejecutar en el proyecto):
1) composer require laravel/breeze --dev
2) php artisan breeze:install blade
3) php artisan migrate
4) Copiar estos archivos en las rutas de arriba (sobrescribir si pregunta).
5) php artisan optimize:clear
6) php artisan serve

Crear usuario admin de prueba:
php artisan db:seed --class=AdminUserSeeder

Proteger rutas en routes/web.php:
Route::middleware('auth')->group(function () { ... });
require __DIR__.'/auth.php';

Cambiar HOME post-login (opcional):
app/Providers/RouteServiceProvider.php => public const HOME = '/dashboard';
