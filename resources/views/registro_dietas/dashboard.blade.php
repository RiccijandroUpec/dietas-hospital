@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">📊 Dashboard de Comidas del Día</h1>
                <p class="text-gray-600 mt-1">Selecciona el tipo de comida y visualiza todo detallado y contabilizado</p>
            </div>
            <a href="{{ route('registro-dietas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg">Volver a Registros</a>
        </div>

        <!-- Filtros -->
        <form method="GET" action="{{ route('registro-dietas.dashboard') }}" class="mb-6 p-4 bg-white rounded-lg shadow-md">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="text-sm text-gray-600">Fecha</label>
                    <input type="date" name="fecha" value="{{ $fecha }}" class="mt-1 w-full border rounded-lg px-3 py-2" />
                </div>
                <div>
                    <label class="text-sm text-gray-600">Tipo de comida</label>
                    <select name="tipo_comida" class="mt-1 w-full border rounded-lg px-3 py-2">
                        @foreach(['desayuno' => 'Desayuno', 'almuerzo' => 'Almuerzo', 'merienda' => 'Merienda'] as $key => $label)
                            <option value="{{ $key }}" @selected($tipo === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm text-gray-600">Servicio (opcional)</label>
                    <select name="servicio_id" class="mt-1 w-full border rounded-lg px-3 py-2">
                        <option value="">Todos</option>
                        @foreach($servicios as $s)
                            <option value="{{ $s->id }}" @selected(request('servicio_id')==$s->id)>{{ $s->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">Filtrar</button>
                </div>
            </div>
        </form>

        <!-- Resumen -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-700">Registros</p>
                <p class="text-3xl font-bold text-blue-900">{{ $totales['registros'] }}</p>
            </div>
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                <p class="text-sm text-emerald-700">Pacientes únicos</p>
                <p class="text-3xl font-bold text-emerald-900">{{ $totales['pacientes_unicos'] }}</p>
            </div>
            <div class="bg-pink-50 border border-pink-200 rounded-lg p-4">
                <p class="text-sm text-pink-700">Dietas totales</p>
                <p class="text-3xl font-bold text-pink-900">{{ $totales['dietas_total'] }}</p>
            </div>
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <p class="text-sm text-amber-700">Servicios</p>
                <p class="text-3xl font-bold text-amber-900">{{ $totales['servicios']->sum() }}</p>
            </div>
        </div>

        <!-- Vajilla -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-gray-50 border border-gray-300 rounded-lg p-4">
                <p class="text-sm text-gray-700">🍽️ Vajilla Normal</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totales['vajilla_normal'] }}</p>
            </div>
            <div class="bg-slate-50 border border-slate-300 rounded-lg p-4">
                <p class="text-sm text-slate-700">📦 Descartable</p>
                <p class="text-3xl font-bold text-slate-900">{{ $totales['vajilla_descartable'] }}</p>
            </div>
        </div>

        <!-- Tabla Detallada -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-indigo-50 to-indigo-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Paciente</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Servicio</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Cama</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Dietas</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Vajilla</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Observaciones</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Registrado</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($registros as $r)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">{{ optional($r->paciente)->nombre }} {{ optional($r->paciente)->apellido }}</div>
                                <div class="text-xs text-gray-500">{{ optional($r->paciente)->cedula }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ optional(optional($r->paciente)->servicio)->nombre ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ optional(optional($r->paciente)->cama)->codigo ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($r->dietas as $dieta)
                                        @php
                                            $n = strtolower($dieta->nombre ?? '');
                                            $cls = 'bg-indigo-100 text-indigo-800';
                                            $title = '';
                                            if (str_contains($n, 'npo') || str_contains($n, 'n.p.o') || str_contains($n, 'nada por via oral') || str_contains($n, 'nada por vía oral')) {
                                                $cls = 'bg-gray-200 text-gray-700';
                                                $title = 'NPO (no contabilizable)';
                                            } elseif (str_contains($n, 'diab')) {
                                                $cls = 'bg-rose-100 text-rose-800';
                                            } elseif (str_contains($n, 'hiposod') || str_contains($n, 'sodio')) {
                                                $cls = 'bg-sky-100 text-sky-800';
                                            } elseif (str_contains($n, 'normal')) {
                                                $cls = 'bg-green-100 text-green-800';
                                            } elseif (str_contains($n, 'bland')) {
                                                $cls = 'bg-amber-100 text-amber-800';
                                            } elseif (str_contains($n, 'líquid') || str_contains($n, 'liquid')) {
                                                $cls = 'bg-cyan-100 text-cyan-800';
                                            } elseif (str_contains($n, 'veget')) {
                                                $cls = 'bg-emerald-100 text-emerald-800';
                                            }
                                        @endphp
                                        <span class="inline-block rounded-full px-2 py-1 text-xs font-medium {{ $cls }}" title="{{ $title }}">{{ $dieta->nombre }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $v = $r->vajilla;
                                    $cls = $v === 'descartable' ? 'bg-slate-100 text-slate-800' : 'bg-green-100 text-green-800';
                                    $label = $v === 'descartable' ? '📦 Descartable' : '🍽️ Normal';
                                @endphp
                                <span class="inline-block rounded-full px-2 py-1 text-xs font-medium {{ $cls }}">{{ $label }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate" title="{{ $r->observaciones }}">{{ $r->observaciones ?? '—' }}</td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                <div>{{ optional($r->createdBy)->name ?? '—' }}</div>
                                <div class="text-gray-400">{{ $r->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <a href="{{ route('registro-dietas.show', $r) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">👁️ Ver</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-600">No hay registros para los filtros seleccionados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Totales al pie -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Totales del día</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-2">Dietas por tipo</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($totales['dietas_por_tipo'] as $tipoNombre => $count)
                            @php
                                $cls = 'bg-indigo-100 text-indigo-800';
                                if (str_contains(strtolower($tipoNombre), 'normal')) {
                                    $cls = 'bg-green-100 text-green-800';
                                } elseif (str_contains(strtolower($tipoNombre), 'terap')) {
                                    $cls = 'bg-rose-100 text-rose-800';
                                } elseif (str_contains(strtolower($tipoNombre), 'npo')) {
                                    $cls = 'bg-gray-200 text-gray-700';
                                } elseif (str_contains(strtolower($tipoNombre), 'consistencia')) {
                                    $cls = 'bg-amber-100 text-amber-800';
                                }
                            @endphp
                            <span class="inline-flex items-center gap-2 {{ $cls }} rounded-full px-3 py-1 text-xs font-semibold">
                                <span>{{ $tipoNombre }}</span>
                                <span class="bg-white/60 text-gray-700 rounded px-2 py-0.5">{{ $count }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-2">Dietas por subtipo</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($totales['dietas_por_subtipo'] as $subtipoNombre => $count)
                            @php
                                $n = strtolower($subtipoNombre);
                                $cls = 'bg-indigo-100 text-indigo-800';
                                if (str_contains($n, 'diab')) {
                                    $cls = 'bg-rose-100 text-rose-800';
                                } elseif (str_contains($n, 'hiposod') || str_contains($n, 'sodio')) {
                                    $cls = 'bg-sky-100 text-sky-800';
                                } elseif (str_contains($n, 'normal') || str_contains($n, 'completa')) {
                                    $cls = 'bg-green-100 text-green-800';
                                } elseif (str_contains($n, 'bland')) {
                                    $cls = 'bg-amber-100 text-amber-800';
                                } elseif (str_contains($n, 'líquid') || str_contains($n, 'liquid')) {
                                    $cls = 'bg-cyan-100 text-cyan-800';
                                } elseif (str_contains($n, 'veget')) {
                                    $cls = 'bg-emerald-100 text-emerald-800';
                                }
                            @endphp
                            <span class="inline-flex items-center gap-2 {{ $cls }} rounded-full px-3 py-1 text-xs font-semibold">
                                <span>{{ $subtipoNombre }}</span>
                                <span class="bg-white/60 text-gray-700 rounded px-2 py-0.5">{{ $count }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-2">Pacientes por servicio</h3>
                    <ul class="text-sm text-gray-800 space-y-1">
                        @foreach($totales['servicios'] as $servicioNombre => $count)
                            <li class="flex justify-between">
                                <span>{{ $servicioNombre }}</span>
                                <span class="font-semibold">{{ $count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-2">Resumen</h3>
                    <ul class="text-sm text-gray-800 space-y-1">
                        <li class="flex justify-between"><span>Total registros</span><span class="font-semibold">{{ $totales['registros'] }}</span></li>
                        <li class="flex justify-between"><span>Pacientes únicos</span><span class="font-semibold">{{ $totales['pacientes_unicos'] }}</span></li>
                        <li class="flex justify-between"><span>Dietas asignadas (excluye NPO)</span><span class="font-semibold">{{ $totales['dietas_total'] }}</span></li>
                        <li class="flex justify-between"><span>Dietas NPO</span>
                            <span class="inline-flex items-center gap-2">
                                <span class="font-semibold">{{ $totales['dietas_npo'] ?? 0 }}</span>
                                @if(($totales['dietas_npo'] ?? 0) > 0)
                                    <span class="px-2 py-0.5 rounded-full text-xs bg-gray-200 text-gray-700">Detectadas</span>
                                @endif
                            </span>
                        </li>
                        <li class="flex justify-between border-t pt-1 mt-1"><span>🍽️ Vajilla Normal</span><span class="font-semibold">{{ $totales['vajilla_normal'] ?? 0 }}</span></li>
                        <li class="flex justify-between"><span>📦 Descartable</span><span class="font-semibold">{{ $totales['vajilla_descartable'] ?? 0 }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
