@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">✏️ Editar Servicio</h1>
                <p class="text-gray-600 mt-1">Actualiza la información del servicio</p>
            </div>
            <a href="{{ route('servicios.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                ← Volver
            </a>
        </div>

        <!-- Información del Servicio -->
        <div class="bg-sky-50 border border-sky-200 rounded-lg p-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="text-2xl">🏢</div>
                <div>
                    <h3 class="font-semibold text-gray-900">{{ $servicio->nombre }}</h3>
                    <p class="text-sm text-gray-600">ID: {{ $servicio->id }}</p>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-8">
                <form action="{{ route('servicios.update', $servicio) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre del Servicio</label>
                        <input 
                            type="text" 
                            name="nombre" 
                            value="{{ old('nombre', $servicio->nombre) }}" 
                            placeholder="Ej: Cardiología, Neurocirugía, Pediatría..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
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
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Prefijo para Códigos de Cama</label>
                        <input 
                            type="text" 
                            name="prefijo" 
                            value="{{ old('prefijo', $servicio->prefijo) }}" 
                            placeholder="Ej: CE, CARD, NEU (solo mayúsculas y números)"
                            maxlength="10"
                            pattern="[A-Z0-9]+"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition uppercase"
                            style="text-transform: uppercase;"
                            required
                        >
                        <p class="text-sm text-gray-500 mt-1">Este prefijo se usa para generar los códigos de las camas (ej: CE1, CE2, CE3...)</p>
                        @error('prefijo')
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
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        >{{ old('descripcion', $servicio->descripcion) }}</textarea>
                        @error('descripcion')
                            <div class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                <span>⚠️</span>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                    <div class="flex gap-3 pt-4 border-t border-gray-200">
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-medium transition-all duration-200 transform hover:scale-105">
                            ✓ Actualizar Servicio
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
