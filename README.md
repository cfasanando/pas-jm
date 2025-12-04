# Acta de Fiscalización Web · PAS Jesús María

Sistema web desarrollado con **Laravel** para gestionar el **Acta de Fiscalización** dentro del
Procedimiento Administrativo Sancionador (PAS) de la Municipalidad de Jesús María.

Permite registrar:

- Actas de fiscalización con ubicación geográfica.
- Infracciones y tipificaciones asociadas.
- Boletas de multa y recaudación.
- Evidencias (fotos, videos).
- Expedientes y su estado dentro del proceso.
- Administración de usuarios y roles (admin, inspector, jefe).
- Dashboard con indicadores clave (KPIs) y un mapa de incidencias.

> Proyecto académico desarrollado para la Universidad Tecnológica del Perú (UTP).

---

## Tabla de contenido

- [Tecnologías](#tecnologías)
- [Arquitectura y módulos](#arquitectura-y-módulos)
- [Requisitos previos](#requisitos-previos)
- [Instalación y configuración](#instalación-y-configuración)
- [Ejecución en local](#ejecución-en-local)
- [Despliegue](#despliegue)
- [Pruebas de software](#pruebas-de-software)
- [Monitoreo y Lighthouse](#monitoreo-y-lighthouse)
- [Mantenimiento y soporte](#mantenimiento-y-soporte)
- [Roadmap](#roadmap)

---

## Tecnologías

- **Laravel 11** (PHP 8.x)
- **MySQL** (estructura normalizada para PAS)
- Autenticación con Laravel Breeze (Blade)
- Vistas Blade + Bootstrap/estilos personalizados
- GitHub + GitHub Pages para la landing pública (`/docs`)

---

## Arquitectura y módulos

El proyecto sigue el patrón **MVC** de Laravel:

### Backend y rutas principales

- `/dashboard` – Indicadores clave: número de actas, recaudación, días con más sanciones, etc.
- `/mapa` – Mapa de incidencias utilizando coordenadas de las actas.
- `/actas` – Listado de actas, creación, edición y gestión de tipificaciones/evidencias.
- `/boletas` – Boletas de multa asociadas a actas y administrados.
- `/expedientes` – Seguimiento del expediente para cada acta.
- `/admin` – Usuarios, infracciones, series y configuración general.

Las rutas están agrupadas con middleware:

- `auth` – Sólo usuarios autenticados.
- `role:admin` – Administración y configuración.
- `role:admin,inspector` – Gestión de actas y boletas.

### Modelo de datos (tablas principales)

- `actas` – Acta de fiscalización (fecha, hora, lugar, inspector, administrado, estado…).
- `tipificaciones` y `infracciones` – Tipo de infracción, base legal y monto.
- `evidencias` – Archivos de evidencia (foto/video) por acta.
- `boletas` – Boleta de multa (monto, estado, PDF, QR).
- `expedientes` – Seguimiento administrativo del caso.
- `administrados` – Empresas o personas sancionadas.
- `roles` y `role_user` – Roles de usuario (admin, inspector, jefe).

---

## Requisitos previos

- PHP **8.2+**
- Composer
- MySQL 8.x (o compatible)
- Node.js + npm (para compilar assets)
- Extensiones PHP típicas de Laravel (mbstring, openssl, pdo_mysql, etc.)

---

## Instalación y configuración

1. **Clonar el repositorio**

   ```bash
   git clone https://github.com/cfasanando/pas-jm.git
   cd pas-jm
   ```

2. **Instalar dependencias PHP**

   ```bash
   composer install
   ```

3. **Instalar dependencias frontend**

   ```bash
   npm install
   npm run build
   ```

4. **Configurar entorno**

   Crear `.env` a partir de `.env.example`:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Configurar la base de datos (ejemplo):

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pas_jm
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Crear base de datos y ejecutar migraciones**

   Crear la BD `pas_jm` en MySQL y luego:

   ```bash
   php artisan migrate
   ```

   > Si se incluyen *seeders*, puedes usarlos con:
   >
   > ```bash
   > php artisan migrate --seed
   > ```

6. **Enlace de almacenamiento (evidencias, PDFs)**

   ```bash
   php artisan storage:link
   ```

---

## Ejecución en local

Levantar el servidor de desarrollo:

```bash
php artisan serve
```

La aplicación quedará disponible en:

- `http://127.0.0.1:8000` (o el host/puerto que indique Artisan).

Rutas más usadas durante las pruebas:

- `http://127.0.0.1:8000/dashboard`
- `http://127.0.0.1:8000/actas`
- `http://127.0.0.1:8000/expedientes`
- `http://127.0.0.1:8000/admin`

---

## Despliegue

### Aplicación Laravel

Para un despliegue real (servidor de producción):

1. Subir el proyecto a un servidor con PHP 8.x y MySQL.
2. Configurar `.env` con credenciales de producción.
3. Ejecutar:

   ```bash
   composer install --no-dev
   php artisan migrate --force
   php artisan storage:link
   npm install
   npm run build
   ```

4. Configurar el VirtualHost / vhost del servidor web (Nginx/Apache) apuntando al directorio `public/`.

### Landing estática en GitHub Pages

Este repositorio usa GitHub Pages para la **presentación del proyecto**:

- La página pública se encuentra en:  
  `https://cfasanando.github.io/pas-jm/`
- El contenido estático vive en la carpeta [`docs/`](docs/index.html).

Configuración (ya aplicada en el repo):

1. En GitHub → *Settings* → *Pages*.
2. Source: `Deploy from a branch`.
3. Branch: `main` y carpeta `/docs`.

---

## Pruebas de software

El proyecto contempla pruebas en dos niveles:

### 1. Pruebas automáticas (PHPUnit)

Laravel ya viene preparado para pruebas con PHPUnit.

- Ejecutar todos los tests:

  ```bash
  php artisan test
  ```

- Se pueden añadir tests unitarios/feature en `tests/` para:
  - Creación de actas y tipificaciones.
  - Emisión de boletas.
  - Restricción por roles y autenticación.
  - Acceso a dashboard y KPIs.

### 2. Pruebas manuales funcionales

Se han definido flujos de prueba manual para los casos críticos:

- **Flujo de acta completa**
  - Crear acta → agregar tipificaciones → adjuntar evidencias → geolocalizar.
- **Flujo de boleta**
  - Generar boleta desde un acta → validar monto → cambiar estado (emitida / notificada / anulada).
- **Flujo de expediente**
  - Abrir expediente desde un acta → cambiar estados (abierto, en trámite, concluido, archivado).
- **Dashboard y mapa**
  - Ver KPIs actualizados al registrar nuevas actas/boletas.
  - Validar marcadores en el mapa según lat/lng de las actas.

Los resultados y errores detectados se revisan en los logs de Laravel (ver sección de mantenimiento).

---

## Monitoreo y Lighthouse

Para evaluar la calidad del frontend se utiliza **Chrome Lighthouse**:

1. Abrir el sistema en Chrome (por ejemplo, `/dashboard` o `/actas`).
2. Ir a *DevTools* → pestaña **Lighthouse**.
3. Ejecutar auditorías para:
   - Performance
   - Accessibility
   - Best Practices
   - SEO (si aplica a páginas públicas)

Los reportes permiten identificar:

- Problemas de accesibilidad en formularios y tablas.
- Mejoras de rendimiento (peso de recursos, caché, etc.).
- Buenas prácticas de seguridad en el frontend.

Los hallazgos se anotan y se van corrigiendo en iteraciones de mantenimiento.

---

## Mantenimiento y soporte

Algunas prácticas definidas para mantener el sistema:

- **Gestión de logs**
  - Laravel registra errores en `storage/logs/laravel.log`.
  - Se revisan periódicamente para identificar excepciones o consultas problemáticas.

- **Backups**
  - Respaldos periódicos de la base de datos `pas_jm`.
  - Copia de la carpeta `storage/app/public` (evidencias, PDFs, etc.).

- **Migraciones controladas**
  - Toda modificación de estructura de BD se realiza mediante migraciones.
  - Se versionan en el repositorio para asegurar reproducibilidad.

- **Documentación**
  - Este README describe módulos, instalación y flujos principales.
  - La landing en `/docs` resume arquitectura, despliegue, pruebas y monitoreo.

---

## Roadmap

Algunas mejoras previstas / ideas futuras:

- Reportes avanzados por periodo, tipo de infracción y zona.
- Filtros avanzados y exportación a PDF/Excel.
- Permisos más finos (por gerencia / área).
- Notificaciones por correo al administrado.
- Más pruebas automáticas (tests de integración completos por flujo).

---

> Cualquier sugerencia o mejora es bienvenida a través de *issues* o *pull requests* en el repositorio.
