@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">🥤 Detalle de Registro de Refrigerio</h1>
                <p class="text-gray-600 mt-1">Información completa del refrigerio registrado</p>
            </div>
            <a href="{{ route('registro-refrigerios.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
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
                            <div class="text-xl font-bold text-gray-900">{{ optional($registroRefrigerio->paciente)->nombre }} {{ optional($registroRefrigerio->paciente)->apellido }}</div>
                            @if($registroRefrigerio->paciente)
                                <div class="text-sm text-gray-600 mt-1">
                                    <span class="text-gray-400">Cédula:</span> <span class="font-mono">{{ $registroRefrigerio->paciente->cedula }}</span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    <span class="text-gray-400">Condición:</span> 
                                    <span class="inline-block bg-blue-100 text-blue-800 rounded-full px-3 py-1 text-xs font-semibold mt-1">{{ ucfirst($registroRefrigerio->paciente->condicion ?? '—') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Refrigerios del registro (mismo paciente, fecha y momento) -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <h2 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">🥤 Refrigerios del registro</h2>
                    @if(isset($refrigeriosDelMomento) && $refrigeriosDelMomento->count() > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($refrigeriosDelMomento as $ref)
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-orange-100 text-orange-800" title="{{ $ref->descripcion }}">{{ $ref->nombre }}</span>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Paciente con {{ $refrigeriosDelMomento->count() }} refrigerio(s) en este momento.</p>
                    @else
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 text-orange-900">No hay otros refrigerios registrados en este momento.</div>
                    @endif
                </div>

                <!-- Información General -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 pb-6 border-b border-gray-200">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">📅 Fecha</h3>
                        <p class="text-gray-900 font-semibold">{{ \Carbon\Carbon::parse($registroRefrigerio->fecha)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">⏰ Momento</h3>
                        @php 
                            $momentos = [
                                'mañana' => ['Mañana', 'yellow'],
                                'manana' => ['Mañana', 'yellow'],
                                'tarde' => ['Tarde', 'orange'],
                                'noche' => ['Noche', 'indigo']
                            ];
                            $momento = $momentos[$registroRefrigerio->momento] ?? [ucfirst($registroRefrigerio->momento ?? '—'), 'gray'];
                        @endphp
                        <span class="inline-block bg-{{ $momento[1] }}-100 text-{{ $momento[1] }}-800 rounded-full px-3 py-1 text-xs font-semibold">{{ $momento[0] }}</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">🕐 Hace</h3>
                        <p class="text-gray-900 font-semibold">{{ \Carbon\Carbon::parse($registroRefrigerio->fecha)->diffForHumans() }}</p>
                    </div>
                </div>

                <!-- Observación -->
                @if($registroRefrigerio->observacion)
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <h2 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">📝 Observación</h2>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-gray-900">{{ $registroRefrigerio->observacion }}</div>
                    </div>
                @endif

                <!-- Auditoría -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">👤 Registrado por</h3>
                        <p class="text-gray-900 font-semibold">{{ optional($registroRefrigerio->createdBy)->name ?? '—' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $registroRefrigerio->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">✏️ Actualizado por</h3>
                        <p class="text-gray-900 font-semibold">{{ optional($registroRefrigerio->updatedBy)->name ?? '—' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $registroRefrigerio->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="flex gap-2">
            @if(auth()->user()->role !== 'usuario')
                <a href="{{ route('registro-refrigerios.edit', $registroRefrigerio) }}" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">✏️ Editar Registro</a>
                <form action="{{ route('registro-refrigerios.destroy', $registroRefrigerio) }}" method="POST" onsubmit="return confirm('¿Eliminar este registro?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">🗑️ Eliminar</button>
                </form>
            @endif
            <a href="{{ route('registro-refrigerios.index') }}" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium transition">← Volver</a>
        </div>
    </div>
</div>
@endsection