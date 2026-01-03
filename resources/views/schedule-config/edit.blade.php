@extends('layouts.app')

@section('content')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ✏️ Editar Horarios de Registro
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md flex items-start">
                    <span class="mr-3">⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md">
                    <p class="font-semibold mb-2">Errores de validación:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('schedule-config.update') }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')

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
                                <h3 class="text-lg font-bold text-gray-800 mb-4">
                                    {{ $emoji }} {{ ucfirst($meal) }}
                                </h3>

                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Hora de inicio -->
                                    <div>
                                        <label for="{{ $meal }}_start" class="block text-sm font-medium text-gray-700 mb-2">
                                            Hora de inicio
                                        </label>
                                        <input 
                                            type="time"
                                            name="{{ $meal }}_start"
                                            id="{{ $meal }}_start"
                                            value="{{ $schedule ? $schedule->start_time->format('H:i') : '' }}"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                            required
                                        >
                                        @error("{$meal}_start")
                                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Hora de fin -->
                                    <div>
                                        <label for="{{ $meal }}_end" class="block text-sm font-medium text-gray-700 mb-2">
                                            Hora de fin
                                        </label>
                                        <input 
                                            type="time"
                                            name="{{ $meal }}_end"
                                            id="{{ $meal }}_end"
                                            value="{{ $schedule ? $schedule->end_time->format('H:i') : '' }}"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                            required
                                        >
                                        @error("{$meal}_end")
                                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Refrigerio Mañana -->
                        @php
                            $refrigerio_manana = $schedules['refrigerio_mañana'] ?? null;
                        @endphp
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-6 border border-orange-200">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">
                                🍊 Refrigerio Mañana
                            </h3>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Hora de inicio -->
                                <div>
                                    <label for="refrigerio_manana_start" class="block text-sm font-medium text-gray-700 mb-2">
                                        Hora de inicio
                                    </label>
                                    <input 
                                        type="time"
                                        name="refrigerio_manana_start"
                                        id="refrigerio_manana_start"
                                        value="{{ $refrigerio_manana ? $refrigerio_manana->start_time->format('H:i') : '' }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                                        required
                                    >
                                    @error("refrigerio_manana_start")
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Hora de fin -->
                                <div>
                                    <label for="refrigerio_manana_end" class="block text-sm font-medium text-gray-700 mb-2">
                                        Hora de fin
                                    </label>
                                    <input 
                                        type="time"
                                        name="refrigerio_manana_end"
                                        id="refrigerio_manana_end"
                                        value="{{ $refrigerio_manana ? $refrigerio_manana->end_time->format('H:i') : '' }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition"
                                        required
                                    >
                                    @error("refrigerio_manana_end")
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Refrigerio Tarde -->
                        @php
                            $refrigerio_tarde = $schedules['refrigerio_tarde'] ?? null;
                        @endphp
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-6 border border-yellow-200">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">
                                🍋 Refrigerio Tarde
                            </h3>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Hora de inicio -->
                                <div>
                                    <label for="refrigerio_tarde_start" class="block text-sm font-medium text-gray-700 mb-2">
                                        Hora de inicio
                                    </label>
                                    <input 
                                        type="time"
                                        name="refrigerio_tarde_start"
                                        id="refrigerio_tarde_start"
                                        value="{{ $refrigerio_tarde ? $refrigerio_tarde->start_time->format('H:i') : '' }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition"
                                        required
                                    >
                                    @error("refrigerio_tarde_start")
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Hora de fin -->
                                <div>
                                    <label for="refrigerio_tarde_end" class="block text-sm font-medium text-gray-700 mb-2">
                                        Hora de fin
                                    </label>
                                    <input 
                                        type="time"
                                        name="refrigerio_tarde_end"
                                        id="refrigerio_tarde_end"
                                        value="{{ $refrigerio_tarde ? $refrigerio_tarde->end_time->format('H:i') : '' }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition"
                                        required
                                    >
                                    @error("refrigerio_tarde_end")
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex gap-4">
                            <button 
                                type="submit"
                                class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition"
                            >
                                💾 Guardar Cambios
                            </button>
                            <a 
                                href="{{ route('schedule-config.index') }}"
                                class="inline-flex items-center px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium transition"
                            >
                                ❌ Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@endsection
