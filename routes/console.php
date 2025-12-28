<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Cama;

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
