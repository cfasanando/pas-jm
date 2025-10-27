<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\{
  HomeController, ActaController, EvidenciaController, MapaController,
  DashboardController, ExpedienteController, BoletaController, AdminController
};

/*
|--------------------------------------------------------------------------
| Pública
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class,'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Privada (auth)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function(){

  // Dashboard y módulos principales
  Route::get('/dashboard',   [DashboardController::class,'index'])->name('dashboard');
  Route::get('/mapa',        [MapaController::class,'index'])->name('mapa');
  Route::get('/mapa/data',   [MapaController::class,'data'])->name('mapa.data');
  Route::get('/expedientes', [ExpedienteController::class,'index'])->name('expedientes');

  /*
  |---------------------------
  | ACTAS
  |---------------------------
  */
  Route::get ('/actas',            [ActaController::class, 'index'])->name('actas.index');
  Route::get ('/actas/crear',      [ActaController::class, 'create'])->name('actas.create'); // <-- antes que {acta}
  Route::post('/actas',            [ActaController::class, 'store'])->name('actas.store');
  Route::put('/actas/{acta}/ubicacion', [ActaController::class, 'updateUbicacion'])->name('actas.ubicacion');


  // evidencias del acta
  Route::get   ('/actas/{acta}/evidencias', [EvidenciaController::class,'index'])->name('evidencias.index')->whereNumber('acta');
  Route::post  ('/actas/{acta}/evidencias', [EvidenciaController::class,'store'])->name('evidencias.store')->whereNumber('acta');
  Route::delete('/evidencias/{ev}',         [EvidenciaController::class,'destroy'])->name('evidencias.destroy')->whereNumber('ev');

  // tipificaciones del acta
  Route::post  ('/actas/{acta}/tipificaciones', [ActaController::class,'addTipificacion'])->name('tipificaciones.store')->whereNumber('acta');
  Route::delete('/tipificaciones/{tip}',        [ActaController::class,'removeTipificacion'])->name('tipificaciones.destroy')->whereNumber('tip');

  // acciones sobre el acta
  Route::post('/actas/{acta}/emitir', [ActaController::class,'emit'])->name('actas.emit')->whereNumber('acta');
  Route::post('/actas/{acta}/anular', [ActaController::class,'cancel'])->name('actas.cancel')->whereNumber('acta');

  // mostrar un acta específico (¡después de /crear!)
  Route::get('/actas/{acta}', [ActaController::class, 'show'])->name('actas.show')->whereNumber('acta');

  /*
  |---------------------------
  | BOLETAS
  |---------------------------
  */
  Route::get ('/boletas',              [BoletaController::class,'index'])->name('boletas');
  Route::get ('/boletas/{boleta}',     [BoletaController::class,'show'])->name('boletas.show')->whereNumber('boleta');
  Route::get ('/boletas/{boleta}/pdf', [BoletaController::class,'pdf'])->name('boletas.pdf')->whereNumber('boleta');
  Route::post('/boletas/{boleta}/notify', [BoletaController::class,'notify'])->name('boletas.notify')->whereNumber('boleta');

  // crear boleta desde acta
  Route::post('/actas/{acta}/boleta',  [BoletaController::class,'createFromActa'])->name('boletas.fromActa')->whereNumber('acta');

  /*
  |---------------------------
  | ADMIN
  |---------------------------
  */
  Route::get('/admin', [AdminController::class,'index'])->name('admin');

  // Users
  Route::post  ('/admin/users',        [AdminController::class,'usersStore'])->name('admin.users.store');
  Route::put   ('/admin/users/{user}', [AdminController::class,'usersUpdate'])->name('admin.users.update')->whereNumber('user');
  Route::delete('/admin/users/{user}', [AdminController::class,'usersDestroy'])->name('admin.users.destroy')->whereNumber('user');

  // Catálogos (infracciones / series)
  Route::post  ('/admin/catalogs',      [AdminController::class,'catalogsStore'])->name('admin.catalogs.store');
  Route::put   ('/admin/catalogs/{id}', [AdminController::class,'catalogsUpdate'])->name('admin.catalogs.update');
  Route::delete('/admin/catalogs/{id}', [AdminController::class,'catalogsDestroy'])->name('admin.catalogs.destroy');

  // Settings
  Route::put('/admin/settings', [AdminController::class,'settingsUpdate'])->name('admin.settings.update');
});

// auth scaffolding (Breeze/Fortify/etc.)
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Logout GET helper (opcional)
|--------------------------------------------------------------------------
*/
Route::get('/logout', function (Request $request) {
    if (Auth::check()) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
    return redirect()->route('login');
})->name('logout.get');

/*
|--------------------------------------------------------------------------
| Fallback
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login');
});
