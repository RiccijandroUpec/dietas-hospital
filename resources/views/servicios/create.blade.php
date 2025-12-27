@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">➕ Crear Nuevo Servicio</h1>
                <p class="text-gray-600 mt-1">Agrega un nuevo servicio hospitalario</p>
            </div>
            <a href="{{ route('servicios.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                ← Volver
            </a>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-8">
                <form action="{{ route('servicios.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre del Servicio</label>
                        <input 
                            type="text" 
                            name="nombre" 
                            value="{{ old('nombre') }}" 
                            placeholder="Ej: Cardiología, Neurocirugía, Pediatría..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition"
                            required
                        >
                        @error('nombre')
                            <div class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                <span>⚠️</span>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Descripción (opcional)</label>
                        <textarea 
                            name="descripcion" 
                            placeholder="Descripción del servicio, especialidades, etc..."
                            rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition"
                        >{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                <span>⚠️</span>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                    <div class="flex gap-3 pt-4 border-t border-gray-200">
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg font-medium transition-all duration-200 transform hover:scale-105">
                            ✓ Guardar Servicio
                        </button>
                        <a href="{{ route('servicios.index') }}" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium transition">
                            ✕ Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
