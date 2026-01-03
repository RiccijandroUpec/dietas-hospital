@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-3 md:mb-6">
            <div class="flex items-center justify-between mb-2">
                <h1 class="text-xl md:text-3xl font-bold text-gray-900">👤 <span class="hidden sm:inline">Detalle del </span>Paciente</h1>
                <a href="{{ route('pacientes.index') }}" class="inline-flex items-center px-2.5 py-1.5 md:px-4 md:py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition text-sm md:text-base">
                    <span class="md:hidden text-lg">←</span>
                    <span class="hidden md:inline">← Volver</span>
                </a>
            </div>
            <p class="text-gray-600 text-xs md:text-base">Información completa del paciente</p>
        </div>

        <!-- Tarjeta Principal -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-3 md:mb-6">
            <div class="p-3 md:p-6">
                <!-- Información Personal -->
                <div class="mb-3 md:mb-6 pb-3 md:pb-6 border-b border-gray-200">
                    <h2 class="text-xs md:text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">📋 Info</h2>
                    <div class="flex flex-col md:flex-row md:items-start gap-2 md:gap-4 mb-3 md:mb-4">
                        <div class="flex items-start gap-2 md:gap-4 flex-1">
                            <div class="h-10 w-10 md:h-16 md:w-16 bg-blue-100 rounded-full flex items-center justify-center text-lg md:text-2xl flex-shrink-0">👤</div>
                            <div class="flex-1 min-w-0">
                                <div class="text-base md:text-2xl font-bold text-gray-900 break-words leading-tight">{{ $paciente->nombre }} {{ $paciente->apellido }}</div>
                                <div class="flex flex-col sm:flex-row sm:gap-3 mt-1 md:mt-2 space-y-0.5 sm:space-y-0">
                                    <div>
                                        <span class="text-xs text-gray-500">CI:</span>
                                        <span class="font-mono text-xs md:text-base text-gray-900 font-semibold ml-1">{{ $paciente->cedula }}</span>
                                    </div>
                                    @if($paciente->edad)
                                        <div>
                                            <span class="text-xs text-gray-500">Edad:</span>
                                            <span class="text-xs md:text-base text-gray-900 font-semibold ml-1">{{ $paciente->edad }} años</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-start md:justify-end">
                            @if($paciente->estado === 'hospitalizado')
                                <span class="inline-block bg-blue-100 text-blue-800 rounded-full px-2.5 md:px-4 py-1 md:py-2 text-xs md:text-sm font-semibold">🏥 Hospitalizado</span>
                            @else
                                <span class="inline-block bg-green-100 text-green-800 rounded-full px-2.5 md:px-4 py-1 md:py-2 text-xs md:text-sm font-semibold">✓ Alta</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Condición Médica -->
                <div class="mb-3 md:mb-6 pb-3 md:pb-6 border-b border-gray-200">
                    <h2 class="text-xs md:text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">🩺 Condición</h2>
                    @php
                        $condiciones = $paciente->condicion ? explode(',', $paciente->condicion) : [];
                        $labels = [
                            'normal' => ['Normal', 'bg-green-100 text-green-800'],
                            'diabetico' => ['Diabético', 'bg-red-100 text-red-800'],
                            'hiposodico' => ['Hiposódico', 'bg-orange-100 text-orange-800'],
                        ];
                    @endphp
                    @if(count($condiciones) > 0 && $condiciones[0] !== '')
                        <div class="flex flex-wrap gap-1.5 md:gap-2">
                            @foreach($condiciones as $cond)
                                @php
                                    $c = trim($cond);
                                    $label = $labels[$c] ?? [$c, 'bg-gray-100 text-gray-800'];
                                @endphp
                                <span class="inline-block {{ $label[1] }} rounded-full px-2.5 md:px-4 py-1 md:py-2 text-xs md:text-sm font-semibold">{{ $label[0] }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic text-xs md:text-base">Sin condiciones especiales</p>
                    @endif
                </div>

                <!-- Ubicación -->
                <div class="mb-3 md:mb-6 pb-3 md:pb-6 border-b border-gray-200">
                    <h2 class="text-xs md:text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">📍 Ubicación</h2>
                    <div class="grid grid-cols-2 gap-2 md:gap-4">
                        <div class="bg-sky-50 border border-sky-200 rounded-lg p-2 md:p-4">
                            <h3 class="text-xs font-semibold text-sky-600 uppercase mb-1">🏢 Servicio</h3>
                            <p class="text-sm md:text-lg font-bold text-sky-900 break-words leading-tight">{{ optional($paciente->servicio)->nombre ?? 'Sin asignar' }}</p>
                        </div>
                        <div class="bg-violet-50 border border-violet-200 rounded-lg p-2 md:p-4">
                            <h3 class="text-xs font-semibold text-violet-600 uppercase mb-1">🛏️ Cama</h3>
                            <p class="text-sm md:text-lg font-bold text-violet-900 font-mono">{{ optional($paciente->cama)->codigo ?? 'Sin asignar' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Auditoría -->
                <div class="grid grid-cols-2 gap-2 md:gap-4">
                    <div class="bg-gray-50 rounded-lg p-2 md:p-4">
                        <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">👤 Creado</h3>
                        <p class="text-xs md:text-base text-gray-900 font-semibold break-words leading-tight">{{ optional($paciente->createdBy)->name ?? '—' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $paciente->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-2 md:p-4">
                        <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">✏️ Actualizado</h3>
                        <p class="text-xs md:text-base text-gray-900 font-semibold break-words leading-tight">{{ optional($paciente->updatedBy)->name ?? '—' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $paciente->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="grid grid-cols-2 md:flex md:flex-row gap-2">
            @if(auth()->user()->role !== 'usuario')
                <a href="{{ route('pacientes.edit', $paciente) }}" class="flex items-center justify-center px-3 md:px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition" title="Editar Paciente">
                    <span class="block md:hidden text-2xl">✏️</span>
                    <span class="hidden md:block">✏️ Editar Paciente</span>
                </a>
                <a href="{{ route('registro-dietas.create') }}?paciente_id={{ $paciente->id }}" class="flex items-center justify-center px-3 md:px-6 py-2.5 bg-pink-600 hover:bg-pink-700 text-white rounded-lg font-medium transition" title="Registrar Dieta">
                    <span class="block md:hidden text-2xl">🥗</span>
                    <span class="hidden md:block">🥗 Registrar Dieta</span>
                </a>
                <a href="{{ route('registro-refrigerios.create') }}?paciente_id={{ $paciente->id }}" class="flex items-center justify-center px-3 md:px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-medium transition" title="Registrar Refrigerio">
                    <span class="block md:hidden text-2xl">🥤</span>
                    <span class="hidden md:block">🥤 Registrar Refrigerio</span>
                </a>
                <form action="{{ route('pacientes.destroy', $paciente) }}" method="POST" onsubmit="return confirm('¿Eliminar este paciente?')">
                    @csrf @method('DELETE')
                    <button type="submit" style="color: white !important;" class="w-full flex items-center justify-center px-3 md:px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition" title="Eliminar">
                        <span class="block md:hidden text-2xl">🗑️</span>
                        <span class="hidden md:block">🗑️ Eliminar</span>
                    </button>
                </form>
            @endif
            <a href="{{ route('pacientes.index') }}" class="col-span-2 md:col-span-1 flex items-center justify-center px-3 md:px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-100 transition" title="Volver">
                <span class="block md:hidden text-2xl">←</span>
                <span class="hidden md:block">← Volver</span>
            </a>
        </div>
    </div>
</div>
@endsection
