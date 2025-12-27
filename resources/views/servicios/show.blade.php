@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">🏥 Detalle del Servicio</h1>
                <p class="text-gray-600 mt-1">Información completa del servicio hospitalario</p>
            </div>
            <a href="{{ route('servicios.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                ← Volver
            </a>
        </div>

        <!-- Tarjeta Principal -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-sky-50 to-sky-100 px-6 py-8 border-b border-sky-200">
                <div class="flex items-start gap-4">
                    <div class="h-16 w-16 bg-sky-200 rounded-lg flex items-center justify-center text-3xl">🏥</div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $servicio->nombre }}</h2>
                        <p class="text-sm text-gray-600 mt-1">ID: {{ $servicio->id }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Descripción -->
                @if($servicio->descripcion)
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">📝 Descripción</h3>
                        <p class="text-gray-900 leading-relaxed whitespace-pre-wrap">{{ $servicio->descripcion }}</p>
                    </div>
                @endif

                <!-- Estadísticas -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 pb-6 border-b border-gray-200">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-2">👥 Pacientes</h3>
                        <p class="text-2xl font-bold text-blue-900">{{ $servicio->pacientes()->count() }}</p>
                        <p class="text-xs text-blue-600 mt-1">Pacientes en este servicio</p>
                    </div>
                    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                        <h3 class="text-xs font-semibold text-emerald-600 uppercase tracking-wide mb-2">🛏️ Camas</h3>
                        <p class="text-2xl font-bold text-emerald-900">{{ $servicio->camas()->count() }}</p>
                        <p class="text-xs text-emerald-600 mt-1">Camas asignadas</p>
                    </div>
                    <div class="bg-sky-50 border border-sky-200 rounded-lg p-4">
                        <h3 class="text-xs font-semibold text-sky-600 uppercase tracking-wide mb-2">📅 Registrado</h3>
                        <p class="text-lg font-bold text-sky-900">{{ $servicio->created_at->format('d/m/Y') }}</p>
                        <p class="text-xs text-sky-600 mt-1">{{ $servicio->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                <!-- Información General -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">🆔 ID del Servicio</h3>
                        <p class="text-gray-900 font-semibold font-mono text-lg">#{{ $servicio->id }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">⏰ Última Actualización</h3>
                        <p class="text-gray-900 font-semibold">{{ $servicio->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="flex gap-2">
            <a href="{{ route('servicios.edit', $servicio) }}" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">✏️ Editar Servicio</a>
            <form action="{{ route('servicios.destroy', $servicio) }}" method="POST" onsubmit="return confirm('¿Eliminar este servicio?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">🗑️ Eliminar</button>
            </form>
            <a href="{{ route('servicios.index') }}" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium transition">← Volver</a>
        </div>
    </div>
</div>
@endsection
