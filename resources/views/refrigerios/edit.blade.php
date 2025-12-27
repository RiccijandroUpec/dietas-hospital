@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <!-- Header con botón volver -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">✏️ Editar Refrigerio</h1>
                <p class="text-gray-600 mt-1">Actualiza la información del refrigerio</p>
            </div>
            <a href="{{ route('refrigerios.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>

        <!-- Información del refrigerio -->
        <div class="mb-6 bg-gradient-to-r from-orange-50 to-yellow-50 rounded-lg p-6 border border-orange-100">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-16 w-16 bg-orange-600 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-2xl">{{ substr($refrigerio->nombre, 0, 1) }}</span>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $refrigerio->nombre }}</h3>
                    <p class="text-sm text-gray-600">ID: {{ $refrigerio->id }}</p>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <form action="{{ route('refrigerios.update', $refrigerio) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nombre -->
                    <div class="mb-6">
                        <label for="nombre" class="block text-sm font-semibold text-gray-700 mb-2">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                Nombre del Refrigerio
                            </span>
                        </label>
                        <input 
                            type="text" 
                            id="nombre" 
                            name="nombre" 
                            value="{{ old('nombre', $refrigerio->nombre) }}" 
                            required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                            placeholder="Ej: Jugo de naranja, Galletas, etc."
                        />
                        @error('nombre')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div class="mb-6">
                        <label for="descripcion" class="block text-sm font-semibold text-gray-700 mb-2">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                </svg>
                                Descripción (Opcional)
                            </span>
                        </label>
                        <textarea 
                            id="descripcion" 
                            name="descripcion" 
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200"
                            placeholder="Describe las características del refrigerio..."
                        >{{ old('descripcion', $refrigerio->descripcion) }}</textarea>
                        @error('descripcion')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">Proporciona detalles adicionales sobre el refrigerio</p>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <a href="{{ route('refrigerios.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 text-white rounded-lg shadow-md transition-all duration-200 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Actualizar Refrigerio
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
