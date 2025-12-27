@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">🍪 Dashboard de Refrigerios del Día</h1>
                <p class="text-gray-600 mt-1">Visualiza y gestiona los refrigerios por momento del día</p>
            </div>
            <a href="{{ route('registro-refrigerios.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg">Volver a Registros</a>
        </div>

        <!-- Filtros -->
        <form method="GET" action="{{ route('registro-refrigerios.dashboard') }}" class="mb-6 p-4 bg-white rounded-lg shadow-md">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="text-sm text-gray-600">Fecha</label>
                    <input type="date" name="fecha" value="{{ $fecha }}" class="mt-1 w-full border rounded-lg px-3 py-2" />
                </div>
                <div>
                    <label class="text-sm text-gray-600">Momento del día</label>
                    <select name="momento" class="mt-1 w-full border rounded-lg px-3 py-2">
                        @foreach(['mañana' => 'Mañana', 'tarde' => 'Tarde', 'noche' => 'Noche'] as $key => $label)
                            <option value="{{ $key }}" @selected($momento === $key)>{{ $label }}</option>
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
                <p class="text-sm text-pink-700">Refrigerios totales</p>
                <p class="text-3xl font-bold text-pink-900">{{ $totales['refrigerios_total'] }}</p>
            </div>
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <p class="text-sm text-amber-700">Servicios</p>
                <p class="text-3xl font-bold text-amber-900">{{ $totales['servicios']->sum() }}</p>
            </div>
        </div>

        @php
            $momentoLabels = ['mañana' => 'Mañana', 'tarde' => 'Tarde', 'noche' => 'Noche'];
        @endphp

        <!-- Hoja de Preparación General -->
        <div id="prep-sheet-general" class="bg-white rounded-lg shadow-md p-6 mb-6 prep-sheet">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">📋 Hoja de preparación general</h2>
                    <p class="text-sm text-gray-600">Resumen consolidado de todos los refrigerios</p>
                    <div class="mt-2 text-xs text-gray-500">
                        <span class="inline-block mr-3"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($fecha)->locale('es')->translatedFormat('l, d \d\e F \d\e Y') }}</span>
                        <span class="inline-block mr-3"><strong>Momento:</strong> {{ $momentoLabels[$momento] ?? $momento }}</span>
                        @if(request('servicio_id'))
                            <span class="inline-block mr-3"><strong>Servicio:</strong> {{ $servicios->find(request('servicio_id'))->nombre ?? 'Todos' }}</span>
                        @endif
                    </div>
                </div>
                @if(auth()->user()->role !== 'usuario')
                    <button onclick="printSheet('prep-sheet-general')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg">🖨️ Imprimir General</button>
                @endif
            </div>

            @php
                // Construir items y agrupar por refrigerio
                $prepItems = collect($registros)->map(function($r) {
                    return [
                        'refrigerio' => optional($r->refrigerio)->nombre,
                        'observacion' => $r->observacion,
                    ];
                });

                // Agrupar por refrigerio y contar
                $prepResumen = $prepItems->groupBy('refrigerio')->map(function($grupo) {
                    $obsCounts = $grupo->pluck('observacion')->filter()->map(fn($t) => trim($t))->filter()->countBy();
                    $obsList = $obsCounts->map(function($cnt, $txt) { return ['txt' => $txt, 'cnt' => $cnt]; })->values();
                    
                    return [
                        'total' => $grupo->count(),
                        'observaciones' => $obsList,
                    ];
                })->sortByDesc('total');
            @endphp

            @if($prepResumen->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Refrigerio</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Observaciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($prepResumen as $nombre => $info)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $nombre }}</td>
                                    <td class="px-6 py-3 text-center text-sm font-semibold text-gray-900">{{ $info['total'] }}</td>
                                    <td class="px-6 py-3 text-xs text-gray-700 max-w-sm">
                                        @if(!empty($info['observaciones']) && count($info['observaciones']) > 0)
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($info['observaciones'] as $obs)
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
                    @endphp
                    <div class="text-right text-sm text-gray-800 mt-2">
                        Total refrigerios a preparar: <span class="font-semibold">{{ $prepTotal }}</span>
                    </div>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No hay registros para mostrar</p>
            @endif
        </div>

        <!-- Hoja de Preparación por Servicios -->
        <div id="prep-sheet-services" class="bg-white rounded-lg shadow-md p-6 mb-6 prep-sheet">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">🏥 Hoja de preparación por servicios</h2>
                    <p class="text-sm text-gray-600">Desglose de refrigerios organizados por área/piso</p>
                    <div class="mt-2 text-xs text-gray-500">
                        <span class="inline-block mr-3"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($fecha)->locale('es')->translatedFormat('l, d \d\e F \d\e Y') }}</span>
                        <span class="inline-block mr-3"><strong>Momento:</strong> {{ $momentoLabels[$momento] ?? $momento }}</span>
                    </div>
                </div>
                @if(auth()->user()->role !== 'usuario')
                    <button onclick="printSheet('prep-sheet-services')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg">🖨️ Imprimir por Servicios</button>
                @endif
            </div>

            @php
                // Resumen por servicio con detalle de cama y paciente
                $prepPorServicio = collect($registros)
                    ->groupBy(fn($r) => optional(optional($r->paciente)->servicio)->nombre ?? 'Sin servicio')
                    ->map(function($regs) {
                        $items = collect($regs)->map(function($r) {
                            return [
                                'refrigerio' => optional($r->refrigerio)->nombre,
                                'observacion' => $r->observacion,
                                'cama' => optional(optional($r->paciente)->cama)->codigo ?? 'S/C',
                                'paciente_nombre' => optional($r->paciente)->nombre . ' ' . optional($r->paciente)->apellido,
                            ];
                        });

                        $resumen = $items->groupBy('refrigerio')->map(function($grupo) {
                            $obsCounts = $grupo->pluck('observacion')->filter()->map(fn($t) => trim($t))->filter()->countBy();
                            $obsList = $obsCounts->map(function($cnt, $txt) { return ['txt' => $txt, 'cnt' => $cnt]; })->values();
                            
                            // Crear lista de entregas con cama + paciente
                            $entregas = $grupo->map(function($item) {
                                return [
                                    'cama' => $item['cama'],
                                    'paciente' => $item['paciente_nombre']
                                ];
                            })->values()->all();
                            
                            return [
                                'total' => $grupo->count(),
                                'observaciones' => $obsList,
                                'entregas' => $entregas,
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
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Refrigerio</th>
                                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Cantidad</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">🛏️ Entregar en Camas</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($resumen as $nombre => $info)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $nombre }}</td>
                                                <td class="px-6 py-3 text-center text-sm font-semibold text-gray-900">{{ $info['total'] }}</td>
                                                <td class="px-6 py-3 text-sm">
                                                    <div class="space-y-2">
                                                        @foreach($info['entregas'] as $entrega)
                                                            <div class="text-sm text-gray-800 flex items-center gap-2">
                                                                <span class="inline-flex items-center px-2.5 py-1.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-bold text-sm shadow-md min-w-[3.5rem] justify-center">
                                                                    {{ $entrega['cama'] }}
                                                                </span>
                                                                <span class="font-medium text-gray-900">{{ $entrega['paciente'] }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td class="px-6 py-3 text-xs text-gray-700 max-w-sm">
                                                    @if(!empty($info['observaciones']) && count($info['observaciones']) > 0)
                                                        <div class="flex flex-wrap gap-1">
                                                            @foreach($info['observaciones'] as $obs)
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
                                @endphp
                                <div class="text-right text-sm text-gray-800 mt-2">
                                    Total en {{ $servicioNombre }}: <span class="font-semibold">{{ $servTotal }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No hay registros para mostrar</p>
            @endif
        </div>

        <!-- Tabla detallada de registros -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="mb-4">
                <h2 class="text-lg font-semibold text-gray-800">📋 Detalle de registros</h2>
                <p class="text-sm text-gray-600">Información completa de todos los registros del período filtrado</p>
            </div>

            @if($registros->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Paciente</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Cédula</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Servicio</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Cama</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Refrigerio</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Momento</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Fecha</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Observación</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                // Agrupar por paciente + fecha + momento
                                $registrosAgrupados = $registros->groupBy(function($item) {
                                    return $item->paciente_id.'|'.$item->fecha.'|'.$item->momento;
                                });
                            @endphp
                            @foreach($registrosAgrupados as $clave => $regsDelGrupo)
                                @php
                                    $primerReg = $regsDelGrupo->first();
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                        {{ optional($primerReg->paciente)->nombre }} {{ optional($primerReg->paciente)->apellido }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ optional($primerReg->paciente)->cedula }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">
                                        {{ optional(optional($primerReg->paciente)->servicio)->nombre ?? 'Sin servicio' }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm text-gray-700">
                                        {{ optional(optional($primerReg->paciente)->cama)->codigo ?? '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($regsDelGrupo as $reg)
                                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                                    {{ optional($reg->refrigerio)->nombre }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @php
                                            $momentoColors = [
                                                'mañana' => 'bg-yellow-100 text-yellow-800',
                                                'tarde' => 'bg-blue-100 text-blue-800',
                                                'noche' => 'bg-purple-100 text-purple-800'
                                            ];
                                            $color = $momentoColors[$primerReg->momento] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                            {{ ucfirst($primerReg->momento) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($primerReg->fecha)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-600 max-w-xs">
                                        @foreach($regsDelGrupo as $reg)
                                            @if($reg->observacion)
                                                <div class="mb-1">• {{ $reg->observacion }}</div>
                                            @endif
                                        @endforeach
                                        @if($regsDelGrupo->whereNotNull('observacion')->count() == 0)
                                            —
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('registro-refrigerios.show', $primerReg->id) }}" class="inline-flex items-center justify-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-md text-xs font-medium transition">
                                            👁️ Ver
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 text-sm text-gray-600">
                    Total de registros: <span class="font-semibold">{{ $registrosAgrupados->count() }}</span>
                    <span class="text-xs text-gray-500 ml-2">(agrupados por paciente/fecha/momento)</span>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No hay registros para mostrar</p>
            @endif
        </div>
    </div>
</div>

<style>
.prep-sheet {
    page-break-after: always;
}

.obs-badge {
    white-space: nowrap;
}

@media print {
    /* Ocultar todo excepto la hoja de preparación */
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
        border-bottom: 3px solid #2563eb;
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
        font-size: 1.1rem;
        page-break-inside: auto;
    }
    
    .prep-sheet thead {
        background: linear-gradient(to right, #eff6ff, #dbeafe) !important;
        border-bottom: 2px solid #2563eb;
    }
    
    .prep-sheet th {
        font-size: 1rem;
        padding: 14px 12px;
        text-align: left;
        font-weight: 700;
        color: #1e40af;
        border: 1px solid #93c5fd;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .prep-sheet td {
        padding: 12px;
        border: 1px solid #cbd5e1;
        color: #1f2937;
        vertical-align: top;
    }
    
    .prep-sheet tbody tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
    
    .prep-sheet tbody tr:nth-child(even) {
        background-color: #f9fafb !important;
    }
    
    .prep-sheet tbody tr:hover {
        background-color: inherit !important;
    }
    
    /* Badges y chips */
    .prep-sheet .bg-orange-100,
    .prep-sheet .bg-amber-100,
    .prep-sheet .bg-blue-100,
    .prep-sheet .bg-sky-100,
    .prep-sheet .bg-yellow-100,
    .prep-sheet .bg-purple-100 {
        background-color: #e0e7ff !important;
        color: #3730a3 !important;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-block;
        border: 1px solid #c7d2fe;
    }
    
    .prep-sheet .bg-gradient-to-r {
        background: #2563eb !important;
        color: white !important;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 1.1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* Totales y resúmenes */
    .prep-sheet .text-right {
        text-align: right;
        font-weight: 700;
        font-size: 1.15rem;
        margin-top: 0.75rem;
        padding-top: 0.5rem;
        border-top: 2px solid #cbd5e1;
        color: #1f2937;
    }
    
    /* Información de fecha */
    .prep-sheet .text-xs.text-gray-500 {
        font-size: 0.9rem !important;
        color: #6b7280 !important;
        margin-bottom: 1rem;
    }
    
    /* Ocultar botones y elementos innecesarios */
    button, .bg-gray-100, .bg-blue-50, .bg-emerald-50, 
    .bg-pink-50, .bg-amber-50, .shadow-md, a {
        display: none !important;
    }
    
    /* Separación entre secciones */
    .prep-sheet > div {
        margin-bottom: 2rem;
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

<script>
function printSheet(sheetId) {
    const originalContent = document.body.innerHTML;
    const sheetContent = document.getElementById(sheetId).innerHTML;
    
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
@endsection
