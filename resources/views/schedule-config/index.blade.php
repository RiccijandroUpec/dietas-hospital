@extends('layouts.app')

@section('content')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ⏰ Configuración de Horarios de Registro
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-md flex items-start">
                    <span class="mr-3">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p class="text-gray-600 mb-6">
                        Define los horarios permitidos para registrar desayunos, almuerzos, meriendas y refrigerios.
                        Los usuarios no podrán registrar fuera de estos horarios.
                    </p>

                    <!-- Horarios actuales -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach(['desayuno', 'almuerzo', 'merienda'] as $meal)
                            @php
                                $schedule = $schedules[$meal] ?? null;
                                $emoji = [
                                    'desayuno' => '🌅',
                                    'almuerzo' => '🍽️',
                                    'merienda' => '☕',
                                ][$meal];
                            @endphp
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 border border-blue-200">
                                <div class="flex items-start justify-between mb-4">
                                    <h3 class="text-lg font-bold text-gray-800">
                                        {{ $emoji }} {{ ucfirst($meal) }}
                                    </h3>
                                    @if($schedule)
                                        <form action="{{ route('schedule-config.toggle-out-of-schedule') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="meal_type" value="{{ $meal }}">
                                            <button type="submit" 
                                                class="px-3 py-1 text-xs rounded-full font-medium transition {{ $schedule->allow_out_of_schedule ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}"
                                                title="{{ $schedule->allow_out_of_schedule ? 'Deshabilitar registro fuera de horario' : 'Habilitar registro fuera de horario' }}">
                                                {{ $schedule->allow_out_of_schedule ? '✓ Fuera de horario habilitado' : '⏸ Fuera de horario bloqueado' }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                @if($schedule)
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Inicio:</span>
                                            <span class="font-mono font-bold text-blue-600">{{ $schedule->start_time->format('H:i') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Fin:</span>
                                            <span class="font-mono font-bold text-blue-600">{{ $schedule->end_time->format('H:i') }}</span>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-gray-500 italic">No configurado</p>
                                @endif
                            </div>
                        @endforeach

                        <!-- Refrigerio Mañana -->
                        @php
                            $refrigerio_mañana = $schedules['refrigerio_mañana'] ?? null;
                        @endphp
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-6 border border-orange-200">
                            <div class="flex items-start justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-800">
                                    🍊 Refrigerio Mañana
                                </h3>
                                @if($refrigerio_mañana)
                                    <form action="{{ route('schedule-config.toggle-out-of-schedule') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="meal_type" value="refrigerio_mañana">
                                        <button type="submit" 
                                            class="px-3 py-1 text-xs rounded-full font-medium transition {{ $refrigerio_mañana->allow_out_of_schedule ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}"
                                            title="{{ $refrigerio_mañana->allow_out_of_schedule ? 'Deshabilitar registro fuera de horario' : 'Habilitar registro fuera de horario' }}">
                                            {{ $refrigerio_mañana->allow_out_of_schedule ? '✓ Habilitado' : '⏸ Bloqueado' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                            @if($refrigerio_mañana)
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Inicio:</span>
                                        <span class="font-mono font-bold text-orange-600">{{ $refrigerio_mañana->start_time->format('H:i') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Fin:</span>
                                        <span class="font-mono font-bold text-orange-600">{{ $refrigerio_mañana->end_time->format('H:i') }}</span>
                                    </div>
                                </div>
                            @else
                                <p class="text-gray-500 italic">No configurado</p>
                            @endif
                        </div>

                        <!-- Refrigerio Tarde -->
                        @php
                            $refrigerio_tarde = $schedules['refrigerio_tarde'] ?? null;
                        @endphp
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-6 border border-yellow-200">
                            <div class="flex items-start justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-800">
                                    🍋 Refrigerio Tarde
                                </h3>
                                @if($refrigerio_tarde)
                                    <form action="{{ route('schedule-config.toggle-out-of-schedule') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="meal_type" value="refrigerio_tarde">
                                        <button type="submit" 
                                            class="px-3 py-1 text-xs rounded-full font-medium transition {{ $refrigerio_tarde->allow_out_of_schedule ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}"
                                            title="{{ $refrigerio_tarde->allow_out_of_schedule ? 'Deshabilitar registro fuera de horario' : 'Habilitar registro fuera de horario' }}">
                                            {{ $refrigerio_tarde->allow_out_of_schedule ? '✓ Habilitado' : '⏸ Bloqueado' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                            @if($refrigerio_tarde)
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Inicio:</span>
                                        <span class="font-mono font-bold text-yellow-600">{{ $refrigerio_tarde->start_time->format('H:i') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Fin:</span>
                                        <span class="font-mono font-bold text-yellow-600">{{ $refrigerio_tarde->end_time->format('H:i') }}</span>
                                    </div>
                                </div>
                            @else
                                <p class="text-gray-500 italic">No configurado</p>
                            @endif
                        </div>
                    </div>

                    <!-- Botón editar -->
                    <div class="mt-8">
                        <a href="{{ route('schedule-config.edit') }}" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                            ✏️ Editar Horarios
                        </a>
                    </div>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h4 class="font-semibold text-blue-900 mb-2">ℹ️ Información importante:</h4>
                <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside">
                    <li>Los usuarios verán un mensaje si intentan registrar fuera del horario permitido.</li>
                    <li>El sistema usa la hora actual del servidor.</li>
                    <li>Los horarios se validan automáticamente al crear registros.</li>
                    <li><strong>Nuevo:</strong> Puedes habilitar el registro fuera de horario usando los botones en cada tipo de comida.</li>
                    <li>Cuando está habilitado (✓ Habilitado), se permite registrar en cualquier momento del día.</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
@endsection
