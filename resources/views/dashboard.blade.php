<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🏠 Panel Principal
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Bienvenida -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-white">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold">¡Bienvenido, {{ auth()->user()->name }}! 👋</h3>
                            <p class="mt-2 text-blue-100">Sistema de Gestión de Dietas Hospitalarias</p>
                            <div class="mt-3 flex flex-wrap items-center gap-4 text-sm">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                    Rol: <strong>{{ ucfirst(auth()->user()->role) }}</strong>
                                </span>
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ now()->locale('es')->translatedFormat('l, d \d\e F \d\e Y') }}
                                </span>
                            </div>
                        </div>

                        <div class="w-full lg:w-80 bg-white/10 border border-white/20 rounded-xl p-4 backdrop-blur">
                            <div class="text-xs uppercase tracking-wide text-blue-100 font-semibold">Hora actual</div>
                            <div class="mt-1 text-3xl font-bold" id="dashboardClock">{{ now()->setTimezone(config('app.timezone'))->format('H:i:s') }}</div>
                            <div class="mt-4">
                                <p class="text-xs text-blue-50 mb-2 font-semibold">Activos ahora</p>
                                <div class="grid grid-cols-3 gap-1.5">
                                    @php
                                        $now = now();
                                        $currentTime = $now->format('H:i');
                                        $schedules = \App\Models\RegistrationSchedule::all();
                                        $activeSchedules = [];
                                        
                                        foreach ($schedules as $schedule) {
                                            if ($schedule->isCurrentTimeAllowed()) {
                                                $activeSchedules[] = $schedule;
                                            }
                                        }
                                    @endphp
                                    
                                    @if(count($activeSchedules) > 0)
                                        @foreach($activeSchedules as $schedule)
                                            @php
                                                $icons = [
                                                    'desayuno' => '🌅',
                                                    'almuerzo' => '🍽️',
                                                    'merienda' => '☕',
                                                    'refrigerio_mañana' => '🍊',
                                                    'refrigerio_tarde' => '🍋',
                                                ];
                                                $names = [
                                                    'desayuno' => 'Desayuno',
                                                    'almuerzo' => 'Almuerzo',
                                                    'merienda' => 'Merienda',
                                                    'refrigerio_mañana' => 'Mañana',
                                                    'refrigerio_tarde' => 'Tarde',
                                                ];
                                                $remaining = $schedule->getRemainingMinutes();
                                                $colors = [
                                                    'desayuno' => 'from-amber-500 to-orange-400',
                                                    'almuerzo' => 'from-green-500 to-emerald-400',
                                                    'merienda' => 'from-purple-500 to-pink-400',
                                                    'refrigerio_mañana' => 'from-yellow-500 to-amber-400',
                                                    'refrigerio_tarde' => 'from-orange-500 to-red-400',
                                                ];
                                            @endphp
                                            <div class="bg-gradient-to-br {{ $colors[$schedule->meal_type] ?? 'from-blue-500 to-blue-400' }} rounded p-1.5 flex flex-col items-center justify-center text-white shadow hover:shadow-lg transition-all hover:scale-110 cursor-pointer">
                                                <span class="text-lg">{{ $icons[$schedule->meal_type] ?? '📋' }}</span>
                                                <span class="text-xs font-bold text-center leading-tight mt-0.5">{{ $names[$schedule->meal_type] ?? ucfirst($schedule->meal_type) }}</span>
                                                @if($remaining >= 0 && $remaining <= 30)
                                                    <span class="text-xs font-bold mt-0.5 bg-white/40 px-1 py-0 rounded text-white">{{ $remaining }}m</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-span-3 px-2 py-1.5 bg-white/20 rounded text-center border border-white/30">
                                            <p class="text-blue-100 text-xs font-semibold">⏸️ Fuera horario</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Pacientes: Hospitalizados vs Total -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition border-l-4 border-green-500">
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-xs font-medium text-gray-600">Pacientes Hospitalizados</p>
                                <div class="mt-2 space-y-2">
                                    <!-- Hospitalizados (Destacado) -->
                                    <div class="bg-white rounded-lg p-2 border-2 border-green-300 shadow">
                                        <p class="text-xs font-bold text-green-600 uppercase tracking-wide">🏥 Hospitalizados</p>
                                        <p class="text-3xl font-bold text-green-600 mt-0.5">
                                            {{ \App\Models\Paciente::where('estado', 'hospitalizado')->count() }}
                                        </p>
                                    </div>
                                    <!-- Desglose -->
                                    <div class="grid grid-cols-2 gap-1.5 mt-1">
                                        <div class="bg-blue-50 rounded p-1.5 border border-blue-200">
                                            <p class="text-xs font-bold text-blue-700">📊 Total</p>
                                            <p class="text-lg font-bold text-blue-600 mt-0.5">
                                                {{ \App\Models\Paciente::count() }}
                                            </p>
                                        </div>
                                        <div class="bg-purple-50 rounded p-1.5 border border-purple-200">
                                            <p class="text-xs font-bold text-purple-700">✓ De Alta</p>
                                            <p class="text-lg font-bold text-purple-600 mt-0.5">
                                                {{ \App\Models\Paciente::where('estado', 'alta')->count() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-green-100 rounded-full p-2.5 flex-shrink-0">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Registros de Dieta -->
                <div class="bg-gradient-to-br from-pink-50 to-rose-50 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition border-l-4 border-pink-500">
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-xs font-medium text-gray-600">Registros de Dieta</p>
                                <div class="mt-2 space-y-2">
                                    <!-- Registros de Hoy (Destacado) -->
                                    <div class="bg-white rounded-lg p-2 border-2 border-pink-300 shadow">
                                        <p class="text-xs font-bold text-pink-600 uppercase tracking-wide">📅 Registros Hoy</p>
                                        <p class="text-3xl font-bold text-pink-600 mt-0.5">
                                            {{ \App\Models\RegistroDieta::whereDate('created_at', today())->count() }}
                                        </p>
                                    </div>
                                    <!-- Total General -->
                                    <p class="text-xs text-gray-600 font-medium">
                                        <span class="inline-block bg-pink-100 text-pink-800 px-3 py-1 rounded-full text-sm font-bold">
                                            📊 Total: {{ \App\Models\RegistroDieta::count() }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="bg-pink-100 rounded-full p-2.5 flex-shrink-0">
                                <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Registros de Refrigerios -->
                <div class="bg-gradient-to-br from-orange-50 to-amber-50 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition border-l-4 border-orange-500">
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-xs font-medium text-gray-600">Registros de Refrigerios</p>
                                <div class="mt-2 space-y-2">
                                    <!-- Registros de Hoy (Destacado) -->
                                    <div class="bg-white rounded-lg p-2 border-2 border-orange-300 shadow">
                                        <p class="text-xs font-bold text-orange-600 uppercase tracking-wide">📅 Registros Hoy</p>
                                        <p class="text-3xl font-bold text-orange-600 mt-0.5">
                                            {{ \App\Models\RegistroRefrigerio::whereDate('created_at', today())->count() }}
                                        </p>
                                    </div>
                                    <!-- Desglose por turno -->
                                    <div class="grid grid-cols-3 gap-1.5 mt-1">
                                        <div class="bg-yellow-50 rounded p-1.5 border border-yellow-200">
                                            <p class="text-xs font-bold text-yellow-700">🌅 Mañana</p>
                                            <p class="text-lg font-bold text-yellow-600 mt-0.5">
                                                {{ \App\Models\RegistroRefrigerio::whereDate('created_at', today())->where('momento', 'mañana')->count() }}
                                            </p>
                                        </div>
                                        <div class="bg-orange-50 rounded p-1.5 border border-orange-200">
                                            <p class="text-xs font-bold text-orange-700">☀️ Tarde</p>
                                            <p class="text-lg font-bold text-orange-600 mt-0.5">
                                                {{ \App\Models\RegistroRefrigerio::whereDate('created_at', today())->where('momento', 'tarde')->count() }}
                                            </p>
                                        </div>
                                        <div class="bg-indigo-50 rounded p-1.5 border border-indigo-200">
                                            <p class="text-xs font-bold text-indigo-700">🌙 Noche</p>
                                            <p class="text-lg font-bold text-indigo-600 mt-0.5">
                                                {{ \App\Models\RegistroRefrigerio::whereDate('created_at', today())->where('momento', 'noche')->count() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-orange-100 rounded-full p-2.5 flex-shrink-0">
                                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Camas -->
                @if(auth()->user()->role === 'admin')
                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition border-l-4 border-blue-500">
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-xs font-medium text-gray-600">Estado de Camas</p>
                                <div class="mt-2 space-y-2">
                                    <!-- Total de Camas -->
                                    <div class="bg-white rounded-lg p-2 border-2 border-blue-300 shadow">
                                        <p class="text-xs font-bold text-blue-600 uppercase tracking-wide">🛏️ Total</p>
                                        <p class="text-3xl font-bold text-blue-600 mt-0.5">
                                            {{ \App\Models\Cama::count() }}
                                        </p>
                                    </div>
                                    <!-- Desglose Ocupadas/Vacías -->
                                    <div class="grid grid-cols-2 gap-1.5 mt-1">
                                        <div class="bg-red-50 rounded p-1.5 border border-red-200">
                                            <p class="text-xs font-bold text-red-700">🔴 Ocupadas</p>
                                            <p class="text-lg font-bold text-red-600 mt-0.5">
                                                @php
                                                    $ocupadas = \App\Models\Cama::whereHas('pacientes', function($q) {
                                                        $q->where('estado', 'hospitalizado');
                                                    })->count();
                                                @endphp
                                                {{ $ocupadas }}
                                            </p>
                                        </div>
                                        <div class="bg-green-50 rounded p-1.5 border border-green-200">
                                            <p class="text-xs font-bold text-green-700">🟢 Vacías</p>
                                            <p class="text-lg font-bold text-green-600 mt-0.5">
                                                @php
                                                    $vacias = \App\Models\Cama::count() - $ocupadas;
                                                @endphp
                                                {{ $vacias }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-blue-100 rounded-full p-2.5 flex-shrink-0">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sección de Pacientes por Estado -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Pacientes Hospitalizados -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 border-b border-green-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-green-900">🟢 Pacientes Hospitalizados</h3>
                            <span class="bg-green-600 text-white text-sm font-bold px-4 py-1 rounded-full">
                                {{ \App\Models\Paciente::where('estado', 'hospitalizado')->count() }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="max-h-96 overflow-y-auto space-y-2">
                            @php
                                $hospitalizados = \App\Models\Paciente::where('estado', 'hospitalizado')
                                    ->with(['servicio', 'cama'])
                                    ->orderBy('updated_at', 'desc')
                                    ->get();
                            @endphp
                            @forelse($hospitalizados as $paciente)
                                <div class="group border-l-4 border-green-400 bg-gradient-to-r from-green-50 to-white p-3 rounded hover:shadow-md transition hover:from-green-100">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-900 group-hover:text-green-700 transition truncate">
                                                {{ $paciente->nombre }} {{ $paciente->apellido }}
                                                @if($paciente->edad)
                                                    <span class="text-xs text-gray-600 font-normal">{{ $paciente->edad }} años</span>
                                                @endif
                                            </p>
                                            <div class="text-xs text-gray-600 mt-1 space-y-1">
                                                <p>CI: <span class="font-mono font-semibold">{{ $paciente->cedula }}</span></p>
                                                <p>
                                                    <span class="inline-block bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs font-medium">
                                                        {{ $paciente->servicio->nombre ?? 'Sin servicio' }}
                                                    </span>
                                                    @if($paciente->cama)
                                                        <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded text-xs font-medium ml-1">
                                                            Cama: {{ $paciente->cama->codigo }}
                                                        </span>
                                                    @else
                                                        <span class="inline-block bg-red-100 text-red-800 px-2 py-0.5 rounded text-xs font-medium ml-1">
                                                            Sin cama
                                                        </span>
                                                    @endif
                                                </p>
                                                @if($paciente->condicion)
                                                    <p class="text-xs">Condición: <span class="font-semibold">{{ str_replace(',', ', ', $paciente->condicion) }}</span></p>
                                                @endif
                                                <p class="text-xs text-gray-500">
                                                    Actualizado: {{ $paciente->updated_at->locale('es')->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex gap-1 flex-shrink-0">
                                            <a href="{{ route('pacientes.edit', $paciente) }}" class="p-2 text-gray-500 hover:text-orange-600 hover:bg-orange-50 rounded transition" title="Editar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7-4l7-7m0 0l-7 7"></path></svg>
                                            </a>
                                            <a href="{{ route('pacientes.show', $paciente) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded transition" title="Ver">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-gray-500 text-sm">✓ No hay pacientes hospitalizados</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Pacientes de Alta -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 p-4 border-b border-blue-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-blue-900">🔵 Pacientes de Alta</h3>
                            <span class="bg-blue-600 text-white text-sm font-bold px-4 py-1 rounded-full">
                                {{ \App\Models\Paciente::where('estado', 'alta')->count() }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="max-h-96 overflow-y-auto space-y-2">
                            @php
                                $altas = \App\Models\Paciente::where('estado', 'alta')
                                    ->with('servicio')
                                    ->orderBy('updated_at', 'desc')
                                    ->take(20)
                                    ->get();
                            @endphp
                            @forelse($altas as $paciente)
                                <div class="group border-l-4 border-blue-400 bg-gradient-to-r from-blue-50 to-white p-3 rounded hover:shadow-md transition hover:from-blue-100">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-900 group-hover:text-blue-700 transition truncate">
                                                {{ $paciente->nombre }} {{ $paciente->apellido }}
                                                @if($paciente->edad)
                                                    <span class="text-xs text-gray-600 font-normal">{{ $paciente->edad }} años</span>
                                                @endif
                                            </p>
                                            <div class="text-xs text-gray-600 mt-1 space-y-1">
                                                <p>CI: <span class="font-mono font-semibold">{{ $paciente->cedula }}</span></p>
                                                <p>
                                                    <span class="inline-block bg-purple-100 text-purple-800 px-2 py-0.5 rounded text-xs font-medium">
                                                        {{ $paciente->servicio->nombre ?? 'Sin servicio' }}
                                                    </span>
                                                </p>
                                                @if($paciente->condicion)
                                                    <p class="text-xs">Condición: <span class="font-semibold">{{ str_replace(',', ', ', $paciente->condicion) }}</span></p>
                                                @endif
                                                <p class="text-xs text-gray-500">
                                                    Alta: {{ $paciente->updated_at->locale('es')->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex gap-1 flex-shrink-0">
                                            <a href="{{ route('pacientes.edit', $paciente) }}" class="p-2 text-gray-500 hover:text-orange-600 hover:bg-orange-50 rounded transition" title="Editar">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7-4l7-7m0 0l-7 7"></path></svg>
                                            </a>
                                            <a href="{{ route('pacientes.show', $paciente) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded transition" title="Ver">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-gray-500 text-sm">✓ No hay pacientes de alta recientemente</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Auditoría / Actividad Reciente -->            <!-- Accesos Rápidos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">⚡ Accesos Rápidos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @if(auth()->user()->role === 'admin')
                        <a href="{{ route('usuarios.index') }}" class="group flex items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg hover:from-blue-100 hover:to-blue-200 transition border-l-4 border-blue-600">
                            <div class="bg-blue-600 rounded-lg p-3 group-hover:scale-110 transition">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="font-semibold text-gray-800">Gestión de Usuarios</p>
                                <p class="text-sm text-gray-600">Administrar usuarios del sistema</p>
                            </div>
                        </a>
                        @endif

                        <a href="{{ route('pacientes.index') }}" class="group flex items-center p-4 bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-lg hover:from-indigo-100 hover:to-indigo-200 transition border-l-4 border-indigo-600">
                            <div class="bg-indigo-600 rounded-lg p-3 group-hover:scale-110 transition">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="font-semibold text-gray-800">Gestión de Pacientes</p>
                                <p class="text-sm text-gray-600">Ver y administrar pacientes</p>
                            </div>
                        </a>

                        <a href="{{ route('registro-dietas.index') }}" class="group flex items-center p-4 bg-gradient-to-r from-pink-50 to-pink-100 rounded-lg hover:from-pink-100 hover:to-pink-200 transition border-l-4 border-pink-600">
                            <div class="bg-pink-600 rounded-lg p-3 group-hover:scale-110 transition">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="font-semibold text-gray-800">Registros de Dietas</p>
                                <p class="text-sm text-gray-600">Gestionar registros dietéticos</p>
                            </div>
                        </a>

                        @if(auth()->user()->role !== 'usuario')
                        <a href="{{ route('registro-dietas.create') }}" class="group flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg hover:from-green-100 hover:to-green-200 transition border-l-4 border-green-600">
                            <div class="bg-green-600 rounded-lg p-3 group-hover:scale-110 transition">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="font-semibold text-gray-800">Registrar Nueva Dieta</p>
                                <p class="text-sm text-gray-600">Crear nuevo registro dietético</p>
                            </div>
                        </a>
                        @endif

                        @if(auth()->user()->role !== 'usuario')
                        <a href="{{ route('registro-refrigerios.create') }}" class="group flex items-center p-4 bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg hover:from-orange-100 hover:to-orange-200 transition border-l-4 border-orange-600">
                            <div class="bg-orange-600 rounded-lg p-3 group-hover:scale-110 transition">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="font-semibold text-gray-800">Registrar Refrigerio</p>
                                <p class="text-sm text-gray-600">Crear registro de refrigerio</p>
                            </div>
                        </a>
                        @endif

                        @if(auth()->user()->role === 'admin')
                        <a href="{{ route('servicios.index') }}" class="group flex items-center p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg hover:from-yellow-100 hover:to-yellow-200 transition border-l-4 border-yellow-600">
                            <div class="bg-yellow-600 rounded-lg p-3 group-hover:scale-110 transition">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="font-semibold text-gray-800">Gestión de Servicios</p>
                                <p class="text-sm text-gray-600">Administrar servicios del hospital</p>
                            </div>
                        </a>

                        <a href="{{ route('camas.index') }}" class="group flex items-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg hover:from-purple-100 hover:to-purple-200 transition border-l-4 border-purple-600">
                            <div class="bg-purple-600 rounded-lg p-3 group-hover:scale-110 transition">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="font-semibold text-gray-800">Gestión de Camas</p>
                                <p class="text-sm text-gray-600">Administrar camas del hospital</p>
                            </div>
                        </a>
                        <!-- Refrigerios (admin) -->
                        <a href="{{ route('refrigerios.index') }}" class="group flex items-center p-4 bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg hover:from-orange-100 hover:to-orange-200 transition border-l-4 border-orange-600">
                            <div class="bg-orange-600 rounded-lg p-3 group-hover:scale-110 transition">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="font-semibold text-gray-800">Gestión de Refrigerios</p>
                                <p class="text-sm text-gray-600">Administrar refrigerios disponibles</p>
                            </div>
                        </a>

                        <!-- Historial de Auditoría (admin) -->
                        <a href="{{ route('audits.index') }}" class="group flex items-center p-4 bg-gradient-to-r from-red-50 to-red-100 rounded-lg hover:from-red-100 hover:to-red-200 transition border-l-4 border-red-600">
                            <div class="bg-red-600 rounded-lg p-3 group-hover:scale-110 transition">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="font-semibold text-gray-800">Historial de Auditoría</p>
                                <p class="text-sm text-gray-600">Ver registro de actividades del sistema</p>
                            </div>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Actualizaciones -->
    <div id="modalActualizaciones" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-auto animate-[slideIn_0.3s_ease-out]">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-t-2xl px-6 py-5">
            <div class="flex justify-between items-center">
                <h3 class="text-2xl font-bold text-white flex items-center gap-2">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    🎉 Actualizaciones del Sistema
                </h3>
                <button onclick="cerrarModalActualizaciones()" class="text-white hover:text-gray-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <p class="text-blue-100 text-sm mt-1">Novedades y mejoras recientes</p>
        </div>

        <!-- Contenido -->
        <div class="px-6 py-6 max-h-[70vh] overflow-y-auto">
            <!-- Aquí puedes colocar tu texto de actualizaciones -->
            <div class="space-y-4">
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                    <h4 class="font-bold text-blue-900 mb-2 flex items-center gap-2">
                        <span class="text-xl">✨</span>
                        Nueva Funcionalidad - Camas Asignadas Gráficamente
                    </h4>
                    <p class="text-blue-800 text-sm">
                        Ahora puedes ver las camas asignadas a cada paciente directamente en su perfil, facilitando la gestión y visualización rápida de la información.
                        asi mismo, puedes agregar un paciente a una cama desde la cama graficamente.
                    </p>
                </div>

                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                    <h4 class="font-bold text-green-900 mb-2 flex items-center gap-2">
                        <span class="text-xl">🔧</span>
                        Mejoras en el Dashboard
                    </h4>
                    <p class="text-green-800 text-sm">
                        Hemos mejorado la visualización de estadísticas y agregado más información en tiempo real sobre los horarios de registro disponibles.
                    </p>
                </div>

                <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded-r-lg">
                    <h4 class="font-bold text-purple-900 mb-2 flex items-center gap-2">
                        <span class="text-xl">📊</span>
                        Reportes Mejorados
                    </h4>
                    <p class="text-purple-800 text-sm">
                        Los reportes ahora incluyen información más detallada sobre quién creó y actualizó cada registro, con fechas y horas precisas.
                    </p>
                </div>

                <!-- Agrega más secciones según necesites -->
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-4 rounded-b-2xl border-t flex justify-between items-center">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>Actualizado: {{ now()->format('d/m/Y') }}</span>
            </div>
            <button onclick="cerrarModalActualizaciones()" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all shadow-md font-semibold">
                Entendido
            </button>
        </div>
    </div>
    </div>
</x-app-layout>

<script>
// Modal de actualizaciones - mostrar solo UNA VEZ
const actualizacionesVista = localStorage.getItem('actualizacionesVista');

if (!actualizacionesVista) {
    setTimeout(() => {
        const modal = document.getElementById('modalActualizaciones');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }, 500); // Pequeño delay para mejor experiencia
}

function cerrarModalActualizaciones() {
    localStorage.setItem('actualizacionesVista', 'true');
    
    const modal = document.getElementById('modalActualizaciones');
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Cerrar con Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarModalActualizaciones();
    }
});
</script>

@push('styles')
<style>
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@push('scripts')
<script>
function updateDashboardClock() {
    const el = document.getElementById('dashboardClock');
    if (!el) return;
    const now = new Date();
    const formatted = now.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    el.textContent = formatted;
}

function initDashboardClock() {
    updateDashboardClock();
    setInterval(updateDashboardClock, 1000);
}

if (document.readyState === 'complete') {
    initDashboardClock();
} else {
    window.addEventListener('load', initDashboardClock, { once: true });
}
</script>
@endpush
