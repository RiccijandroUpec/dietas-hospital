@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="font-semibold text-xl text-gray-800 mb-4">Reporte de Registros de Dieta</h2>
                <form method="GET" action="{{ route('registro-dietas.reporte') }}" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha</label>
                        <input type="date" name="fecha" value="{{ request('fecha') }}" class="mt-1 block w-full border-gray-300 rounded-md">
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
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo de comida</label>
                        <select name="tipo_comida" class="mt-1 block w-full border-gray-300 rounded-md">
                            <option value="">-- Todos --</option>
                            <option value="desayuno" @if(request('tipo_comida')=='desayuno') selected @endif>Desayuno</option>
                            <option value="almuerzo" @if(request('tipo_comida')=='almuerzo') selected @endif>Almuerzo</option>
                            <option value="merienda" @if(request('tipo_comida')=='merienda') selected @endif>Merienda</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md w-full">Buscar</button>
                    </div>
                </form>
                <div class="w-full overflow-auto">
                    <table class="table-auto w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Servicio</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Cama</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Dietas</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Tipo comida</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Observaciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($registros as $r)
                                <tr>
                                    <td class="px-3 py-2">{{ optional($r->paciente)->nombre }} {{ optional($r->paciente)->apellido }}</td>
                                    <td class="px-3 py-2">{{ optional($r->paciente->servicio)->nombre }}</td>
                                    <td class="px-3 py-2">{{ optional($r->paciente->cama)->codigo }}</td>
                                    <td class="px-3 py-2">
                                        @foreach($r->dietas as $dieta)
                                            <span class="inline-block bg-gray-100 text-gray-800 rounded px-2 py-0.5 mr-1 text-xs">{{ $dieta->nombre }}</span>
                                        @endforeach
                                    </td>
                                    <td class="px-3 py-2 text-xs">
                                        @php $tipos = ['desayuno'=>'Desayuno','almuerzo'=>'Almuerzo','merienda'=>'Merienda']; @endphp
                                        {{ $tipos[$r->tipo_comida] ?? $r->tipo_comida }}
                                    </td>
                                    <td class="px-3 py-2">{{ $r->fecha }}</td>
                                    <td class="px-3 py-2">{{ $r->observaciones }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-3 py-2 text-center text-gray-500">No se encontraron registros.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $registros->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
