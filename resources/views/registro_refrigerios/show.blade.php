@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-4 md:mb-6">
            <div class="flex items-center justify-between mb-3">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">🥤 <span class="hidden sm:inline">Detalle de </span>Refrigerio</h1>
                <a href="{{ route('registro-refrigerios.index') }}" class="inline-flex items-center px-3 py-2 md:px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition text-sm md:text-base">
                    <span class="md:hidden">←</span>
                    <span class="hidden md:inline">← Volver</span>
                </a>
            </div>
            <p class="text-gray-600 text-sm md:text-base">Información completa del refrigerio registrado</p>
        </div>

        <!-- Tarjeta Principal -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-4 md:mb-6">
            <div class="p-4 md:p-6">
                <!-- Paciente -->
                <div class="mb-4 md:mb-6 pb-4 md:pb-6 border-b border-gray-200">
                    <h2 class="text-xs md:text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">👤 Paciente</h2>
                    <div class="flex items-start gap-3 md:gap-4">
                        <div class="h-10 w-10 md:h-12 md:w-12 bg-blue-100 rounded-full flex items-center justify-center text-base md:text-lg flex-shrink-0">👤</div>
                        <div class="min-w-0 flex-1">
                            <div class="text-lg md:text-xl font-bold text-gray-900 break-words">{{ optional($registroRefrigerio->paciente)->nombre }} {{ optional($registroRefrigerio->paciente)->apellido }}</div>
                            @if($registroRefrigerio->paciente)
                                <div class="text-xs md:text-sm text-gray-600 mt-1">
                                    <span class="text-gray-400">CI:</span> <span class="font-mono">{{ $registroRefrigerio->paciente->cedula }}</span>
                                </div>
                                <div class="text-xs md:text-sm text-gray-600 mt-1">
                                    <span class="text-gray-400">Condición:</span> 
                                    <span class="inline-block bg-blue-100 text-blue-800 rounded-full px-2 md:px-3 py-1 text-xs font-semibold mt-1">{{ ucfirst($registroRefrigerio->paciente->condicion ?? '—') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Refrigerios del registro (mismo paciente, fecha y momento) -->
                <div class="mb-4 md:mb-6 pb-4 md:pb-6 border-b border-gray-200">
                    <h2 class="text-xs md:text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">🥤 Refrigerios</h2>
                    @if(isset($refrigeriosDelMomento) && $refrigeriosDelMomento->count() > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($refrigeriosDelMomento as $ref)
                                <span class="px-2 md:px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-orange-100 text-orange-800" title="{{ $ref->descripcion }}">{{ $ref->nombre }}</span>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-2">{{ $refrigeriosDelMomento->count() }} refrigerio(s) en este momento.</p>
                    @else
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 md:p-4 text-orange-900 text-sm">No hay refrigerios registrados.</div>
                    @endif
                </div>

                <!-- Información General -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4 mb-4 md:mb-6 pb-4 md:pb-6 border-b border-gray-200">
                    <div>
                        <h3 class="text-xs md:text-sm font-semibold text-gray-600 mb-1 md:mb-2">📅 Fecha</h3>
                        <p class="text-sm md:text-base text-gray-900 font-semibold">{{ \Carbon\Carbon::parse($registroRefrigerio->fecha)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <h3 class="text-xs md:text-sm font-semibold text-gray-600 mb-1 md:mb-2">⏰ Momento</h3>
                        @php 
                            $momentos = [
                                'mañana' => ['Mañana', 'yellow'],
                                'manana' => ['Mañana', 'yellow'],
                                'tarde' => ['Tarde', 'orange'],
                                'noche' => ['Noche', 'indigo']
                            ];
                            $momento = $momentos[$registroRefrigerio->momento] ?? [ucfirst($registroRefrigerio->momento ?? '—'), 'gray'];
                        @endphp
                        <span class="inline-block bg-{{ $momento[1] }}-100 text-{{ $momento[1] }}-800 rounded-full px-2 md:px-3 py-1 text-xs font-semibold">{{ $momento[0] }}</span>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <h3 class="text-xs md:text-sm font-semibold text-gray-600 mb-1 md:mb-2">🕐 Hace</h3>
                        <p class="text-sm md:text-base text-gray-900 font-semibold">{{ \Carbon\Carbon::parse($registroRefrigerio->fecha)->diffForHumans() }}</p>
                    </div>
                </div>

                <!-- Observación -->
                @if($registroRefrigerio->observacion)
                    <div class="mb-4 md:mb-6 pb-4 md:pb-6 border-b border-gray-200">
                        <h2 class="text-xs md:text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">📝 Observación</h2>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 md:p-4 text-sm md:text-base text-gray-900 break-words">{{ $registroRefrigerio->observacion }}</div>
                    </div>
                @endif

                <!-- Auditoría -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                    <div class="bg-gray-50 rounded-lg p-3 md:p-4">
                        <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">👤 Registrado por</h3>
                        <p class="text-sm md:text-base text-gray-900 font-semibold break-words">{{ optional($registroRefrigerio->createdBy)->name ?? '—' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $registroRefrigerio->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 md:p-4">
                        <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">✏️ Actualizado por</h3>
                        <p class="text-sm md:text-base text-gray-900 font-semibold break-words">{{ optional($registroRefrigerio->updatedBy)->name ?? '—' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $registroRefrigerio->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="flex flex-col md:flex-row gap-2">
            @if(auth()->user()->role !== 'usuario')
                <a href="{{ route('registro-refrigerios.edit', $registroRefrigerio) }}" class="px-4 md:px-6 py-2 md:py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition text-center text-sm md:text-base">
                    ✏️ <span class="hidden sm:inline">Editar Registro</span><span class="sm:hidden">Editar</span>
                </a>
            @endif
            @if(auth()->user()->role === 'administrador')
                <form action="{{ route('registro-refrigerios.destroy', $registroRefrigerio) }}" method="POST" onsubmit="return confirm('¿Eliminar este registro?')">
                    @csrf @method('DELETE')
                    <button type="submit" style="color: white !important;" class="w-full md:w-auto px-4 md:px-6 py-2 md:py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition text-sm md:text-base">🗑️ Eliminar</button>
                </form>
            @endif
            <a href="{{ route('registro-refrigerios.index') }}" class="px-4 md:px-6 py-2 md:py-2.5 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium transition text-center text-sm md:text-base">← Volver</a>
        </div>
    </div>
</div>
@endsection