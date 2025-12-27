<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

Route::get('/', function () {
    return view('welcome');
});

// Pacientes resource
Route::middleware('auth')->group(function () {
    Route::get('pacientes/reporte', [App\Http\Controllers\PacienteController::class, 'reporte'])->name('pacientes.reporte');
    Route::get('pacientes/search', [App\Http\Controllers\PacienteController::class, 'search'])->name('pacientes.search');
    Route::resource('pacientes', App\Http\Controllers\PacienteController::class);
});

// Endpoint para camas disponibles por servicio (AJAX)
Route::get('servicios/{servicio}/camas-available', [App\Http\Controllers\PacienteController::class, 'availableCamas'])->middleware('auth');

// Endpoint para verificar existencia de paciente por cédula
Route::get('pacientes/check-cedula', [App\Http\Controllers\PacienteController::class, 'checkCedula'])->middleware('auth');

// Servicios and Camas (admin)
Route::resource('servicios', App\Http\Controllers\ServicioController::class)->middleware('auth');
Route::resource('camas', App\Http\Controllers\CamaController::class)->middleware('auth');
// Dietas live search (public) - MUST be before resource to match correctly
Route::get('dietas/search', [App\Http\Controllers\DietaController::class, 'search'])->name('dietas.search');
// Dietas CRUD (index/show public; other actions protected in controller)
Route::resource('dietas', App\Http\Controllers\DietaController::class);

// Tipos y Subtipos de Dieta
Route::middleware('auth')->group(function () {
    Route::resource('tipos-dieta', App\Http\Controllers\TipoDietaController::class)->parameters(['tipos-dieta' => 'tipo_dieta']);
    Route::resource('subtipos-dieta', App\Http\Controllers\SubtipoDietaController::class)->parameters(['subtipos-dieta' => 'subtipo_dieta']);
});

// Refrigerios CRUD
Route::middleware('auth')->group(function () {
    Route::resource('refrigerios', App\Http\Controllers\RefrigerioController::class);
});

// PacienteDietas (simple registro paciente-dieta)
Route::resource('paciente-dietas', App\Http\Controllers\PacienteDietaController::class)->middleware('auth');
// Registro de dietas
Route::middleware('auth')->group(function () {
    Route::get('registro-dietas/reporte', [App\Http\Controllers\RegistroDietaController::class, 'reporte'])->name('registro-dietas.reporte');
    Route::get('registro-dietas/dashboard', [App\Http\Controllers\RegistroDietaController::class, 'dashboard'])->name('registro-dietas.dashboard');
    Route::get('registro-dietas/dialisis', [App\Http\Controllers\RegistroDietaController::class, 'dialisis'])->name('registro-dietas.dialisis');
    Route::resource('registro-dietas', App\Http\Controllers\RegistroDietaController::class);
});

// Registro de refrigerios
Route::middleware('auth')->group(function () {
    Route::get('registro-refrigerios/dashboard', [App\Http\Controllers\RegistroRefrigerioController::class, 'dashboard'])->name('registro-refrigerios.dashboard');
    Route::get('registro-refrigerios/reporte', [App\Http\Controllers\RegistroRefrigerioController::class, 'reporte'])->name('registro-refrigerios.reporte');
    Route::resource('registro-refrigerios', App\Http\Controllers\RegistroRefrigerioController::class);
});

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
