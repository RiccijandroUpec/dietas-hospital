@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">🏥 Servicios Hospitalarios</h1>
                <p class="text-gray-600 mt-1">Gestión de servicios y departamentos</p>
            </div>
            <a href="{{ route('servicios.create') }}" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-sky-600 to-sky-700 hover:from-sky-700 hover:to-sky-800 text-white rounded-lg shadow-md transition-all duration-200 transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Crear Servicio
            </a>
        </div>

        <!-- Mensajes -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-start">
                <span class="mr-3 text-xl">✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Tabla de Servicios -->
        @if($servicios->count() > 0)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-sky-50 to-sky-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">🏥 Nombre del Servicio</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Descripción</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">ID</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($servicios as $servicio)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="text-base font-semibold text-gray-900">{{ $servicio->nombre }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600 max-w-lg">
                                            @if($servicio->descripcion)
                                                <span title="{{ $servicio->descripcion }}">{{ Str::limit($servicio->descripcion, 80) }}</span>
                                            @else
                                                <span class="text-gray-400 italic">Sin descripción</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-block bg-sky-100 text-sky-800 rounded-full px-3 py-1 text-xs font-semibold">#{{ $servicio->id }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('servicios.show', $servicio) }}" class="px-4 py-2 bg-indigo-100 text-indigo-700 hover:bg-indigo-200 rounded-lg font-medium text-sm transition">👁️ Ver</a>
                                            <a href="{{ route('servicios.edit', $servicio) }}" class="px-4 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg font-medium text-sm transition">✏️ Editar</a>
                                            <form action="{{ route('servicios.destroy', $servicio) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este servicio?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-4 py-2 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg font-medium text-sm transition">🗑️ Eliminar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($servicios->hasPages())
                <div class="mt-8">
                    {{ $servicios->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-16 bg-white rounded-lg shadow-md">
                <div class="text-6xl mb-4">🏥</div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">No hay servicios registrados</h3>
                <p class="text-gray-600 mb-6">Comienza creando el primer servicio hospitalario</p>
                <a href="{{ route('servicios.create') }}" class="inline-block px-6 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition font-medium">
                    ➕ Crear Servicio
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
