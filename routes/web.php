<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstadoController;

Route::get('/', fn() => redirect()->route('estados.index'));

Route::get('/estados', [EstadoController::class, 'index'])->name('estados.index');
Route::post('/estados/sync', [EstadoController::class, 'sync'])->name('estados.sync');
Route::get('/estados/{estado}/municipios', [EstadoController::class, 'municipios'])->name('estados.municipios');
