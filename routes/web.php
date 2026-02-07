<?php

use App\Http\Controllers\LibrosController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RegistrarseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\PasswordResetController;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth:usuarios'])->group(function () {
Route::get('/libros/crear', [LibrosController::class, 'crear'])->name('libros.crear');
Route::post('/libros/store', [LibrosController::class, 'store'])->name('libros.store');
Route::get('/libros/leer', [LibrosController::class, 'leer'])->name('libros.leer');
Route::put('/libros/{libro}', [LibrosController::class, 'update'])->name('libros.update');
Route::get('/libros/eliminar', [LibrosController::class, 'eliminar'])->name('libros.eliminar');
Route::post('/libros/destroy', [LibrosController::class, 'destroy'])->name('libros.destroy');
Route::get('/libros/inicio', [LibrosController::class, 'inicio'])->name('libros.inicio');
Route::get('/libros/consultar', [LibrosController::class, 'consultar'])->name('libros.consultar');
Route::get('/libros/registrarse', [RegistrarseController::class, 'registrarse'])->name('libros.registrarse');
Route::post('/libros/registrarse', [RegistrarseController::class, 'registrar'])->name('libros.registrar');
Route::get('/libros/registrarse', [RegistrarseController::class, 'registrarse'])->name('libros.registrarse');
Route::post('/libros/registrarse', [RegistrarseController::class, 'registrar'])->name('libros.registrar');


Route::get('/productos/crear', [ProductosController::class, 'crear'])->name('productos.crear');
Route::get('/productos/leer', [ProductosController::class, 'leer'])->name('productos.leer');
Route::post('/productos/store', [ProductosController::class, 'store'])->name('productos.store');
Route::put('/productos/{producto}', [ProductosController::class, 'update'])->name('productos.update');
Route::get('/productos/eliminar', [ProductosController::class, 'eliminar'])->name('productos.eliminar');
Route::post('/productos/destroy', [ProductosController::class, 'destroy'])->name('productos.destroy');


// Rutas para backups
    Route::get('/backups', [BackupController::class, 'index'])->name('backups.index');
    Route::get('/backups/create', [BackupController::class, 'createBackupForm'])->name('backups.create');
    Route::post('/backups', [BackupController::class, 'createBackup'])->name('backups.store');
    Route::get('/backups/download/{filename}', [BackupController::class, 'downloadBackup'])->name('backups.download');
    Route::delete('/backups/{filename}', [BackupController::class, 'deleteBackup'])->name('backups.delete');
    Route::get('/backups/restore', [BackupController::class, 'restoreBackupForm'])->name('backups.restore.form');
    Route::get('/backups/restore/{filename}', [BackupController::class, 'restoreBackupForm'])->name('backups.restore.form.withfile');
    Route::post('/backups/restore', [BackupController::class, 'restoreBackup'])->name('backups.restore');
    
Route::get('/carrito', [CarritoController::class, 'carrito'])->name('/carrito');
// routes/web.php
Route::post('/carrito/agregar/{id}', [CarritoController::class, 'agregar'])->name('carrito.agregar');

Route::get('/carrito', [CarritoController::class, 'ver'])->name('carrito.ver');
Route::delete('/carrito/eliminar/{id}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
Route::get('/pago', [CarritoController::class, 'mostrarPago'])->name('carrito.mostrar-pago');
Route::post('/procesar-pago', [CarritoController::class, 'procesarPago'])->name('carrito.procesar-pago');
Route::get('/pago-exito', [CarritoController::class, 'pagoExito'])->name('carrito.pago-exito');
Route::get('/descargar-ticket', [CarritoController::class, 'descargarTicket'])->name('carrito.descargar-ticket');    
   
 
    });



// Rutas para recuperación de contraseña
Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.forgot');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [PasswordResetController::class, 'resetPassword'])->name('password.update');
Route::get('/password/show-link/{token}', [PasswordResetController::class, 'showGeneratedLink'])->name('password.show.link');

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');