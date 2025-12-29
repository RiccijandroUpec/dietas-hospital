@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">🔍 Detalle de Auditoría</h1>
                <p class="text-gray-600 mt-1">Información completa del evento registrado</p>
            </div>
            <a href="{{ route('audits.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg">
                ← Volver al Historial
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <!-- Información General -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-1">Fecha y Hora</h3>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $audit->created_at->format('d/m/Y H:i:s') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">{{ $audit->created_at->diffForHumans() }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-1">Usuario</h3>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ optional($audit->user)->name ?? '—' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ optional($audit->user)->email ?? '—' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Acción -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">Acción</h3>
                    <div class="flex items-center gap-3">
                        @php
                            $actionColors = [
                                'create' => ['bg-green-100', 'text-green-800', '➕'],
                                'update' => ['bg-blue-100', 'text-blue-800', '✏️'],
                                'delete' => ['bg-red-100', 'text-red-800', '🗑️'],
                                'login' => ['bg-purple-100', 'text-purple-800', '🔐'],
                                'logout' => ['bg-yellow-100', 'text-yellow-800', '🚪'],
                            ];
                            $colors = $actionColors[$audit->action] ?? ['bg-gray-100', 'text-gray-800', '•'];
                        @endphp
                        <span class="inline-block {{ $colors[0] }} {{ $colors[1] }} rounded-full px-4 py-2 text-sm font-semibold">
                            {{ $colors[2] }} {{ ucfirst($audit->action) }}
                        </span>
                    </div>
                </div>

                <!-- Descripción -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Descripción</h3>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="text-gray-900">{{ $audit->description }}</p>
                    </div>
                </div>

                <!-- Modelo -->
                @if($audit->model_type)
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Modelo Afectado</h3>
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <p class="text-gray-900">
                                <span class="font-semibold">Tipo:</span> {{ $audit->model_type }}
                                @if($audit->model_id)
                                    <span class="text-gray-600 ml-2">(ID: #{{ $audit->model_id }})</span>
                                @endif
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Cambios (si los hay) -->
                @if($audit->changes && count($audit->changes) > 0)
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">Cambios Realizados</h3>
                        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                            <div class="space-y-2">
                                @foreach($audit->changes as $field => $change)
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $field)) }}</p>
                                        <p class="text-xs text-gray-600">
                                            @if(is_array($change))
                                                De: <span class="text-red-600 font-mono">{{ json_encode($change['old'] ?? '—') }}</span>
                                                <br>
                                                A: <span class="text-green-600 font-mono">{{ json_encode($change['new'] ?? '—') }}</span>
                                            @else
                                                {{ $change }}
                                            @endif
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Información Técnica -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">Información Técnica</h3>
                    <div class="space-y-2">
                        <p class="text-sm text-gray-900">
                            <span class="font-semibold">Dirección IP:</span> 
                            <span class="font-mono">{{ $audit->ip_address ?? '—' }}</span>
                        </p>
                        <p class="text-sm text-gray-900 break-all">
                            <span class="font-semibold">User Agent:</span> 
                            <span class="font-mono text-xs">{{ $audit->user_agent ?? '—' }}</span>
                        </p>
                        <p class="text-sm text-gray-900">
                            <span class="font-semibold">ID del Evento:</span> 
                            <span class="font-mono">#{{ $audit->id }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
