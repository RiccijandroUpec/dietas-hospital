@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">📊 Estadísticas Avanzadas</h1>
                    <p class="text-sm text-gray-600 mt-1">Análisis detallado de pacientes y servicios</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('pacientes.reporte') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                        ← Volver al Reporte
                    </a>
                    <a href="{{ route('pacientes.exportar') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Exportar
                    </a>
                </div>
            </div>
        </div>

        <!-- Tarjetas principales -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Pacientes -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Pacientes</p>
                        <p class="text-4xl font-bold mt-2">{{ $totalPacientes }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Hospitalizados -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Hospitalizados</p>
                        <p class="text-4xl font-bold mt-2">{{ $hospitalizados }}</p>
                        <p class="text-purple-200 text-xs mt-1">{{ $totalPacientes > 0 ? number_format(($hospitalizados / $totalPacientes) * 100, 1) : 0 }}% del total</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Alta -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Alta</p>
                        <p class="text-4xl font-bold mt-2">{{ $altas }}</p>
                        <p class="text-green-200 text-xs mt-1">{{ $totalPacientes > 0 ? number_format(($altas / $totalPacientes) * 100, 1) : 0 }}% del total</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Edad Promedio -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">Edad Promedio</p>
                        <p class="text-4xl font-bold mt-2">{{ $edadPromedio ? number_format($edadPromedio, 1) : 'N/A' }}</p>
                        <p class="text-orange-200 text-xs mt-1">Rango: {{ $edadMin ?? 'N/A' }} - {{ $edadMax ?? 'N/A' }} años</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ocupación de Camas -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">🛏️ Ocupación de Camas</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <p class="text-blue-600 text-sm font-medium">Total Camas</p>
                    <p class="text-3xl font-bold text-blue-900 mt-1">{{ $totalCamas }}</p>
                </div>
                <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                    <p class="text-red-600 text-sm font-medium">Ocupadas</p>
                    <p class="text-3xl font-bold text-red-900 mt-1">{{ $camasOcupadas }}</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <p class="text-green-600 text-sm font-medium">Disponibles</p>
                    <p class="text-3xl font-bold text-green-900 mt-1">{{ $camasDisponibles }}</p>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                    <p class="text-yellow-600 text-sm font-medium">Sin Cama</p>
                    <p class="text-3xl font-bold text-yellow-900 mt-1">{{ $hospitalizadosSinCama }}</p>
                </div>
            </div>
            
            <!-- Barra de progreso -->
            <div class="mt-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Porcentaje de Ocupación</span>
                    <span class="text-lg font-bold text-gray-900">{{ number_format($porcentajeOcupacion, 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-6 overflow-hidden">
                    <div class="h-full {{ $porcentajeOcupacion > 80 ? 'bg-red-500' : ($porcentajeOcupacion > 60 ? 'bg-yellow-500' : 'bg-green-500') }} transition-all duration-500 flex items-center justify-center text-white text-xs font-bold" style="width: {{ $porcentajeOcupacion }}%">
                        @if($porcentajeOcupacion > 10)
                            {{ number_format($porcentajeOcupacion, 1) }}%
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid de Gráficos -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Distribución por Edad -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">👥 Distribución por Edad</h2>
                <div class="space-y-3">
                    @php
                        $maxRango = max(array_values($rangosEdad));
                        $colores = ['bg-blue-500', 'bg-purple-500', 'bg-green-500', 'bg-yellow-500', 'bg-red-500'];
                        $i = 0;
                    @endphp
                    @foreach($rangosEdad as $rango => $count)
                        @php
                            $width = $maxRango > 0 ? ($count / $maxRango) * 100 : 0;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-semibold text-gray-700">{{ $rango }} años</span>
                                <span class="text-sm font-bold text-gray-900">{{ $count }} pacientes</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-8 overflow-hidden">
                                <div class="{{ $colores[$i % count($colores)] }} h-full transition-all duration-500 flex items-center px-3" style="width: {{ $width }}%">
                                    @if($width > 15)
                                        <span class="text-white text-xs font-bold">{{ $totalPacientes > 0 ? number_format(($count / $totalPacientes) * 100, 1) : 0 }}%</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @php $i++; @endphp
                    @endforeach
                </div>
            </div>

            <!-- Distribución por Condición -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">🏥 Distribución por Condición</h2>
                <div class="space-y-3">
                    @php
                        $maxCondicion = !empty($condiciones) ? max(array_values($condiciones)) : 0;
                        $labels = [
                            'normal' => 'Normal',
                            'diabetico' => 'Diabético',
                            'hiposodico' => 'Hiposódico',
                        ];
                        $coloresCondicion = [
                            'normal' => 'bg-gray-500',
                            'diabetico' => 'bg-red-500',
                            'hiposodico' => 'bg-blue-500',
                        ];
                    @endphp
                    @if(!empty($condiciones))
                        @foreach($condiciones as $cond => $count)
                            @php
                                $width = $maxCondicion > 0 ? ($count / $maxCondicion) * 100 : 0;
                                $label = $labels[$cond] ?? $cond;
                                $color = $coloresCondicion[$cond] ?? 'bg-purple-500';
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-semibold text-gray-700">{{ $label }}</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $count }} pacientes</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-8 overflow-hidden">
                                    <div class="{{ $color }} h-full transition-all duration-500 flex items-center px-3" style="width: {{ $width }}%">
                                        @if($width > 15)
                                            <span class="text-white text-xs font-bold">{{ $totalPacientes > 0 ? number_format(($count / $totalPacientes) * 100, 1) : 0 }}%</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500 text-sm">Sin datos disponibles</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tendencias de Ingresos -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">📈 Tendencia de Ingresos (Últimos 7 días)</h2>
            <div class="space-y-2">
                @php
                    $maxTendencia = !empty($tendenciaCreados) ? max(array_column($tendenciaCreados, 'count')) : 1;
                @endphp
                @foreach($tendenciaCreados as $item)
                    @php
                        $width = $maxTendencia > 0 ? ($item['count'] / $maxTendencia) * 100 : 0;
                    @endphp
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-gray-600 w-12">{{ $item['fecha'] }}</span>
                        <div class="flex-1">
                            <div class="w-full bg-gray-200 rounded-full h-6 overflow-hidden">
                                <div class="bg-indigo-500 h-full transition-all duration-500 flex items-center px-2" style="width: {{ max($width, 5) }}%">
                                    @if($item['count'] > 0)
                                        <span class="text-white text-xs font-bold">{{ $item['count'] }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Estadísticas por Servicio -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">🏨 Estadísticas Detalladas por Servicio</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servicio</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Pacientes</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Edad Promedio</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Con Condiciones</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Distribución</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($serviciosConEstadisticas as $servicio)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="font-semibold text-gray-900">{{ $servicio['nombre'] }}</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                        {{ $servicio['total_pacientes'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <span class="text-sm text-gray-700">{{ $servicio['edad_promedio'] > 0 ? number_format($servicio['edad_promedio'], 1) : '–' }}</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-center">
                                    <span class="text-sm text-gray-700">{{ $servicio['con_condiciones'] }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $porcentaje = $hospitalizados > 0 ? ($servicio['total_pacientes'] / $hospitalizados) * 100 : 0;
                                    @endphp
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-gray-200 rounded-full h-4 overflow-hidden">
                                            <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-full transition-all duration-500" style="width: {{ $porcentaje }}%"></div>
                                        </div>
                                        <span class="text-xs font-medium text-gray-600 w-12 text-right">{{ number_format($porcentaje, 1) }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
