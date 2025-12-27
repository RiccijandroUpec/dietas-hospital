@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">🏥 Hoja de Diálisis del Día</h1>
                <p class="text-gray-600 mt-1">Resumen completo de todas las comidas para pacientes en diálisis</p>
            </div>

            <script>
            function printDialisis() {
                const originalContent = document.body.innerHTML;
                const sheetContent = document.getElementById('dialisis-sheet').innerHTML;
    
                // Crear fecha y hora actual
                const ahora = new Date();
                const fechaHora = ahora.toLocaleString('es-ES', { 
                    year: 'numeric', 
                    month: '2-digit', 
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit'
                });
    
                document.body.innerHTML = `
                    <div id="dialisis-sheet">
                        <div style="text-align: right; font-size: 0.85rem; color: #6b7280; margin-bottom: 1rem;">
                            Impreso: ${fechaHora}
                        </div>
                        ${sheetContent}
                        <div style="margin-top: 2rem; padding-top: 1rem; border-top: 2px solid #e5e7eb; text-align: center; font-size: 0.85rem; color: #9ca3af;">
                            Sistema de Gestión de Dietas Hospitalarias
                        </div>
                    </div>
                `;
    
                window.print();
    
                document.body.innerHTML = originalContent;
                window.location.reload();
            }
            </script>

            <div class="flex gap-2">
                @if(auth()->user()->role !== 'usuario')
                    <button onclick="printDialisis()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">🖨️ Imprimir</button>
                @endif
                <a href="{{ route('registro-dietas.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg">Volver al Dashboard</a>
            </div>
        </div>

        <!-- Filtro de Fecha -->
        <form method="GET" action="{{ route('registro-dietas.dialisis') }}" class="mb-6 p-4 bg-white rounded-lg shadow-md">
            <div class="flex items-end gap-4">
                <div class="flex-1">
                    <label class="text-sm text-gray-600">Fecha</label>
                    <input type="date" name="fecha" value="{{ $fecha }}" class="mt-1 w-full border rounded-lg px-3 py-2" />
                </div>
                <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">Filtrar</button>
            </div>
        </form>

        <!-- Resumen -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-700">Registros</p>
                <p class="text-3xl font-bold text-blue-900">{{ $totales['registros'] }}</p>
            </div>
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                <p class="text-sm text-emerald-700">Pacientes</p>
                <p class="text-3xl font-bold text-emerald-900">{{ $totales['pacientes_unicos'] }}</p>
            </div>
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <p class="text-sm text-amber-700">Desayunos</p>
                <p class="text-3xl font-bold text-amber-900">{{ $totales['desayuno'] }}</p>
            </div>
            <div class="bg-pink-50 border border-pink-200 rounded-lg p-4">
                <p class="text-sm text-pink-700">Almuerzos</p>
                <p class="text-3xl font-bold text-pink-900">{{ $totales['almuerzo'] }}</p>
            </div>
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                <p class="text-sm text-purple-700">Meriendas</p>
                <p class="text-3xl font-bold text-purple-900">{{ $totales['merienda'] }}</p>
            </div>
        </div>

        <!-- Hoja de Preparación de Diálisis -->
        <div id="dialisis-sheet" class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="mb-4">
                <h2 class="text-2xl font-semibold text-gray-800">🧾 Hoja de preparación - Diálisis</h2>
                <p class="text-sm text-gray-600 mt-1">Todas las comidas del día</p>
                <div class="mt-2 text-xs text-gray-500">
                    <span class="inline-block mr-3"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($fecha)->locale('es')->translatedFormat('l, d \d\e F \d\e Y') }}</span>
                    <span class="inline-block text-gray-400">(NPO excluido)</span>
                </div>
            </div>

            @php
                $tipoLabels = ['desayuno' => 'Desayuno', 'almuerzo' => 'Almuerzo', 'merienda' => 'Merienda'];
            @endphp

            @forelse($porTipoComida as $tipoComida => $regs)
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b-2 border-gray-300">
                        {{ $tipoLabels[$tipoComida] ?? ucfirst($tipoComida) }}
                    </h3>

                    @php
                        // Construir items y excluir NPO
                        $items = collect($regs)->flatMap(function($r) {
                            return collect($r->dietas)->map(function($d) use ($r) {
                                $nombre = strtolower($d->nombre ?? '');
                                $esNpo = str_contains($nombre, 'npo') || str_contains($nombre, 'n.p.o') || str_contains($nombre, 'nada por via oral') || str_contains($nombre, 'nada por vía oral');
                                return [
                                    'dieta' => $d->nombre,
                                    'vajilla' => $r->vajilla,
                                    'observaciones' => $r->observaciones,
                                    'es_tardia' => $r->es_tardia,
                                    'npo' => $esNpo,
                                ];
                            });
                        })->filter(fn($i) => !$i['npo']);

                        // Agrupar por dieta
                        $resumen = $items->groupBy('dieta')->map(function($grupo) {
                            // Observaciones por tipo de vajilla
                            $obsNormal = $grupo->where('vajilla', 'normal')->pluck('observaciones')->filter()->map(fn($t) => trim($t))->filter()->countBy();
                            $obsDesc = $grupo->where('vajilla', 'descartable')->pluck('observaciones')->filter()->map(fn($t) => trim($t))->filter()->countBy();
                            
                            $obsNormalList = $obsNormal->map(function($cnt, $txt) { return ['txt' => $txt, 'cnt' => $cnt]; })->values();
                            $obsDescList = $obsDesc->map(function($cnt, $txt) { return ['txt' => $txt, 'cnt' => $cnt]; })->values();
                            
                            return [
                                'total' => $grupo->count(),
                                'normal' => $grupo->where('vajilla', 'normal')->count(),
                                'descartable' => $grupo->where('vajilla', 'descartable')->count(),
                                'tardias' => $grupo->filter(fn($i) => $i['es_tardia'])->count(),
                                'obs_normal' => $obsNormalList,
                                'obs_descartable' => $obsDescList,
                            ];
                        })->sortByDesc('total');
                    @endphp

                    @if($resumen->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Dieta</th>
                                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase">Total</th>
                                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase"><span class="text-xl">🍽️</span> Normal</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Obs. Normal</th>
                                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase"><span class="text-xl">📦</span> Descartable</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase">Obs. Descartable</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($resumen as $nombre => $info)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-3 text-base font-medium text-gray-900">{{ $nombre }}</td>
                                        <td class="px-6 py-3 text-center text-base font-semibold text-gray-900">{{ $info['total'] }}</td>
                                        <td class="px-6 py-3 text-center text-base text-gray-800">{{ $info['normal'] }}</td>
                                        <td class="px-6 py-3 text-sm text-gray-700 max-w-sm">
                                            @if(!empty($info['obs_normal']) && count($info['obs_normal']) > 0)
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($info['obs_normal'] as $obs)
                                                        <span class="inline-flex items-center rounded-full px-2 py-1 bg-blue-100 text-blue-800 text-sm">{{ $obs['txt'] }} <span class="ml-1 bg-white/70 text-gray-700 rounded px-1">{{ $obs['cnt'] }}</span></span>
                                                    @endforeach
                                                </div>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="px-6 py-3 text-center text-base text-gray-800">{{ $info['descartable'] }}</td>
                                        <td class="px-6 py-3 text-sm text-gray-700 max-w-sm">
                                            @if(!empty($info['obs_descartable']) && count($info['obs_descartable']) > 0)
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($info['obs_descartable'] as $obs)
                                                        <span class="inline-flex items-center rounded-full px-2 py-1 bg-amber-100 text-amber-800 text-sm">{{ $obs['txt'] }} <span class="ml-1 bg-white/70 text-gray-700 rounded px-1">{{ $obs['cnt'] }}</span></span>
                                                    @endforeach
                                                </div>
                                            @else
                                                —
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @php
                            $total = $resumen->reduce(fn($c, $i) => $c + ($i['total'] ?? 0), 0);
                            $normal = $resumen->reduce(fn($c, $i) => $c + ($i['normal'] ?? 0), 0);
                            $desc = $resumen->reduce(fn($c, $i) => $c + ($i['descartable'] ?? 0), 0);
                        @endphp
                        <div class="text-right text-base text-gray-800 mt-2 font-semibold">
                            Total {{ $tipoLabels[$tipoComida] ?? $tipoComida }}: {{ $total }}
                            <span class="ml-3 text-sm text-gray-600">(🍽️ {{ $normal }} · 📦 {{ $desc }})</span>
                        </div>
                    @else
                        <p class="text-gray-600 py-4">No hay dietas para este tipo de comida.</p>
                    @endif
                </div>
            @empty
                <div class="text-center py-12 text-gray-600">
                    <div class="text-5xl mb-4">🏥</div>
                    <p class="text-lg">No hay registros de diálisis para la fecha seleccionada.</p>
                </div>
            @endforelse
        </div>

        <!-- Tabla Detallada de Registros -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">📋 Registros Detallados</h2>
            
            @if($registros->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Fecha</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tipo Comida</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Paciente</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Cama</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Dietas</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Vajilla</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Observaciones</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($registros as $registro)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($registro->fecha)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($registro->tipo_comida == 'desayuno')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                🌅 Desayuno
                                            </span>
                                        @elseif($registro->tipo_comida == 'almuerzo')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                                🍽️ Almuerzo
                                            </span>
                                        @elseif($registro->tipo_comida == 'merienda')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                🌙 Merienda
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                        {{ $registro->paciente->nombre }}
                                        @if($registro->es_tardia)
                                            <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                🔴 TARDÍA
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ $registro->paciente->cama->numero ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($registro->dietas as $dieta)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                                    {{ $dieta->nombre }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($registro->vajilla == 'normal')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                🍽️ Normal
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                📦 Descartable
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 max-w-xs">
                                        {{ $registro->observaciones ?: '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('registro-dietas.show', $registro->id) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">
                                            👁️ Ver
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 text-sm text-gray-600">
                    Total de registros: <span class="font-semibold text-gray-900">{{ $registros->count() }}</span>
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <p>No hay registros para mostrar en la fecha seleccionada.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Estilos para pantalla */
#dialisis-sheet h2 { font-size: 1.75rem; }
#dialisis-sheet h3 { font-size: 1.5rem; }
#dialisis-sheet table th, #dialisis-sheet table td { font-size: 1.05rem; }

@media print {
    body * {
        visibility: hidden;
    }
    
    #dialisis-sheet, #dialisis-sheet * {
        visibility: visible;
    }
    
    #dialisis-sheet {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 30px;
        box-shadow: none !important;
        border: none !important;
        background: white !important;
    }
    
    /* Encabezados y títulos */
    #dialisis-sheet h2 {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.75rem;
        color: #1f2937;
        border-bottom: 3px solid #2563eb;
        padding-bottom: 0.5rem;
    }
    
    #dialisis-sheet h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-top: 2rem;
        margin-bottom: 0.75rem;
        color: #374151;
        page-break-after: avoid;
    }
    
    #dialisis-sheet p {
        font-size: 0.95rem;
        color: #4b5563;
        margin-bottom: 0.5rem;
    }
    
    /* Tablas */
    #dialisis-sheet table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
        margin-bottom: 1.5rem;
        font-size: 1.05rem;
        page-break-inside: auto;
    }
    
    #dialisis-sheet thead {
        background: linear-gradient(to right, #eff6ff, #dbeafe) !important;
        border-bottom: 2px solid #2563eb;
    }
    
    #dialisis-sheet th {
        font-size: 0.95rem;
        padding: 14px 10px;
        text-align: left;
        font-weight: 700;
        color: #1e40af;
        border: 1px solid #93c5fd;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    #dialisis-sheet td {
        padding: 10px;
        border: 1px solid #cbd5e1;
        color: #1f2937;
        vertical-align: top;
        font-size: 0.95rem;
    }
    
    #dialisis-sheet tbody tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
    
    #dialisis-sheet tbody tr:nth-child(even) {
        background-color: #f9fafb !important;
    }
    
    /* Badges de dietas */
    #dialisis-sheet .bg-rose-100,
    #dialisis-sheet .bg-sky-100,
    #dialisis-sheet .bg-green-100,
    #dialisis-sheet .bg-amber-100,
    #dialisis-sheet .bg-cyan-100,
    #dialisis-sheet .bg-emerald-100,
    #dialisis-sheet .bg-indigo-100,
    #dialisis-sheet .bg-gray-200,
    #dialisis-sheet .bg-purple-100 {
        background-color: #e0e7ff !important;
        color: #3730a3 !important;
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-block;
        border: 1px solid #c7d2fe;
        margin: 2px;
    }
    
    /* Totales */
    #dialisis-sheet .text-right {
        text-align: right;
        font-weight: 700;
        font-size: 1.1rem;
        margin-top: 0.75rem;
        padding-top: 0.5rem;
        border-top: 2px solid #cbd5e1;
        color: #1f2937;
    }
    
    /* Ocultar elementos innecesarios */
    button, form, a, .shadow-md,
    .bg-blue-50, .bg-emerald-50, .bg-amber-50, 
    .bg-pink-50, .bg-purple-50 {
        display: none !important;
    }
    
    /* Forzar impresión en color */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }
}

@page {
    size: letter;
    margin: 1.5cm;
}
</style>
@endsection
