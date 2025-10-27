# PAS-JM — Starter (Laravel snippets)

Estos archivos son para **copiar/pegar** dentro de un proyecto Laravel nuevo.

## Pasos rápidos

1) Crear proyecto (Laravel 11 recomendado):
```bash
composer create-project laravel/laravel pas-jm
cd pas-jm
cp .env.example .env
php artisan key:generate
```

2) Configurar **.env** para MySQL (ejemplo):
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pas_jm
DB_USERNAME=root
DB_PASSWORD=
```

3) Instalar login rápido (opcional pero recomendado): **Laravel Breeze (Blade)**
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
```

4) Copia los archivos de este ZIP en tu proyecto respetando rutas.
   - Si ya instalaste Breeze, puedes conservar su `layouts/app.blade.php` y borrar/ajustar éste.
   - Ejecuta migraciones:
```bash
php artisan migrate
php artisan storage:link
php artisan serve
```

5) Rutas principales:
- `/` Home
- `/actas/crear`
- `/actas/{id}/evidencias`
- `/mapa`
- `/dashboard`
- `/expedientes`
- `/boletas/{id}`

> Este starter guarda evidencias en `storage/app/public/evidencias/{ACTA_ID}/...`

## Próximos pasos
- Añadir roles/permissions (spatie/laravel-permission) y middleware por área.
- Generar PDF real de Boleta (dompdf o Snappy) y QR.
- Agregar Leaflet a `/mapa` con marcadores desde coordenadas de actas.
- KPIs reales en `/dashboard` con consultas.
