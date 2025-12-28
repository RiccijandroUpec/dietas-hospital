<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Cama;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('camas:normalize-codigos', function () {
    $updated = 0;
    $skipped = 0;

    $camas = Cama::all();
    foreach ($camas as $cama) {
        $codigo = $cama->codigo;
        $pos = strrpos($codigo, '-');
        if ($pos === false) {
            $skipped++;
            continue;
        }

        $prefix = substr($codigo, 0, $pos);
        $suffix = substr($codigo, $pos + 1);

        if ($suffix === '' || !ctype_digit($suffix)) {
            $skipped++;
            continue;
        }

        $numero = (int) $suffix; // quita ceros a la izquierda
        $nuevo = $prefix . '-' . $numero;

        if ($nuevo === $codigo) {
            $skipped++;
            continue;
        }

        // Evitar colisiones: si existe, incrementar hasta que sea único
        $probe = $nuevo;
        $n = $numero;
        while (Cama::where('codigo', $probe)->where('id', '!=', $cama->id)->exists()) {
            $n++;
            $probe = $prefix . '-' . $n;
        }

        $cama->codigo = $probe;
        $cama->save();
        $updated++;
    }

    $this->info("Camas actualizadas: {$updated}; omitidas: {$skipped}");
})->purpose('Normaliza códigos de camas removiendo ceros a la izquierda');

Artisan::command('camas:delete-all {--force}', function () {
    $this->warn('Advertencia: Esta acción eliminará TODAS las camas y cualquier registro relacionado en cascada (por FK).');
    $this->warn('Esto afectará el entorno actual y no es reversible.');

    if (!$this->option('force')) {
        if (!$this->confirm('¿Deseas continuar?')) {
            $this->info('Operación cancelada.');
            return;
        }
    }

    $total = Cama::count();
    DB::transaction(function () {
        // Usamos delete() para evitar problemas de TRUNCATE con FKs
        Cama::query()->delete();
    });

    $this->info("Camas eliminadas: {$total}");
})->purpose('Elimina todas las camas (y dependencias en cascada)');

Artisan::command('users:fix-password-hashes', function () {
    $this->info('Verificando y reparando contraseñas con hash inválido...');

    $fixed = 0;
    $users = User::all();

    foreach ($users as $user) {
        // Intenta verificar que el hash sea válido
        try {
            // Si el hash no comienza con $2, $2y, $2a, $2b, no es un hash Bcrypt válido
            if (!preg_match('/^\$2[aby]\$/', $user->password)) {
                $this->warn("Usuario {$user->id} ({$user->email}): contraseña sin hashear o formato inválido");
                
                // Asumimos que la contraseña es 'password' si está en texto plano
                // (alternativa: pedir al usuario que resetee su contraseña)
                $user->password = Hash::make('password');
                $user->save();
                $fixed++;
                $this->info("  → Reparado: contraseña reseteada a 'password'");
            }
        } catch (\Exception $e) {
            $this->error("Error al procesar usuario {$user->id}: {$e->getMessage()}");
        }
    }

    $this->info("Total de contraseñas reparadas: {$fixed}");
})->purpose('Repara hashes de contraseñas inválidas en la tabla users');
