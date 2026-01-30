<?php

use App\Http\Controllers\LibrosController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RegistrarseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\carritoController;
use App\Http\Controllers\BackupController;

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


Route::get('/backup/mongo', [BackupController::class, 'download'])->middleware('auth:usuarios');

Route::get('/carrito', [carritoController::class, 'carrito'])->name('/carrito');
});





Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');