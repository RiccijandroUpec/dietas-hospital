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
                        @foreach(['desayuno', 'almuerzo', 'merienda', 'refrigerio'] as $meal)
                            @php
                                $schedule = $schedules[$meal] ?? null;
                                $emoji = [
                                    'desayuno' => '🌅',
                                    'almuerzo' => '🍽️',
                                    'merienda' => '☕',
                                    'refrigerio' => '🍊'
                                ][$meal];
                            @endphp
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 border border-blue-200">
                                <h3 class="text-lg font-bold text-gray-800 mb-4">
                                    {{ $emoji }} {{ ucfirst($meal) }}
                                </h3>
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
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
@endsection
