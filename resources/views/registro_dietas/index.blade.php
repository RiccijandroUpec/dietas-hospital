@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="font-semibold text-2xl text-gray-800">📋 Registros de Dieta</h2>
                        <p class="text-gray-600 text-sm mt-1">Gestión de registros dietéticos de pacientes</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('registro-dietas.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition">
                            📊 Dashboard
                        </a>
                        <a href="{{ route('registro-dietas.reporte') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition">
                            📄 Reporte
                        </a>
                        @if(auth()->user()->role !== 'usuario')
                            <a href="{{ route('registro-dietas.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition">
                                ➕ Nuevo Registro
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-md flex items-start">
                        <span class="mr-3">✓</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Filters -->
                <form method="GET" action="{{ route('registro-dietas.index') }}" class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Buscar Paciente</label>
                            <input type="text" name="search" placeholder="Nombre o cédula" value="{{ request('search') }}" class="w-full border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Comida</label>
                            <select name="tipo_comida" class="w-full border-gray-300 rounded-md text-sm">
                                <option value="">-- Todos --</option>
                                <option value="desayuno" @if(request('tipo_comida') == 'desayuno') selected @endif>Desayuno</option>
                                <option value="almuerzo" @if(request('tipo_comida') == 'almuerzo') selected @endif>Almuerzo</option>
                                <option value="merienda" @if(request('tipo_comida') == 'merienda') selected @endif>Merienda</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                            <input type="date" name="fecha" value="{{ request('fecha') }}" class="w-full border-gray-300 rounded-md text-sm">
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm transition">🔍 Buscar</button>
                            <a href="{{ route('registro-dietas.index') }}" class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-md text-sm transition">↺ Limpiar</a>
                        </div>
                    </div>
                </form>

                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 p-3 rounded-lg border border-blue-200">
                        <div class="text-xs font-medium text-blue-600">Total de Registros</div>
                        <div class="text-2xl font-bold text-blue-900">{{ $registros->total() }}</div>
                    </div>
                    <div class="bg-purple-50 p-3 rounded-lg border border-purple-200">
                        <div class="text-xs font-medium text-purple-600">En esta página</div>
                        <div class="text-2xl font-bold text-purple-900">{{ $registros->count() }}</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <div class="text-xs font-medium text-gray-600">Última actualización</div>
                        <div class="text-lg font-bold text-gray-900">{{ now()->format('d/m/Y H:i') }}</div>
                    </div>
                </div>

                <!-- Table -->
                <div class="w-full overflow-x-auto">
                    @if($registros->count() > 0)
                        <table class="w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Paciente</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Tipo Comida</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Presentación</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Dietas</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Fecha</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Observaciones</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700 text-xs">Registrado por</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700 text-xs">Actualizado por</th>
                                    <th class="px-4 py-3 text-center font-semibold text-gray-700">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($registros as $r)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 font-medium text-gray-900">
                                            {{ optional($r->paciente)->nombre }} {{ optional($r->paciente)->apellido }}
                                            @if($r->paciente)
                                                <div class="text-xs text-gray-500">{{ $r->paciente->cedula }}</div>
                                            @endif
                                            @if($r->es_tardia)
                                                <span class="inline-block mt-1 px-2 py-0.5 bg-red-100 text-red-700 rounded text-xs font-semibold">🔴 TARDÍA</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @php 
                                                $tipos = [
                                                    'desayuno' => ['Desayuno', 'bg-yellow-100 text-yellow-800'],
                                                    'almuerzo' => ['Almuerzo', 'bg-orange-100 text-orange-800'],
                                                    'merienda' => ['Merienda', 'bg-pink-100 text-pink-800']
                                                ];
                                                $tipo = $tipos[$r->tipo_comida] ?? ['', ''];
                                            @endphp
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $tipo[1] }}">
                                                {{ $tipo[0] }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            @php
                                                $v = $r->vajilla;
                                                $cls = $v === 'descartable' ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800';
                                                $label = $v === 'descartable' ? 'Descartable' : 'Vajilla normal';
                                            @endphp
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $cls }}">{{ $label }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($r->dietas as $dieta)
                                                    @php
                                                        $n = strtolower($dieta->nombre ?? '');
                                                        $cls = 'bg-indigo-100 text-indigo-800';
                                                        if (str_contains($n, 'diab')) {
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
                                                    <span class="inline-block rounded-full px-2 py-1 text-xs font-medium {{ $cls }}">{{ $dieta->nombre }}</span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">
                                            {{ \Carbon\Carbon::parse($r->fecha)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-600 max-w-xs truncate" title="{{ $r->observaciones }}">
                                            {{ $r->observaciones ?? '–' }}
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-500">
                                            <div>{{ optional($r->createdBy)->name ?? '—' }}</div>
                                            <div class="text-gray-400">{{ $r->created_at->format('d/m/Y') }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-500">
                                            <div>{{ optional($r->updatedBy)->name ?? '—' }}</div>
                                            <div class="text-gray-400">{{ $r->updated_at->format('d/m/Y') }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex justify-center gap-2">
                                                <a href="{{ route('registro-dietas.show', $r) }}" class="px-3 py-1 bg-indigo-100 text-indigo-700 hover:bg-indigo-200 rounded text-xs font-medium transition">👁️ Ver</a>
                                                @if(auth()->user()->role !== 'usuario')
                                                    <a href="{{ route('registro-dietas.edit', $r) }}" class="px-3 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded text-xs font-medium transition">✏️ Editar</a>
                                                    <form action="{{ route('registro-dietas.destroy', $r) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar este registro?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 hover:bg-red-200 rounded text-xs font-medium transition">🗑️ Eliminar</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">📭</div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">No hay registros</h3>
                            <p class="text-gray-600 mb-4">No se encontraron registros de dieta con los criterios de búsqueda.</p>
                            @if(auth()->user()->role !== 'usuario')
                                <a href="{{ route('registro-dietas.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                    ➕ Crear primer registro
                                </a>
                            @endif
                        </div>
                    @endif

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $registros->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
