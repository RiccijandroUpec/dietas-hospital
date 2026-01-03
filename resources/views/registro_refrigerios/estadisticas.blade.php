@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">📈 Estadísticas Avanzadas de Refrigerios</h1>
                <p class="text-gray-600 mt-1">Visión consolidada de entregas, servicios y tendencias</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('registro-refrigerios.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg shadow-sm hover:bg-gray-50">
                    ← Volver a registros
                </a>
                <a href="{{ route('registro-refrigerios.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-orange-500 text-white rounded-lg shadow hover:bg-orange-600">
                    🗂️ Dashboard diario
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-5 border-l-4 border-orange-500">
                <p class="text-sm text-gray-600">Entregas únicas</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalEntregas }}</p>
                <p class="text-xs text-gray-500">Paciente + fecha + momento</p>
            </div>
            <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">
                <p class="text-sm text-gray-600">Pacientes distintos</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $pacientesUnicos }}</p>
                <p class="text-xs text-gray-500">Con al menos un refrigerio</p>
            </div>
            <div class="bg-white rounded-lg shadow p-5 border-l-4 border-green-500">
                <p class="text-sm text-gray-600">Hoy</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $hoy }}</p>
                <p class="text-xs text-gray-500">Entregas únicas</p>
            </div>
            <div class="bg-white rounded-lg shadow p-5 border-l-4 border-purple-500">
                <p class="text-sm text-gray-600">Últimos 7 días</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $semana }}</p>
                <p class="text-xs text-gray-500">Entregas únicas</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg shadow p-5 lg:col-span-1">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-semibold text-gray-900">Por momento</h2>
                    <span class="text-sm text-gray-500">Total: {{ $totalEntregas }}</span>
                </div>
                @php $momentoLabels = ['mañana' => 'Mañana', 'tarde' => 'Tarde', 'noche' => 'Noche']; @endphp
                <div class="space-y-3">
                    @foreach($porMomento as $momento => $count)
                        @php $percent = $totalEntregas > 0 ? round(($count / $totalEntregas) * 100) : 0; @endphp
                        <div>
                            <div class="flex justify-between text-sm text-gray-700">
                                <span>{{ $momentoLabels[$momento] ?? ucfirst($momento) }}</span>
                                <span class="text-gray-500">{{ $count }} ({{ $percent }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2 mt-1">
                                <div class="h-2 rounded-full bg-orange-500" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-5 lg:col-span-1">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-semibold text-gray-900">Servicios con más entregas</h2>
                    <span class="text-sm text-gray-500">Top</span>
                </div>
                <div class="space-y-3">
                    @foreach($porServicio->take(6) as $servicio => $count)
                        @php $percent = $totalServicios > 0 ? round(($count / $totalServicios) * 100) : 0; @endphp
                        <div>
                            <div class="flex justify-between text-sm text-gray-700">
                                <span>{{ $servicio }}</span>
                                <span class="text-gray-500">{{ $count }} ({{ $percent }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2 mt-1">
                                <div class="h-2 rounded-full bg-blue-500" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                    @if($porServicio->isEmpty())
                        <p class="text-sm text-gray-500">Sin datos disponibles.</p>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-5 lg:col-span-1">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-semibold text-gray-900">Refrigerios más asignados</h2>
                    <span class="text-sm text-gray-500">Top</span>
                </div>
                <div class="space-y-3">
                    @foreach($porRefrigerio->take(6) as $nombre => $count)
                        @php $percent = $totalRefrigerios > 0 ? round(($count / $totalRefrigerios) * 100) : 0; @endphp
                        <div>
                            <div class="flex justify-between text-sm text-gray-700">
                                <span>{{ $nombre }}</span>
                                <span class="text-gray-500">{{ $count }} registros</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2 mt-1">
                                <div class="h-2 rounded-full bg-green-500" style="width: {{ min($percent, 100) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                    @if($porRefrigerio->isEmpty())
                        <p class="text-sm text-gray-500">Sin datos disponibles.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-5">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold text-gray-900">Tendencia últimos 7 días</h2>
                <span class="text-sm text-gray-500">Entregas únicas por día</span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-7 gap-4">
                @foreach($tendencia as $dia)
                    <div class="p-3 rounded-lg bg-gray-50 border border-gray-100">
                        <p class="text-xs text-gray-500">{{ $dia['fecha'] }}</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $dia['count'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
