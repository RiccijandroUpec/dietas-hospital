@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">👤 Detalle del Paciente</h1>
                <p class="text-gray-600 mt-1">Información completa del paciente</p>
            </div>
            <a href="{{ route('pacientes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition">
                ← Volver
            </a>
        </div>

        <!-- Tarjeta Principal -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="p-6">
                <!-- Información Personal -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <h2 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">📋 Información Personal</h2>
                    <div class="flex items-start gap-4 mb-4">
                        <div class="h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center text-2xl">👤</div>
                        <div class="flex-1">
                            <div class="text-2xl font-bold text-gray-900">{{ $paciente->nombre }} {{ $paciente->apellido }}</div>
                            <div class="flex gap-4 mt-2">
                                <div>
                                    <span class="text-sm text-gray-500">Cédula:</span>
                                    <span class="font-mono text-gray-900 font-semibold ml-1">{{ $paciente->cedula }}</span>
                                </div>
                                @if($paciente->edad)
                                    <div>
                                        <span class="text-sm text-gray-500">Edad:</span>
                                        <span class="text-gray-900 font-semibold ml-1">{{ $paciente->edad }} años</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div>
                            @if($paciente->estado === 'hospitalizado')
                                <span class="inline-block bg-blue-100 text-blue-800 rounded-full px-4 py-2 text-sm font-semibold">🏥 Hospitalizado</span>
                            @else
                                <span class="inline-block bg-green-100 text-green-800 rounded-full px-4 py-2 text-sm font-semibold">✓ Alta</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Condición Médica -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <h2 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">🩺 Condición Médica</h2>
                    @php
                        $condiciones = $paciente->condicion ? explode(',', $paciente->condicion) : [];
                        $labels = [
                            'normal' => ['Normal', 'bg-green-100 text-green-800'],
                            'diabetico' => ['Diabético', 'bg-red-100 text-red-800'],
                            'hiposodico' => ['Hiposódico', 'bg-orange-100 text-orange-800'],
                        ];
                    @endphp
                    @if(count($condiciones) > 0 && $condiciones[0] !== '')
                        <div class="flex flex-wrap gap-2">
                            @foreach($condiciones as $cond)
                                @php
                                    $c = trim($cond);
                                    $label = $labels[$c] ?? [$c, 'bg-gray-100 text-gray-800'];
                                @endphp
                                <span class="inline-block {{ $label[1] }} rounded-full px-4 py-2 text-sm font-semibold">{{ $label[0] }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">Sin condiciones médicas especiales registradas</p>
                    @endif
                </div>

                <!-- Ubicación -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <h2 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">📍 Ubicación Hospitalaria</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-sky-50 border border-sky-200 rounded-lg p-4">
                            <h3 class="text-xs font-semibold text-sky-600 uppercase mb-2">🏢 Servicio</h3>
                            <p class="text-lg font-bold text-sky-900">{{ optional($paciente->servicio)->nombre ?? 'Sin asignar' }}</p>
                        </div>
                        <div class="bg-violet-50 border border-violet-200 rounded-lg p-4">
                            <h3 class="text-xs font-semibold text-violet-600 uppercase mb-2">🛏️ Cama</h3>
                            <p class="text-lg font-bold text-violet-900 font-mono">{{ optional($paciente->cama)->codigo ?? 'Sin asignar' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Auditoría -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">👤 Registrado por</h3>
                        <p class="text-gray-900 font-semibold">{{ optional($paciente->createdBy)->name ?? '—' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $paciente->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">✏️ Actualizado por</h3>
                        <p class="text-gray-900 font-semibold">{{ optional($paciente->updatedBy)->name ?? '—' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $paciente->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="flex gap-2">
            @if(auth()->user()->role !== 'usuario')
                <a href="{{ route('pacientes.edit', $paciente) }}" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">✏️ Editar Paciente</a>
                <a href="{{ route('registro-dietas.create') }}?paciente_id={{ $paciente->id }}" class="px-6 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-lg font-medium transition">🥗 Registrar Dieta</a>
                <a href="{{ route('registro-refrigerios.create') }}?paciente_id={{ $paciente->id }}" class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-medium transition">🥤 Registrar Refrigerio</a>
                <form action="{{ route('pacientes.destroy', $paciente) }}" method="POST" onsubmit="return confirm('¿Eliminar este paciente?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">🗑️ Eliminar</button>
                </form>
            @endif
            <a href="{{ route('pacientes.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-100 transition">← Volver</a>
        </div>
    </div>
</div>
@endsection
