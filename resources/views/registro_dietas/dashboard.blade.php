@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">📊 Dashboard de Comidas del Día</h1>
                <p class="text-gray-600 mt-1">Selecciona el tipo de comida y visualiza todo detallado y contabilizado</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('registro-dietas.dialisis') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">🏥 Diálisis</a>
                <a href="{{ route('registro-dietas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg">Volver a Registros</a>
            </div>
        </div>

        <!-- Filtros -->
        <form method="GET" action="{{ route('registro-dietas.dashboard') }}" class="mb-6 p-4 bg-white rounded-lg shadow-md">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="text-sm text-gray-600">Fecha</label>
                    <input type="date" name="fecha" value="{{ $fecha }}" class="mt-1 w-full border rounded-lg px-3 py-2" />
                </div>
                <div>
                    <label class="text-sm text-gray-600">Tipo de comida</label>
                    <select name="tipo_comida" class="mt-1 w-full border rounded-lg px-3 py-2">
                        @foreach(['desayuno' => 'Desayuno', 'almuerzo' => 'Almuerzo', 'merienda' => 'Merienda'] as $key => $label)
                            <option value="{{ $key }}" @selected($tipo === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Servicio (opcional)</label>
                    <select name="servicio_id" class="mt-1 w-full border rounded-lg px-3 py-2">
                        <option value="">Todos</option>
                        @foreach($servicios as $s)
                            <option value="{{ $s->id }}" @selected(request('servicio_id')==$s->id)>{{ $s->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">Filtrar</button>
                </div>
            </div>
        </form>

        <!-- Resumen -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-700">Registros</p>
                <p class="text-3xl font-bold text-blue-900">{{ $totales['registros'] }}</p>
            </div>
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                <p class="text-sm text-emerald-700">Pacientes únicos</p>
                <p class="text-3xl font-bold text-emerald-900">{{ $totales['pacientes_unicos'] }}</p>
            </div>
            <div class="bg-pink-50 border border-pink-200 rounded-lg p-4">
                <p class="text-sm text-pink-700">Dietas totales</p>
                <p class="text-3xl font-bold text-pink-900">{{ $totales['dietas_total'] }}</p>
            </div>
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <p class="text-sm text-amber-700">Servicios</p>
                <p class="text-3xl font-bold text-amber-900">{{ $totales['servicios']->sum() }}</p>
            </div>
        </div>

        <!-- Vajilla -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-gray-50 border border-gray-300 rounded-lg p-4">
                <p class="text-sm text-gray-700">🍽️ Vajilla Normal</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totales['vajilla_normal'] }}</p>
            </div>
            <div class="bg-slate-50 border border-slate-300 rounded-lg p-4">
                <p class="text-sm text-slate-700">📦 Descartable</p>
                <p class="text-3xl font-bold text-slate-900">{{ $totales['vajilla_descartable'] }}</p>
            </div>
        </div>

        @php
                $servSel = $servicios->firstWhere('id', request('servicio_id'));
                $tipoLabels = ['desayuno' => 'Desayuno', 'almuerzo' => 'Almuerzo', 'merienda' => 'Merienda'];
        @endphp

        <!-- Hoja de Preparación General -->
        <div id="prep-sheet-general" class="bg-white rounded-lg shadow-md p-6 mb-6 prep-sheet">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">🧾 Hoja de preparación general</h2>
                    <p class="text-sm text-gray-600">Resumen global de todas las dietas a preparar</p>
                    <div class="mt-2 text-xs text-gray-500">
                        <span class="inline-block mr-3"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($fecha)->locale('es')->translatedFormat('l, d \d\e F \d\e Y') }}</span>
                        <span class="inline-block mr-3"><strong>Comida:</strong> {{ $tipoLabels[$tipo] ?? $tipo }}</span>
                        <span class="inline-block"><strong>Servicio:</strong> {{ optional($servSel)->nombre ?? 'Todos' }}</span>
                        <span class="inline-block ml-3 text-gray-400">(NPO excluido)</span>
                    </div>
                </div>
                @if(auth()->user()->role !== 'usuario')
                    <button onclick="printSheet('prep-sheet-general')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg">🖨️ Imprimir General</button>
                @endif
            </div>

            @php
                // Construir items (dieta + vajilla) y excluir NPO
                $prepItems = collect($registros)->flatMap(function($r) {
                    return collect($r->dietas)->map(function($d) use ($r) {
                        $nombre = strtolower($d->nombre ?? '');
                        $esNpo = str_contains($nombre, 'npo') || str_contains($nombre, 'n.p.o') || str_contains($nombre, 'nada por via oral') || str_contains($nombre, 'nada por vía oral');
                        return [
                            'dieta' => $d->nombre,
                            'tipo' => optional($d->tipo)->nombre,
                            'subtipos' => $d->subtipos->pluck('nombre')->join(', '),
                            'vajilla' => $r->vajilla,
                            'observaciones' => $r->observaciones,
                            'es_tardia' => $r->es_tardia,
                            'npo' => $esNpo,
                        ];
                    });
                })->filter(fn($i) => !$i['npo']);

                // Agrupar por dieta y contar total/por vajilla
                $prepResumen = $prepItems->groupBy('dieta')->map(function($grupo) {
                    $first = $grupo->first();
                    
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

            @if($prepResumen->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Dieta</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Total</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase"><span class="text-xl">🍽️</span> Normal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Obs. Normal</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase"><span class="text-xl">📦</span> Descartable</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Obs. Descartable</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($prepResumen as $nombre => $info)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $nombre }}</td>
                                    <td class="px-6 py-3 text-center text-sm font-semibold text-gray-900">{{ $info['total'] }}</td>
                                    <td class="px-6 py-3 text-center text-sm text-gray-800">{{ $info['normal'] }}</td>
                                    <td class="px-6 py-3 text-xs text-gray-700 max-w-sm">
                                        @if(!empty($info['obs_normal']) && count($info['obs_normal']) > 0)
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($info['obs_normal'] as $obs)
                                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 bg-blue-100 text-blue-800 text-sm obs-badge">{{ $obs['txt'] }} <span class="ml-1 bg-white/70 text-gray-700 rounded px-1">{{ $obs['cnt'] }}</span></span>
                                                @endforeach
                                            </div>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-center text-sm text-gray-800">{{ $info['descartable'] }}</td>
                                    <td class="px-6 py-3 text-xs text-gray-700 max-w-sm">
                                        @if(!empty($info['obs_descartable']) && count($info['obs_descartable']) > 0)
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($info['obs_descartable'] as $obs)
                                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 bg-amber-100 text-amber-800 text-sm obs-badge">{{ $obs['txt'] }} <span class="ml-1 bg-white/70 text-gray-700 rounded px-1">{{ $obs['cnt'] }}</span></span>
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
                        $prepTotal = $prepResumen->reduce(fn($carry, $info) => $carry + ($info['total'] ?? 0), 0);
                        $prepNormal = $prepResumen->reduce(fn($c, $i) => $c + ($i['normal'] ?? 0), 0);
                        $prepDesc = $prepResumen->reduce(fn($c, $i) => $c + ($i['descartable'] ?? 0), 0);
                    @endphp
                    <div class="text-right text-sm text-gray-800 mt-2">
                        Total dietas a preparar: <span class="font-semibold">{{ $prepTotal }}</span>
                        <span class="ml-3 text-xs text-gray-600">(🍽️ {{ $prepNormal }} · 📦 {{ $prepDesc }})</span>
                    </div>
                </div>
            @else
                <div class="text-gray-600">No hay dietas para preparar en los filtros seleccionados.</div>
            @endif
        </div>

        <!-- Hoja de Preparación por Servicios -->
        <div id="prep-sheet-services" class="bg-white rounded-lg shadow-md p-6 mb-6 prep-sheet">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">🏥 Hoja de preparación por servicios</h2>
                    <p class="text-sm text-gray-600">Desglose de dietas organizadas por área/piso</p>
                    <div class="mt-2 text-xs text-gray-500">
                        <span class="inline-block mr-3"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($fecha)->locale('es')->translatedFormat('l, d \d\e F \d\e Y') }}</span>
                        <span class="inline-block mr-3"><strong>Comida:</strong> {{ $tipoLabels[$tipo] ?? $tipo }}</span>
                        <span class="inline-block ml-3 text-gray-400">(NPO excluido)</span>
                    </div>
                </div>
                @if(auth()->user()->role !== 'usuario')
                    <button onclick="printSheet('prep-sheet-services')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg">🖨️ Imprimir por Servicios</button>
                @endif
            </div>

            @php
                // Resumen por servicio (para organizar entregas)
                $prepPorServicio = collect($registros)
                    ->groupBy(fn($r) => optional(optional($r->paciente)->servicio)->nombre ?? 'Sin servicio')
                    ->map(function($regs) {
                        $items = collect($regs)->flatMap(function($r) {
                            return collect($r->dietas)->map(function($d) use ($r) {
                                $nombre = strtolower($d->nombre ?? '');
                                $esNpo = str_contains($nombre, 'npo') || str_contains($nombre, 'n.p.o') || str_contains($nombre, 'nada por via oral') || str_contains($nombre, 'nada por vía oral');
                                return [
                                    'dieta' => $d->nombre,
                                    'tipo' => optional($d->tipo)->nombre,
                                    'subtipos' => $d->subtipos->pluck('nombre')->join(', '),
                                    'vajilla' => $r->vajilla,
                                    'observaciones' => $r->observaciones,
                                    'es_tardia' => $r->es_tardia,
                                    'npo' => $esNpo,
                                ];
                            });
                        })->filter(fn($i) => !$i['npo']);

                        $resumen = $items->groupBy('dieta')->map(function($grupo) {
                            $first = $grupo->first();
                            
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

                        return $resumen;
                    })
                    ->filter(fn($resumen) => $resumen->count() > 0);
            @endphp
            
            @if($prepPorServicio->count() > 0)
                <div>
                    @foreach($prepPorServicio as $servicioNombre => $resumen)
                        <div class="mb-5">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">{{ $servicioNombre }}</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Dieta</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Total</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase"><span class="text-xl">🍽️</span> Normal</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Obs. Normal</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase"><span class="text-xl">📦</span> Descartable</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Obs. Descartable</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($resumen as $nombre => $info)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $nombre }}</td>
                                                <td class="px-6 py-3 text-center text-sm font-semibold text-gray-900">{{ $info['total'] }}</td>
                                                <td class="px-6 py-3 text-center text-sm text-gray-800">{{ $info['normal'] }}</td>
                                                <td class="px-6 py-3 text-xs text-gray-700 max-w-sm">
                                                    @if(!empty($info['obs_normal']) && count($info['obs_normal']) > 0)
                                                        <div class="flex flex-wrap gap-1">
                                                            @foreach($info['obs_normal'] as $obs)
                                                                <span class="inline-flex items-center rounded-full px-2 py-0.5 bg-blue-100 text-blue-800 text-sm obs-badge">{{ $obs['txt'] }} <span class="ml-1 bg-white/70 text-gray-700 rounded px-1">{{ $obs['cnt'] }}</span></span>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                                <td class="px-6 py-3 text-center text-sm text-gray-800">{{ $info['descartable'] }}</td>
                                                <td class="px-6 py-3 text-xs text-gray-700 max-w-sm">
                                                    @if(!empty($info['obs_descartable']) && count($info['obs_descartable']) > 0)
                                                        <div class="flex flex-wrap gap-1">
                                                            @foreach($info['obs_descartable'] as $obs)
                                                                <span class="inline-flex items-center rounded-full px-2 py-0.5 bg-amber-100 text-amber-800 text-sm obs-badge">{{ $obs['txt'] }} <span class="ml-1 bg-white/70 text-gray-700 rounded px-1">{{ $obs['cnt'] }}</span></span>
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
                                    $servTotal = $resumen->reduce(fn($carry, $info) => $carry + ($info['total'] ?? 0), 0);
                                    $servNormal = $resumen->reduce(fn($c, $i) => $c + ($i['normal'] ?? 0), 0);
                                    $servDesc = $resumen->reduce(fn($c, $i) => $c + ($i['descartable'] ?? 0), 0);
                                @endphp
                                <div class="text-right text-sm text-gray-800 mt-2">
                                    Total dietas en {{ $servicioNombre }}: <span class="font-semibold">{{ $servTotal }}</span>
                                    <span class="ml-3 text-xs text-gray-600">(🍽️ {{ $servNormal }} · 📦 {{ $servDesc }})</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-gray-600">No hay servicios para mostrar.</div>
            @endif
        </div>

        <script>
        function printSheet(sheetId) {
            const sheet = document.getElementById(sheetId);
            const original = document.body.innerHTML;
            const printContent = sheet.innerHTML;
            document.body.innerHTML = '<div class="prep-sheet" style="padding: 20px;">' + printContent + '</div>';
            window.print();
            document.body.innerHTML = original;
            window.location.reload();
        }

        function printSheet(sheetId) {
            const sheet = document.getElementById(sheetId);
            const original = document.body.innerHTML;
            const printContent = sheet.innerHTML;
            
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
                <div class="prep-sheet">
                    <div style="text-align: right; font-size: 0.85rem; color: #6b7280; margin-bottom: 1rem;">
                        Impreso: ${fechaHora}
                    </div>
                    ${printContent}
                    <div style="margin-top: 2rem; padding-top: 1rem; border-top: 2px solid #e5e7eb; text-align: center; font-size: 0.85rem; color: #9ca3af;">
                        Sistema de Gestión de Dietas Hospitalarias
                    </div>
                </div>
            `;
            
            window.print();
            document.body.innerHTML = original;
            window.location.reload();
        }
        </script>

        <style>
        /* Pantalla: aumentar legibilidad en la hoja de preparación */
        .prep-sheet h2 { font-size: 1.75rem; }
        .prep-sheet table th, .prep-sheet table td { font-size: 1rem; }
        .prep-sheet .obs-badge { font-size: 0.95rem; padding: 4px 8px; }
        .prep-sheet { page-break-after: always; }

        @media print {
            body * {
                visibility: hidden;
            }
            
            .prep-sheet, .prep-sheet * {
                visibility: visible;
            }
            
            .prep-sheet {
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
            .prep-sheet h2 {
                font-size: 2rem;
                font-weight: bold;
                margin-bottom: 0.75rem;
                color: #1f2937;
                border-bottom: 3px solid #4f46e5;
                padding-bottom: 0.5rem;
            }
            
            .prep-sheet h3, .prep-sheet h4 {
                font-size: 1.25rem;
                font-weight: 600;
                margin-top: 1.5rem;
                margin-bottom: 0.75rem;
                color: #374151;
            }
            
            .prep-sheet p {
                font-size: 0.95rem;
                color: #4b5563;
                margin-bottom: 0.5rem;
            }
            
            /* Tablas */
            .prep-sheet table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 1rem;
                font-size: 1.05rem;
                page-break-inside: auto;
            }
            
            .prep-sheet thead {
                background: linear-gradient(to right, #eef2ff, #e0e7ff) !important;
                border-bottom: 2px solid #4f46e5;
            }
            
            .prep-sheet th {
                font-size: 0.95rem;
                padding: 14px 10px;
                text-align: left;
                font-weight: 700;
                color: #3730a3;
                border: 1px solid #c7d2fe;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }
            
            .prep-sheet td {
                padding: 10px;
                border: 1px solid #cbd5e1;
                color: #1f2937;
                vertical-align: top;
                font-size: 0.95rem;
            }
            
            .prep-sheet tbody tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            
            .prep-sheet tbody tr:nth-child(even) {
                background-color: #f9fafb !important;
            }
            
            /* Badges de dietas */
            .prep-sheet .bg-rose-100,
            .prep-sheet .bg-sky-100,
            .prep-sheet .bg-green-100,
            .prep-sheet .bg-amber-100,
            .prep-sheet .bg-cyan-100,
            .prep-sheet .bg-emerald-100,
            .prep-sheet .bg-indigo-100,
            .prep-sheet .bg-gray-200 {
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
            
            /* Badges de observaciones */
            .obs-badge {
                background-color: #fef3c7 !important;
                color: #78350f !important;
                padding: 4px 8px;
                border-radius: 6px;
                font-weight: 600;
                font-size: 0.85rem;
                border: 1px solid #fde68a;
            }
            
            /* Totales */
            .prep-sheet .text-right {
                text-align: right;
                font-weight: 700;
                font-size: 1.1rem;
                margin-top: 0.75rem;
                padding-top: 0.5rem;
                border-top: 2px solid #cbd5e1;
                color: #1f2937;
            }
            
            /* Ocultar botones y elementos innecesarios */
            button, a, .shadow-md {
                display: none !important;
            }
            
            /* Manejo de saltos de página */
            .service-block {
                page-break-after: always;
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

        <!-- Tabla Detallada -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-indigo-50 to-indigo-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Paciente</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Servicio</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Cama</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Dietas</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Vajilla</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Observaciones</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Registrado</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($registros as $r)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">{{ optional($r->paciente)->nombre }} {{ optional($r->paciente)->apellido }}</div>
                                <div class="text-xs text-gray-500">{{ optional($r->paciente)->cedula }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ optional(optional($r->paciente)->servicio)->nombre ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ optional(optional($r->paciente)->cama)->codigo ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($r->dietas as $dieta)
                                        @php
                                            $n = strtolower($dieta->nombre ?? '');
                                            $cls = 'bg-indigo-100 text-indigo-800';
                                            $title = '';
                                            if (str_contains($n, 'npo') || str_contains($n, 'n.p.o') || str_contains($n, 'nada por via oral') || str_contains($n, 'nada por vía oral')) {
                                                $cls = 'bg-gray-200 text-gray-700';
                                                $title = 'NPO (no contabilizable)';
                                            } elseif (str_contains($n, 'diab')) {
                                                $cls = 'bg-rose-100 text-rose-800';
                                            } elseif (str_contains($n, 'hiposod') || str_contains($n, 'sodio')) {
                                                $cls = 'bg-sky-100 text-sky-800';
                                            } elseif (str_contains($n, 'normal')) {
                                                $cls = 'bg-green-100 text-green-800';
                                            } elseif (str_contains($n, 'bland')) {
                                                $cls = 'bg-amber-100 text-amber-800';
                                            } elseif (str_contains($n, 'líquid') || str_contains($n, 'liquid')) {
                                                $cls = 'bg-cyan-100 text-cyan-800';
                                            } elseif (str_contains($n, 'veget')) {
                                                $cls = 'bg-emerald-100 text-emerald-800';
                                            }
                                        @endphp
                                        <span class="inline-block rounded-full px-2 py-1 text-xs font-medium {{ $cls }}" title="{{ $title }}">{{ $dieta->nombre }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $v = $r->vajilla;
                                    $cls = $v === 'descartable' ? 'bg-slate-100 text-slate-800' : 'bg-green-100 text-green-800';
                                    $label = $v === 'descartable' ? '📦 Descartable' : '🍽️ Normal';
                                @endphp
                                <span class="inline-block rounded-full px-2 py-1 text-xs font-medium {{ $cls }}">{{ $label }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate" title="{{ $r->observaciones }}">{{ $r->observaciones ?? '—' }}</td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                <div>{{ optional($r->createdBy)->name ?? '—' }}</div>
                                <div class="text-gray-400">{{ $r->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="{{ route('registro-dietas.show', $r) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">👁️ Ver</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-600">No hay registros para los filtros seleccionados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Totales al pie -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Totales del día</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-2">Dietas por tipo</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($totales['dietas_por_tipo'] as $tipoNombre => $count)
                            @php
                                $cls = 'bg-indigo-100 text-indigo-800';
                                if (str_contains(strtolower($tipoNombre), 'normal')) {
                                    $cls = 'bg-green-100 text-green-800';
                                } elseif (str_contains(strtolower($tipoNombre), 'terap')) {
                                    $cls = 'bg-rose-100 text-rose-800';
                                } elseif (str_contains(strtolower($tipoNombre), 'npo')) {
                                    $cls = 'bg-gray-200 text-gray-700';
                                } elseif (str_contains(strtolower($tipoNombre), 'consistencia')) {
                                    $cls = 'bg-amber-100 text-amber-800';
                                }
                            @endphp
                            <span class="inline-flex items-center gap-2 {{ $cls }} rounded-full px-3 py-1 text-xs font-semibold">
                                <span>{{ $tipoNombre }}</span>
                                <span class="bg-white/60 text-gray-700 rounded px-2 py-0.5">{{ $count }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-2">Dietas por subtipo</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($totales['dietas_por_subtipo'] as $subtipoNombre => $count)
                            @php
                                $n = strtolower($subtipoNombre);
                                $cls = 'bg-indigo-100 text-indigo-800';
                                if (str_contains($n, 'diab')) {
                                    $cls = 'bg-rose-100 text-rose-800';
                                } elseif (str_contains($n, 'hiposod') || str_contains($n, 'sodio')) {
                                    $cls = 'bg-sky-100 text-sky-800';
                                } elseif (str_contains($n, 'normal') || str_contains($n, 'completa')) {
                                    $cls = 'bg-green-100 text-green-800';
                                } elseif (str_contains($n, 'bland')) {
                                    $cls = 'bg-amber-100 text-amber-800';
                                } elseif (str_contains($n, 'líquid') || str_contains($n, 'liquid')) {
                                    $cls = 'bg-cyan-100 text-cyan-800';
                                } elseif (str_contains($n, 'veget')) {
                                    $cls = 'bg-emerald-100 text-emerald-800';
                                }
                            @endphp
                            <span class="inline-flex items-center gap-2 {{ $cls }} rounded-full px-3 py-1 text-xs font-semibold">
                                <span>{{ $subtipoNombre }}</span>
                                <span class="bg-white/60 text-gray-700 rounded px-2 py-0.5">{{ $count }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-2">Pacientes por servicio</h3>
                    <ul class="text-sm text-gray-800 space-y-1">
                        @foreach($totales['servicios'] as $servicioNombre => $count)
                            <li class="flex justify-between">
                                <span>{{ $servicioNombre }}</span>
                                <span class="font-semibold">{{ $count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-2">Resumen</h3>
                    <ul class="text-sm text-gray-800 space-y-1">
                        <li class="flex justify-between"><span>Total registros</span><span class="font-semibold">{{ $totales['registros'] }}</span></li>
                        <li class="flex justify-between"><span>Pacientes únicos</span><span class="font-semibold">{{ $totales['pacientes_unicos'] }}</span></li>
                        <li class="flex justify-between"><span>Dietas asignadas (excluye NPO)</span><span class="font-semibold">{{ $totales['dietas_total'] }}</span></li>
                        <li class="flex justify-between"><span>Dietas NPO</span>
                            <span class="inline-flex items-center gap-2">
                                <span class="font-semibold">{{ $totales['dietas_npo'] ?? 0 }}</span>
                                @if(($totales['dietas_npo'] ?? 0) > 0)
                                    <span class="px-2 py-0.5 rounded-full text-xs bg-gray-200 text-gray-700">Detectadas</span>
                                @endif
                            </span>
                        </li>
                        <li class="flex justify-between border-t pt-1 mt-1"><span>🍽️ Vajilla Normal</span><span class="font-semibold">{{ $totales['vajilla_normal'] ?? 0 }}</span></li>
                        <li class="flex justify-between"><span>📦 Descartable</span><span class="font-semibold">{{ $totales['vajilla_descartable'] ?? 0 }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
