@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- Header -->
                <div class="mb-6">
                    <h2 class="font-semibold text-2xl text-gray-800">📊 Reporte de Registros de Dieta</h2>
                    <p class="text-gray-600 text-sm mt-1">Análisis detallado de registros dietéticos</p>
                </div>

                <!-- Filtros -->
                <form method="GET" action="{{ route('registro-dietas.reporte') }}" class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">📅 Fecha</label>
                            <input type="date" name="fecha" value="{{ request('fecha') }}" class="w-full border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">🏢 Servicio</label>
                            <select name="servicio_id" class="w-full border-gray-300 rounded-md text-sm">
                                <option value="">-- Todos --</option>
                                @foreach($servicios as $servicio)
                                    <option value="{{ $servicio->id }}" @if(request('servicio_id') == $servicio->id) selected @endif>{{ $servicio->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">🍽️ Tipo de Comida</label>
                            <select name="tipo_comida" class="w-full border-gray-300 rounded-md text-sm">
                                <option value="">-- Todos --</option>
                                <option value="desayuno" @if(request('tipo_comida') == 'desayuno') selected @endif>Desayuno</option>
                                <option value="almuerzo" @if(request('tipo_comida') == 'almuerzo') selected @endif>Almuerzo</option>
                                <option value="merienda" @if(request('tipo_comida') == 'merienda') selected @endif>Merienda</option>
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="flex-1 px-5 py-2.5 bg-blue-300 text-blue-900 rounded-lg hover:bg-blue-400 transition font-semibold shadow-md text-sm">🔍 Filtrar</button>
                            <a href="{{ route('registro-dietas.reporte') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition text-sm font-medium">↺ Limpiar</a>
                        </div>
                    </div>
                </form>

                <!-- Estadísticas -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <div class="text-xs font-medium text-blue-600">Total Registros</div>
                        <div class="text-3xl font-bold text-blue-900 mt-1">{{ $registros->total() }}</div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                        <div class="text-xs font-medium text-yellow-600">🌅 Desayunos</div>
                        <div class="text-3xl font-bold text-yellow-900 mt-1">{{ $registros->where('tipo_comida', 'desayuno')->count() }}</div>
                    </div>
                    <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
                        <div class="text-xs font-medium text-orange-600">🌤️ Almuerzos</div>
                        <div class="text-3xl font-bold text-orange-900 mt-1">{{ $registros->where('tipo_comida', 'almuerzo')->count() }}</div>
                    </div>
                    <div class="bg-pink-50 p-4 rounded-lg border border-pink-200">
                        <div class="text-xs font-medium text-pink-600">🌆 Meriendas</div>
                        <div class="text-3xl font-bold text-pink-900 mt-1">{{ $registros->where('tipo_comida', 'merienda')->count() }}</div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="w-full overflow-x-auto">
                    @if($registros->count() > 0)
                        <table class="w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Paciente</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Servicio</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Cama</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Dietas</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Tipo Comida</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Fecha</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($registros as $r)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 font-medium text-gray-900">
                                            {{ optional($r->paciente)->nombre }} {{ optional($r->paciente)->apellido }}
                                            <div class="text-xs text-gray-500">{{ optional($r->paciente)->cedula }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">{{ optional($r->paciente->servicio)->nombre }}</td>
                                        <td class="px-4 py-3 text-gray-600 font-mono">{{ optional($r->paciente->cama)->codigo }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($r->dietas as $dieta)
                                                    <span class="inline-block bg-indigo-100 text-indigo-800 rounded-full px-2 py-0.5 text-xs font-medium">{{ $dieta->nombre }}</span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @php 
                                                $tiposComida = [
                                                    'desayuno' => ['🌅 Desayuno', 'bg-yellow-100 text-yellow-800'],
                                                    'almuerzo' => ['🌤️ Almuerzo', 'bg-orange-100 text-orange-800'],
                                                    'merienda' => ['🌆 Merienda', 'bg-pink-100 text-pink-800']
                                                ];
                                                $tipo = $tiposComida[$r->tipo_comida] ?? ['', ''];
                                            @endphp
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $tipo[1] }}">{{ $tipo[0] }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">{{ \Carbon\Carbon::parse($r->fecha)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 text-gray-600 max-w-xs truncate" title="{{ $r->observaciones }}">{{ $r->observaciones ?? '–' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-12">
                            <div class="text-5xl mb-4">📭</div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">No hay registros</h3>
                            <p class="text-gray-600">No se encontraron registros con los criterios seleccionados.</p>
                        </div>
                    @endif

                    @if($registros->count() > 0)
                        <div class="mt-6">
                            {{ $registros->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
