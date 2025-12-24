<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

Route::get('/', function () {
    return view('welcome');
});

// Pacientes resource
Route::resource('pacientes', App\Http\Controllers\PacienteController::class)->middleware('auth');

// Endpoint para camas disponibles por servicio (AJAX)
Route::get('servicios/{servicio}/camas-available', [App\Http\Controllers\PacienteController::class, 'availableCamas'])->middleware('auth');

// Endpoint para verificar existencia de paciente por cédula
Route::get('pacientes/check-cedula', [App\Http\Controllers\PacienteController::class, 'checkCedula'])->middleware('auth');

// Servicios and Camas (admin)
Route::resource('servicios', App\Http\Controllers\ServicioController::class)->middleware('auth');
Route::resource('camas', App\Http\Controllers\CamaController::class)->middleware('auth');
// Dietas CRUD (index/show public; other actions protected in controller)
Route::resource('dietas', App\Http\Controllers\DietaController::class);
// PacienteDietas (simple registro paciente-dieta)
Route::resource('paciente-dietas', App\Http\Controllers\PacienteDietaController::class)->middleware('auth');
// Registro de dietas
Route::resource('registro-dietas', App\Http\Controllers\RegistroDietaController::class)->middleware('auth');
Route::get('registro-dietas/reporte', [App\Http\Controllers\RegistroDietaController::class, 'reporte'])->name('registro-dietas.reporte')->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ruta de usuarios 
Route::middleware(['auth'])->group(function () {
    Route::resource('usuarios', UsuarioController::class);
});

require __DIR__.'/auth.php';
