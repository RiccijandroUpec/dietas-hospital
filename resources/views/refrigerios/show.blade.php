@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <!-- Header con botón volver -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">🔍 Detalles del Refrigerio</h1>
                <p class="text-gray-600 mt-1">Información completa del refrigerio</p>
            </div>
            <a href="{{ route('refrigerios.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>

        <!-- Tarjeta principal -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header de la tarjeta -->
            <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-6 py-8">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-20 w-20 bg-white rounded-full flex items-center justify-center">
                        <span class="text-orange-600 font-bold text-3xl">{{ substr($refrigerio->nombre, 0, 1) }}</span>
                    </div>
                    <div class="ml-6">
                        <h2 class="text-2xl font-bold text-white">{{ $refrigerio->nombre }}</h2>
                        <p class="text-orange-100 mt-1">ID: {{ $refrigerio->id }}</p>
                    </div>
                </div>
            </div>

            <!-- Contenido de la tarjeta -->
            <div class="p-6">
                <!-- Descripción -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                        </svg>
                        Descripción
                    </h3>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-700">{{ $refrigerio->descripcion ?? 'Sin descripción' }}</p>
                    </div>
                </div>

                <!-- Información de registro -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Fecha de Creación
                        </h3>
                        <div class="p-4 bg-green-50 rounded-lg">
                            <p class="text-gray-700">{{ $refrigerio->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Última Actualización
                        </h3>
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <p class="text-gray-700">{{ $refrigerio->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            @if(auth()->check() && auth()->user()->role === 'admin')
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                    <a href="{{ route('refrigerios.edit', $refrigerio) }}" class="inline-flex items-center px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar
                    </a>
                    <form action="{{ route('refrigerios.destroy', $refrigerio) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este refrigerio?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Eliminar
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
