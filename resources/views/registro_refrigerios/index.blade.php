@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">🥤 Registros de Refrigerios</h1>
                <p class="text-gray-600 mt-1">Listado de refrigerios registrados a pacientes</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('registro-refrigerios.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">📊 Dashboard</a>
                @if(auth()->user()->role !== 'usuario')
                    <a href="{{ route('registro-refrigerios.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 text-white rounded-lg shadow-md transition-all duration-200 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Registrar Refrigerio
                    </a>
                @endif
            </div>
        </div>

        <!-- Filtros -->
        <form method="GET" class="bg-white rounded-lg shadow-md p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="text-sm text-gray-600">Buscar</label>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" class="mt-1 w-full border rounded-lg px-3 py-2" placeholder="Paciente (nombre/apellido/cedula), refrigerio o momento">
                </div>
                <div>
                    <label class="text-sm text-gray-600">Fecha</label>
                    <input type="date" name="fecha" value="{{ request('fecha') }}" class="mt-1 w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="text-sm text-gray-600">Momento</label>
                    <select name="momento" class="mt-1 w-full border rounded-lg px-3 py-2">
                        <option value="">Todos</option>
                        @foreach(['mañana','tarde','noche'] as $m)
                            <option value="{{ $m }}" @selected(request('momento')===$m)>{{ ucfirst($m) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="px-5 py-2.5 bg-blue-300 text-blue-900 rounded-lg hover:bg-blue-400 transition font-semibold shadow-md">Filtrar</button>
                    <a href="{{ route('registro-refrigerios.index') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition">Limpiar</a>
                </div>
            </div>
        </form>

        @php
            // Agrupar por paciente + fecha + momento para mostrar una sola fila
            $grupos = $registros->groupBy(function($item) {
                return $item->paciente_id.'|'.$item->fecha.'|'.$item->momento;
            });
        @endphp

        <!-- Vista TABLA para Desktop -->
        <div class="hidden md:block bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-orange-50 to-orange-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Paciente</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Refrigerios</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Momento</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Registrado por</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Actualizado por</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($grupos as $clave => $items)
                            @php
                                $base = $items->first();
                                $nombresRefrigerios = $items->pluck('refrigerio.nombre')->filter()->unique();
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $base->paciente->nombre }} {{ $base->paciente->apellido }}</div>
                                    <div class="text-xs text-gray-500">{{ $base->paciente->cedula }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($nombresRefrigerios as $nombre)
                                            <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-orange-100 text-orange-800">{{ $nombre }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($base->fecha)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-sky-100 text-sky-800">{{ ucfirst($base->momento) }}</span>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500">
                                    <div>{{ optional($base->createdBy)->name ?? '—' }}</div>
                                    <div class="text-gray-400">{{ $base->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500">
                                    <button 
                                        onclick="mostrarActualizaciones(
                                            {{ $base->id }}, 
                                            '{{ addslashes($base->paciente->nombre) }} {{ addslashes($base->paciente->apellido) }}', 
                                            '{{ $base->paciente->cedula }}',
                                            '{{ $nombresRefrigerios->implode(', ') }}',
                                            '{{ \Carbon\Carbon::parse($base->fecha)->format('d/m/Y') }}', 
                                            '{{ ucfirst($base->momento) }}',
                                            '{{ addslashes(optional($base->createdBy)->name ?? 'Sistema') }}',
                                            '{{ $base->created_at->format('d/m/Y H:i') }}',
                                            '{{ addslashes(optional($base->updatedBy)->name ?? 'Sistema') }}',
                                            '{{ $base->updated_at->format('d/m/Y H:i') }}'
                                        )" 
                                        class="text-left hover:bg-blue-50 rounded px-2 py-1 -mx-2 -my-1 transition-colors cursor-pointer group w-full"
                                    >
                                        <div class="group-hover:text-blue-600">{{ optional($base->updatedBy)->name ?? '—' }}</div>
                                        <div class="text-gray-400 group-hover:text-blue-500">{{ $base->updated_at->format('d/m/Y H:i') }}</div>
                                        <div class="text-blue-500 text-xs mt-0.5 opacity-0 group-hover:opacity-100 transition-opacity">📋 Ver historial</div>
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('registro-refrigerios.show', $base) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">👁️ Ver</a>
                                        @if(auth()->user()->role !== 'usuario')
                                            <a href="{{ route('registro-refrigerios.edit', $base) }}" class="text-blue-600 hover:text-blue-900">✏️ Editar</a>
                                        @endif
                                        @if(auth()->user()->role === 'admin')
                                            <form action="{{ route('registro-refrigerios.destroy', $base) }}" method="POST" class="inline" onsubmit="return confirm('Eliminar registro?')">
                                                @csrf @method('DELETE')
                                                <button class="text-red-600 hover:text-red-900">🗑️ Eliminar</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-600">No hay registros aún.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t">{{ $registros->links() }}</div>
            <div class="px-6 pb-4 text-xs text-gray-500">* Las filas se agrupan por paciente + fecha + momento.</div>
        </div>

        <!-- Vista TARJETAS para Móvil -->
        <div class="md:hidden space-y-4">
            @forelse($grupos as $clave => $items)
                @php
                    $base = $items->first();
                    $nombresRefrigerios = $items->pluck('refrigerio.nombre')->filter()->unique();
                @endphp
                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-orange-500">
                    <!-- Paciente -->
                    <div class="mb-3 pb-3 border-b border-gray-200">
                        <div class="text-base font-bold text-gray-900">{{ $base->paciente->nombre }} {{ $base->paciente->apellido }}</div>
                        <div class="text-sm text-gray-600 mt-1">CI: {{ $base->paciente->cedula }}</div>
                    </div>

                    <!-- Refrigerios -->
                    <div class="mb-3">
                        <div class="text-xs font-semibold text-gray-500 uppercase mb-2">🥤 Refrigerios</div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($nombresRefrigerios as $nombre)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">{{ $nombre }}</span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Fecha y Momento -->
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <div class="text-xs font-semibold text-gray-500 uppercase mb-1">📅 Fecha</div>
                            <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($base->fecha)->format('d/m/Y') }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-gray-500 uppercase mb-1">⏰ Momento</div>
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-sky-100 text-sky-800">{{ ucfirst($base->momento) }}</span>
                        </div>
                    </div>

                    <!-- Registrado por -->
                    <div class="mb-3 pb-3 border-b border-gray-100">
                        <div class="text-xs font-semibold text-gray-500 uppercase mb-1">👤 Registrado por</div>
                        <div class="text-sm text-gray-700">{{ optional($base->createdBy)->name ?? '—' }}</div>
                        <div class="text-xs text-gray-500">{{ $base->created_at->format('d/m/Y H:i') }}</div>
                    </div>

                    <!-- Actualizado por -->
                    <div class="mb-4">
                        <div class="text-xs font-semibold text-gray-500 uppercase mb-1">🔄 Actualizado por</div>
                        <button 
                            onclick="mostrarActualizaciones(
                                {{ $base->id }}, 
                                '{{ addslashes($base->paciente->nombre) }} {{ addslashes($base->paciente->apellido) }}', 
                                '{{ $base->paciente->cedula }}',
                                '{{ $nombresRefrigerios->implode(', ') }}',
                                '{{ \Carbon\Carbon::parse($base->fecha)->format('d/m/Y') }}', 
                                '{{ ucfirst($base->momento) }}',
                                '{{ addslashes(optional($base->createdBy)->name ?? 'Sistema') }}',
                                '{{ $base->created_at->format('d/m/Y H:i') }}',
                                '{{ addslashes(optional($base->updatedBy)->name ?? 'Sistema') }}',
                                '{{ $base->updated_at->format('d/m/Y H:i') }}'
                            )" 
                            class="text-left hover:bg-blue-50 rounded px-2 py-1 -mx-2 transition-colors w-full"
                        >
                            <div class="text-sm text-gray-700">{{ optional($base->updatedBy)->name ?? '—' }}</div>
                            <div class="text-xs text-gray-500">{{ $base->updated_at->format('d/m/Y H:i') }}</div>
                            <div class="text-blue-600 text-xs mt-1">📋 Ver historial completo</div>
                        </button>
                    </div>

                    <!-- Acciones -->
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('registro-refrigerios.show', $base) }}" class="flex-1 text-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
                            👁️ Ver
                        </a>
                        @if(auth()->user()->role !== 'usuario')
                            <a href="{{ route('registro-refrigerios.edit', $base) }}" class="flex-1 text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                                ✏️ Editar
                            </a>
                        @endif
                        @if(auth()->user()->role === 'admin')
                            <form action="{{ route('registro-refrigerios.destroy', $base) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Eliminar registro?')">
                                @csrf @method('DELETE')
                                <button class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition">🗑️ Eliminar</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <div class="text-gray-600">No hay registros aún.</div>
                </div>
            @endforelse

            <div class="mt-6">{{ $registros->links() }}</div>
            <div class="text-xs text-gray-500 text-center mt-2">* Las tarjetas se agrupan por paciente + fecha + momento.</div>
        </div>

        @isset($totales)
            <div class="mt-6 bg-gradient-to-br from-orange-50 to-amber-50 rounded-lg shadow-lg border border-orange-200 overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-orange-600 to-amber-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        📊 Resumen de Refrigerios
                    </h2>
                    <p class="text-orange-100 text-sm mt-1">Estadísticas según los filtros aplicados</p>
                </div>

                <div class="p-6">
                    <!-- Totales principales -->
                    <div class="mb-6">
                        <div class="bg-white rounded-lg shadow-md border-l-4 border-orange-500 p-6 max-w-md">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Registros</p>
                                    <p class="text-4xl font-bold text-orange-600 mt-1">{{ $totales['registros'] }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Agrupados por paciente/fecha/momento</p>
                                </div>
                                <div class="h-16 w-16 bg-orange-100 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Distribución por momentos -->
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Distribución por Momento del Día
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="bg-gradient-to-br from-amber-50 to-yellow-50 border-2 border-amber-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 bg-amber-200 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-2xl">🌅</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-sm font-semibold text-amber-900">Mañana</div>
                                        <div class="text-2xl font-bold text-amber-700">{{ $totales['momentos']['mañana'] ?? 0 }}</div>
                                        <div class="text-xs text-amber-600">{{ $totales['momentos']['mañana'] > 0 ? round(($totales['momentos']['mañana'] / max($totales['registros'], 1)) * 100, 1) : 0 }}% del total</div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-br from-sky-50 to-blue-50 border-2 border-sky-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 bg-sky-200 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-2xl">🌞</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-sm font-semibold text-sky-900">Tarde</div>
                                        <div class="text-2xl font-bold text-sky-700">{{ $totales['momentos']['tarde'] ?? 0 }}</div>
                                        <div class="text-xs text-sky-600">{{ $totales['momentos']['tarde'] > 0 ? round(($totales['momentos']['tarde'] / max($totales['registros'], 1)) * 100, 1) : 0 }}% del total</div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-br from-purple-50 to-indigo-50 border-2 border-purple-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 bg-purple-200 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-2xl">🌙</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-sm font-semibold text-purple-900">Noche</div>
                                        <div class="text-2xl font-bold text-purple-700">{{ $totales['momentos']['noche'] ?? 0 }}</div>
                                        <div class="text-xs text-purple-600">{{ $totales['momentos']['noche'] > 0 ? round(($totales['momentos']['noche'] / max($totales['registros'], 1)) * 100, 1) : 0 }}% del total</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endisset
    </div>
</div>

<!-- Modal de Actualizaciones -->
<div id="modalActualizaciones" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-xl font-bold text-gray-900">📝 Historial de Actualizaciones</h3>
            <button onclick="cerrarModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div id="contenidoModal" class="space-y-4">
            <!-- Se llenará dinámicamente -->
        </div>
    </div>
</div>

<script>
function mostrarActualizaciones(registroId, paciente, cedula, refrigerios, fecha, momento, creador, fechaCreacion, actualizador, fechaActualizacion) {
    const modal = document.getElementById('modalActualizaciones');
    const contenido = document.getElementById('contenidoModal');
    
    contenido.innerHTML = `
        <div class="bg-gradient-to-r from-orange-50 to-amber-50 border-l-4 border-orange-500 p-4 rounded">
            <div class="font-semibold text-orange-900 mb-2">👤 Información del Paciente</div>
            <div class="text-orange-800"><strong>${paciente}</strong></div>
            <div class="text-orange-700 text-sm">Cédula: ${cedula}</div>
        </div>
        
        <div class="bg-gradient-to-r from-blue-50 to-sky-50 border-l-4 border-blue-500 p-4 rounded">
            <div class="font-semibold text-blue-900 mb-2">🍽️ Detalles del Registro</div>
            <div class="text-blue-800"><strong>Refrigerios:</strong> ${refrigerios}</div>
            <div class="text-blue-700 text-sm"><strong>Fecha:</strong> ${fecha}</div>
            <div class="text-blue-700 text-sm"><strong>Momento:</strong> ${momento}</div>
        </div>
        
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <h4 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Historial de Cambios
            </h4>
            <div class="space-y-3">
                <div class="bg-green-50 border-l-4 border-green-400 pl-4 py-3 rounded-r">
                    <div class="flex items-start gap-2">
                        <span class="text-green-600 text-xl">✅</span>
                        <div class="flex-1">
                            <div class="font-medium text-green-900">Creación del Registro</div>
                            <div class="text-green-700 text-sm mt-1">Por: <span class="font-semibold">${creador}</span></div>
                            <div class="text-green-600 text-xs mt-1">
                                <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                ${fechaCreacion}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-blue-50 border-l-4 border-blue-400 pl-4 py-3 rounded-r">
                    <div class="flex items-start gap-2">
                        <span class="text-blue-600 text-xl">🔄</span>
                        <div class="flex-1">
                            <div class="font-medium text-blue-900">Última Actualización</div>
                            <div class="text-blue-700 text-sm mt-1">Por: <span class="font-semibold">${actualizador}</span></div>
                            <div class="text-blue-600 text-xs mt-1">
                                <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                ${fechaActualizacion}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 flex items-start gap-2">
            <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-sm text-yellow-800">
                <strong>Nota:</strong> Este registro agrupa todos los refrigerios del paciente para la fecha y momento especificados.
            </div>
        </div>
    `;
    
    modal.classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modalActualizaciones').classList.add('hidden');
}

// Cerrar al hacer clic fuera del modal
document.getElementById('modalActualizaciones')?.addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

// Cerrar con tecla Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') cerrarModal();
});
</script>
@endsection