<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;

Route::get('/', function () {
    return view('welcome');
});

// Pacientes resource y endpoints AJAX (orden: primero AJAX, luego resource)
Route::middleware('auth')->group(function () {
    // Endpoint para verificar existencia de paciente por cédula
    Route::get('pacientes/check-cedula', [App\Http\Controllers\PacienteController::class, 'checkCedula'])
        ->name('pacientes.check-cedula');

    // Endpoint para camas disponibles por servicio (AJAX)
    Route::get('servicios/{servicio}/camas-available', [App\Http\Controllers\PacienteController::class, 'availableCamas']);

    Route::get('pacientes/reporte', [App\Http\Controllers\PacienteController::class, 'reporte'])->name('pacientes.reporte');
    Route::get('pacientes/estadisticas', [App\Http\Controllers\PacienteController::class, 'estadisticas'])->name('pacientes.estadisticas');
    Route::get('pacientes/search', [App\Http\Controllers\PacienteController::class, 'search'])->name('pacientes.search');
    Route::get('pacientes/exportar', [App\Http\Controllers\PacienteController::class, 'exportar'])->name('pacientes.exportar');
    Route::resource('pacientes', App\Http\Controllers\PacienteController::class);

    // Vista del programador
    Route::view('programador', 'developer')->name('developer');
    Route::post('programador/export-db', function () {
        $user = auth()->user();
        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

        $config = config('database.connections.mysql');
        $host = $config['host'] ?? '127.0.0.1';
        $userDb = $config['username'] ?? '';
        $password = $config['password'] ?? '';
        $database = $config['database'] ?? '';

        if (!$database || !$userDb) {
            return back()->with('error', 'Configuración de base de datos incompleta.');
        }

        $mysqldump = env('MYSQLDUMP_PATH', 'mysqldump');

        // Verificar disponibilidad de mysqldump antes de ejecutar el backup
        $check = new Process([$mysqldump, '--version']);
        $check->run();
        if (!$check->isSuccessful()) {
            return back()->with('error', 'mysqldump no está disponible en el servidor. Define MYSQLDUMP_PATH en .env o agrega el binario al PATH.');
        }

        $backupDir = storage_path('app/backups');
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $filename = 'backup_'.now()->format('Ymd_His').'.sql';
        $filePath = $backupDir.DIRECTORY_SEPARATOR.$filename;

        $command = [
            $mysqldump,
            '-h', $host,
            '-u', $userDb,
        ];

        if ($password !== '') {
            $command[] = '-p'.$password;
        }

        $command[] = $database;

        $process = new Process($command);
        $process->setTimeout(120);
        $process->run();

        if (!$process->isSuccessful()) {
            return back()->with('error', 'No se pudo exportar la base: '.$process->getErrorOutput());
        }

        file_put_contents($filePath, $process->getOutput());

        return response()->download($filePath, $filename)->deleteFileAfterSend(true);
    })->name('programador.export-db');
});

// Servicios and Camas (admin)
Route::resource('servicios', App\Http\Controllers\ServicioController::class)->middleware('auth');
Route::resource('camas', App\Http\Controllers\CamaController::class)->middleware('auth');

// Vista gráfica de camas
Route::get('camas-grafica', [App\Http\Controllers\CamasGraficaController::class, 'index'])->middleware('auth')->name('camas-grafica.index');
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
    Route::get('registro-refrigerios/estadisticas', [App\Http\Controllers\RegistroRefrigerioController::class, 'estadisticas'])->name('registro-refrigerios.estadisticas');
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
    Route::get('usuarios/search', [UsuarioController::class, 'search'])->name('usuarios.search');
    Route::resource('usuarios', UsuarioController::class);
});

// Auditoría (solo administradores)
Route::middleware(['auth'])->group(function () {
    Route::get('audits', [App\Http\Controllers\AuditController::class, 'index'])->name('audits.index');
    Route::get('audits/{audit}', [App\Http\Controllers\AuditController::class, 'show'])->name('audits.show');
    // Redirección para acceder con /audit (singular)
    Route::redirect('audit', 'audits');
});

// Configuración de horarios (solo administradores)
Route::middleware(['auth'])->group(function () {
    Route::get('schedule-config', [App\Http\Controllers\ScheduleConfigController::class, 'index'])->name('schedule-config.index');
    Route::get('schedule-config/edit', [App\Http\Controllers\ScheduleConfigController::class, 'edit'])->name('schedule-config.edit');
    Route::put('schedule-config', [App\Http\Controllers\ScheduleConfigController::class, 'update'])->name('schedule-config.update');
    Route::post('schedule-config/toggle-out-of-schedule', [App\Http\Controllers\ScheduleConfigController::class, 'toggleOutOfSchedule'])->name('schedule-config.toggle-out-of-schedule');
});

require __DIR__.'/auth.php';

// Operación administrativa: eliminar todas las camas en el entorno actual
Route::get('/ops/camas/delete-all', function () {
    $user = auth()->user();
    if (!$user || $user->role !== 'admin') {
        abort(403);
    }
    Artisan::call('camas:delete-all', ['--force' => true]);
    return response()->json([
        'status' => 'ok',
        'message' => 'Todas las camas eliminadas (acción en cascada aplicada)'
    ]);
})->middleware('auth');
