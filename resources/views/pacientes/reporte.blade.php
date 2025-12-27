@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="font-semibold text-xl text-gray-800 mb-4">Reporte de Pacientes</h2>

                <!-- Filtros -->
                <form method="GET" action="{{ route('pacientes.reporte') }}" class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                        <select name="estado" class="mt-1 block w-full border-gray-300 rounded-md">
                            <option value="">-- Todos --</option>
                            <option value="hospitalizado" @if(request('estado') == 'hospitalizado') selected @endif>Hospitalizado</option>
                            <option value="alta" @if(request('estado') == 'alta') selected @endif>Alta</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Servicio</label>
                        <select name="servicio_id" class="mt-1 block w-full border-gray-300 rounded-md">
                            <option value="">-- Todos --</option>
                            @foreach($servicios as $servicio)
                                <option value="{{ $servicio->id }}" @if(request('servicio_id') == $servicio->id) selected @endif>{{ $servicio->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md w-full">Filtrar</button>
                    </div>
                </form>

                <!-- Resumen de totales -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <div class="text-sm font-medium text-blue-600">Hospitalizados</div>
                        <div class="text-2xl font-bold text-blue-900">{{ $totales['hospitalizado'] }}</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <div class="text-sm font-medium text-green-600">Alta</div>
                        <div class="text-2xl font-bold text-green-900">{{ $totales['alta'] }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="text-sm font-medium text-gray-600">Total</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $totales['hospitalizado'] + $totales['alta'] }}</div>
                    </div>
                </div>

                <!-- Tabla de pacientes -->
                <div class="w-full overflow-auto">
                    <table class="table-auto w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Cédula</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Edad</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Condición</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Servicio</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Cama</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($pacientes as $p)
                                <tr>
                                    <td class="px-3 py-2">{{ $p->nombre }} {{ $p->apellido }}</td>
                                    <td class="px-3 py-2">{{ $p->cedula }}</td>
                                    <td class="px-3 py-2">
                                        @if($p->estado === 'hospitalizado')
                                            <span class="inline-block bg-blue-100 text-blue-800 rounded px-2 py-1 text-xs font-semibold">Hospitalizado</span>
                                        @else
                                            <span class="inline-block bg-green-100 text-green-800 rounded px-2 py-1 text-xs font-semibold">Alta</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2">{{ $p->edad ?? '–' }}</td>
                                    <td class="px-3 py-2">
                                        @php
                                            $cond = $p->condicion;
                                            $labels = [
                                                'normal' => 'Normal',
                                                'diabetico' => 'Diabético',
                                                'hiposodico' => 'Hiposódico',
                                            ];
                                            $condArr = $cond ? explode(',', $cond) : [];
                                        @endphp
                                        @if($condArr && $condArr[0] !== '')
                                            @foreach($condArr as $c)
                                                <span class="inline-block bg-gray-100 text-gray-800 rounded px-2 py-0.5 mr-1 text-xs">{{ $labels[trim($c)] ?? $c }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-gray-400">–</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2">{{ optional($p->servicio)->nombre }}</td>
                                    <td class="px-3 py-2">{{ optional($p->cama)->codigo ?? '–' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-3 py-2 text-center text-gray-500">No se encontraron pacientes.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $pacientes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
