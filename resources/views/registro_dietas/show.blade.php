@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">📋 Detalle de Registro de Dieta</h1>
                <p class="text-gray-600 mt-1">Información completa del registro dietético</p>
            </div>
            <a href="{{ route('registro-dietas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                ← Volver
            </a>
        </div>

        <!-- Tarjeta Principal -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="p-6">
                <!-- Paciente -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <h2 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">👤 Paciente</h2>
                    <div class="flex items-start gap-4">
                        <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center text-lg">👤</div>
                        <div>
                            <div class="text-xl font-bold text-gray-900">{{ optional($registro->paciente)->nombre }} {{ optional($registro->paciente)->apellido }}</div>
                            @if($registro->paciente)
                                <div class="text-sm text-gray-600 mt-1">
                                    <span class="text-gray-400">Cédula:</span> <span class="font-mono">{{ $registro->paciente->cedula }}</span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    <span class="text-gray-400">Estado:</span> <span class="inline-block bg-{{ $registro->paciente->estado === 'hospitalizado' ? 'green' : 'yellow' }}-100 text-{{ $registro->paciente->estado === 'hospitalizado' ? 'green' : 'yellow' }}-800 rounded-full px-3 py-1 text-xs font-semibold mt-1">{{ ucfirst($registro->paciente->estado) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Dietas Asignadas -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <h2 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">🥗 Dietas Asignadas</h2>
                    <div class="flex flex-wrap gap-2">
                        @if($registro->dietas->count() > 0)
                            @foreach($registro->dietas as $dieta)
                                <span class="inline-block bg-purple-100 text-purple-800 rounded-full px-4 py-2 text-sm font-semibold">{{ $dieta->nombre }}</span>
                            @endforeach
                        @else
                            <span class="text-gray-500 italic">No hay dietas asignadas</span>
                        @endif
                    </div>
                </div>

                <!-- Información General -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 pb-6 border-b border-gray-200">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">🍽️ Tipo de Comida</h3>
                        @php 
                            $tipos = [
                                'desayuno' => ['Desayuno', 'yellow'],
                                'almuerzo' => ['Almuerzo', 'orange'],
                                'merienda' => ['Merienda', 'pink']
                            ];
                            $tipo = $tipos[$registro->tipo_comida] ?? ['—', 'gray'];
                        @endphp
                        <span class="inline-block bg-{{ $tipo[1] }}-100 text-{{ $tipo[1] }}-800 rounded-full px-3 py-1 text-xs font-semibold">{{ $tipo[0] }}</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">📅 Fecha</h3>
                        <p class="text-gray-900 font-semibold">{{ \Carbon\Carbon::parse($registro->fecha)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">⏰ Hace</h3>
                        <p class="text-gray-900 font-semibold">{{ \Carbon\Carbon::parse($registro->fecha)->diffForHumans() }}</p>
                    </div>
                </div>

                <!-- Observaciones -->
                @if($registro->observaciones)
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <h2 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">📝 Observaciones</h2>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-gray-900">{{ $registro->observaciones }}</div>
                    </div>
                @endif

                <!-- Auditoría -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">👤 Registrado por</h3>
                        <p class="text-gray-900 font-semibold">{{ optional($registro->createdBy)->name ?? '—' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $registro->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">✏️ Actualizado por</h3>
                        <p class="text-gray-900 font-semibold">{{ optional($registro->updatedBy)->name ?? '—' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $registro->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="flex gap-2">
            @if(auth()->user()->role !== 'usuario')
                <a href="{{ route('registro-dietas.edit', $registro) }}" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">✏️ Editar Registro</a>
                <form action="{{ route('registro-dietas.destroy', $registro) }}" method="POST" onsubmit="return confirm('¿Eliminar este registro?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">🗑️ Eliminar</button>
                </form>
            @endif
            <a href="{{ route('registro-dietas.index') }}" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium transition">← Volver</a>
        </div>
    </div>
</div>
@endsection
