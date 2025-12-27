@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">🥤 Registros de Refrigerios</h1>
                <p class="text-gray-600 mt-1">Listado de refrigerios registrados a pacientes</p>
            </div>
            @if(auth()->user()->role !== 'usuario')
                <a href="{{ route('registro-refrigerios.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 text-white rounded-lg shadow-md transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Registrar Refrigerio
                </a>
            @endif
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
                <div class="flex items-end">
                    <button class="px-4 py-2 bg-orange-600 text-white rounded-lg">Filtrar</button>
                </div>
            </div>
        </form>

        <!-- Tabla -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
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
                        @forelse($registros as $r)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $r->paciente->nombre }} {{ $r->paciente->apellido }}</div>
                                <div class="text-xs text-gray-500">{{ $r->paciente->cedula }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        // Obtener todos los refrigerios de este paciente en este momento
                                        $pacienteRefrigerios = $registros->filter(function($item) use ($r) {
                                            return $item->paciente_id === $r->paciente_id && $item->fecha === $r->fecha && $item->momento === $r->momento;
                                        })->map(function($item) {
                                            return $item->refrigerio->nombre;
                                        })->unique();
                                    @endphp
                                    @foreach($pacienteRefrigerios as $nombre)
                                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-orange-100 text-orange-800">{{ $nombre }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ \Carbon\Carbon::parse($r->fecha)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-sky-100 text-sky-800">{{ ucfirst($r->momento) }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                <div>{{ optional($r->createdBy)->name ?? '—' }}</div>
                                <div class="text-gray-400">{{ $r->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                <div>{{ optional($r->updatedBy)->name ?? '—' }}</div>
                                <div class="text-gray-400">{{ $r->updated_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('registro-refrigerios.show', $r) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">👁️ Ver</a>
                                    @if(auth()->user()->role !== 'usuario')
                                        <a href="{{ route('registro-refrigerios.edit', $r) }}" class="text-blue-600 hover:text-blue-900">✏️ Editar</a>
                                        <form action="{{ route('registro-refrigerios.destroy', $r) }}" method="POST" class="inline" onsubmit="return confirm('Eliminar registro?')">
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
        </div>
    </div>
</div>
@endsection