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
                    <h3 class="text-2xl font-bold">¡Bienvenido, {{ auth()->user()->name }}! 👋</h3>
                    <p class="mt-2 text-blue-100">Sistema de Gestión de Dietas Hospitalarias</p>
                    <div class="mt-3 flex items-center gap-4 text-sm">
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
            </div>

            <!-- Estadísticas Rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Pacientes: Hospitalizados vs Total -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Pacientes Hospitalizados</p>
                                <p class="text-4xl font-bold text-green-600 mt-2">
                                    {{ \App\Models\Paciente::where('estado', 'hospitalizado')->count() }}
                                </p>
                                <div class="flex gap-3 mt-3 flex-col">
                                    <p class="text-xs text-gray-600 font-medium">
                                        📊 Total: <span class="font-bold text-blue-600">{{ \App\Models\Paciente::count() }}</span>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        🔵 {{ \App\Models\Paciente::where('estado', 'alta')->count() }} de alta
                                    </p>
                                </div>
                            </div>
                            <div class="bg-green-100 rounded-full p-3">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Registros de Dieta -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Registros Dieta</p>
                                <p class="text-3xl font-bold text-pink-600 mt-2">
                                    {{ \App\Models\RegistroDieta::count() }}
                                </p>
                                <p class="text-xs text-gray-500 mt-2">
                                    {{ \App\Models\RegistroDieta::whereDate('created_at', today())->count() }} hoy
                                </p>
                            </div>
                            <div class="bg-pink-100 rounded-full p-3">
                                <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Servicios -->
                @if(auth()->user()->role === 'admin')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Servicios</p>
                                <p class="text-3xl font-bold text-green-600 mt-2">
                                    {{ \App\Models\Servicio::count() }}
                                </p>
                                <p class="text-xs text-gray-500 mt-2">Activos</p>
                            </div>
                            <div class="bg-green-100 rounded-full p-3">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Camas -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Camas</p>
                                <p class="text-3xl font-bold text-yellow-600 mt-2">
                                    {{ \App\Models\Cama::count() }}
                                </p>
                                <p class="text-xs text-gray-500 mt-2">Total disponibles</p>
                            </div>
                            <div class="bg-yellow-100 rounded-full p-3">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Dietas</p>
                                <p class="text-3xl font-bold text-purple-600 mt-2">
                                    {{ \App\Models\Dieta::count() }}
                                </p>
                                <p class="text-xs text-gray-500 mt-2">Tipos disponibles</p>
                            </div>
                            <div class="bg-purple-100 rounded-full p-3">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
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
</x-app-layout>
