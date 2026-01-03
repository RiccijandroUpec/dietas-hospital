@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-4 md:mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h1 class="text-xl md:text-3xl font-bold text-gray-900">🏥 <span class="hidden sm:inline">Servicios Hospitalarios</span><span class="sm:hidden">Servicios</span></h1>
                <p class="text-gray-600 text-xs md:text-base">Gestión de servicios y departamentos</p>
            </div>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('servicios.create') }}" class="inline-flex items-center justify-center px-3 md:px-6 py-2 bg-gradient-to-r from-sky-600 to-sky-700 hover:from-sky-700 hover:to-sky-800 text-white rounded-lg shadow-md transition-all duration-200 transform hover:scale-105" title="Crear Servicio">
                    <span class="md:hidden text-xl">➕</span>
                    <span class="hidden md:flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Crear Servicio
                    </span>
                </a>
            @endif
        </div>

        <!-- Mensajes -->
        @if(session('success'))
            <div class="mb-4 md:mb-6 p-3 md:p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-start text-sm md:text-base">
                <span class="mr-2 md:mr-3 text-lg md:text-xl">✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Tabla de Servicios -->
        @if($servicios->count() > 0)
            <!-- Vista Desktop (Tabla) -->
            <div class="hidden md:block bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-sky-50 to-sky-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">🏥 Nombre del Servicio</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Descripción</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">ID</th>
                                @if(auth()->user()->role === 'admin')
                                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Acciones</th>
                                @endif
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
                                            @if(auth()->user()->role === 'admin')
                                                <a href="{{ route('servicios.edit', $servicio) }}" class="px-4 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg font-medium text-sm transition">✏️ Editar</a>
                                                <form action="{{ route('servicios.destroy', $servicio) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este servicio?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="color: white !important;" class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded-lg font-medium text-sm transition">🗑️ Eliminar</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Vista Móvil (Tarjetas) -->
            <div class="md:hidden space-y-3">
                @foreach($servicios as $servicio)
                    <div class="bg-white rounded-lg shadow-md p-3 border-l-4 border-sky-500">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-bold text-gray-900 break-words">🏥 {{ $servicio->nombre }}</h3>
                                <span class="inline-block bg-sky-100 text-sky-800 rounded-full px-2 py-0.5 text-xs font-semibold mt-1">#{{ $servicio->id }}</span>
                            </div>
                        </div>
                        
                        @if($servicio->descripcion)
                            <div class="mb-3 pb-3 border-b border-gray-100">
                                <p class="text-xs text-gray-600 break-words">{{ Str::limit($servicio->descripcion, 100) }}</p>
                            </div>
                        @endif

                        <div class="flex justify-center gap-2">
                            <a href="{{ route('servicios.show', $servicio) }}" class="flex items-center justify-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-lg rounded-lg transition" title="Ver">👁️</a>
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('servicios.edit', $servicio) }}" class="flex items-center justify-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-lg rounded-lg transition" title="Editar">✏️</a>
                                <form action="{{ route('servicios.destroy', $servicio) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar este servicio?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="color: white !important;" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-lg rounded-lg transition" title="Eliminar">🗑️</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if($servicios->hasPages())
                <div class="mt-4 md:mt-8">
                    {{ $servicios->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-8 md:py-16 bg-white rounded-lg shadow-md">
                <div class="text-4xl md:text-6xl mb-3 md:mb-4">🏥</div>
                <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-2">No hay servicios registrados</h3>
                <p class="text-xs md:text-base text-gray-600 mb-4 md:mb-6">Comienza creando el primer servicio hospitalario</p>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('servicios.create') }}" class="inline-block px-4 md:px-6 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition font-medium text-sm md:text-base">
                        ➕ Crear Servicio
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
