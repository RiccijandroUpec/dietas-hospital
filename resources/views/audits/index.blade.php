@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">📋 Historial de Auditoría</h1>
            <p class="text-gray-600 mt-1">Registro de todas las acciones realizadas en el sistema</p>
        </div>

        <!-- Filtros -->
        <form method="GET" class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Buscar</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Usuario, descripción, IP..." class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Usuario</label>
                    <select name="user_id" class="w-full border rounded-lg px-3 py-2">
                        <option value="">-- Todos --</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" @selected(request('user_id') == $usuario->id)>{{ $usuario->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Acción</label>
                    <select name="action" class="w-full border rounded-lg px-3 py-2">
                        <option value="">-- Todos --</option>
                        @foreach($acciones as $accion)
                            <option value="{{ $accion }}" @selected(request('action') == $accion)>{{ ucfirst($accion) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Modelo</label>
                    <select name="model_type" class="w-full border rounded-lg px-3 py-2">
                        <option value="">-- Todos --</option>
                        @foreach($modelos as $modelo)
                            <option value="{{ $modelo }}" @selected(request('model_type') == $modelo)>{{ $modelo }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Desde</label>
                    <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Hasta</label>
                    <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="w-full border rounded-lg px-3 py-2">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">🔍 Filtrar</button>
                    <a href="{{ route('audits.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition">↺ Limpiar</a>
                </div>
            </div>
        </form>

        <!-- Tabla de auditoría -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-blue-50 to-blue-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Fecha y Hora</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Acción</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Modelo</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Descripción</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">IP</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($audits as $audit)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                    {{ $audit->created_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <span class="inline-block bg-blue-100 text-blue-800 rounded-full px-3 py-1 text-xs font-semibold">
                                        {{ optional($audit->user)->name ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
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
                                    <span class="inline-block {{ $colors[0] }} {{ $colors[1] }} rounded-full px-3 py-1 text-xs font-semibold">
                                        {{ $colors[2] }} {{ ucfirst($audit->action) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    @if($audit->model_type)
                                        <span class="text-xs">{{ $audit->model_type }}</span>
                                        @if($audit->model_id)
                                            <span class="text-gray-500">#{{ $audit->model_id }}</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate">
                                    {{ $audit->description }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 font-mono">
                                    {{ $audit->ip_address ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-center text-sm">
                                    <a href="{{ route('audits.show', $audit) }}" class="text-blue-600 hover:text-blue-900 font-medium">👁️ Ver</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-600">
                                    No hay registros de auditoría.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="px-6 py-4 bg-gray-50 border-t">
                {{ $audits->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
