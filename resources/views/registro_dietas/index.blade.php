@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold text-xl text-gray-800">Registros de Dieta</h2>
                    @if(auth()->user()->role !== 'usuario')
                        <a href="{{ route('registro-dietas.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md">Nuevo</a>
                    @endif
                </div>
                @if(session('success'))
                    <div class="mb-4 text-green-600">{{ session('success') }}</div>
                @endif
                <div class="w-full overflow-auto">
                    <table class="table-auto w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Dieta</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Tipo comida</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Observaciones</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Creado por</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Actualizado por</th>
                                <th class="px-3 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($registros as $r)
                                <tr>
                                    <td class="px-3 py-2">{{ optional($r->paciente)->nombre }} {{ optional($r->paciente)->apellido }}</td>
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
                                    <td class="px-3 py-2">{{ optional($r->createdBy)->name }}</td>
                                    <td class="px-3 py-2">{{ optional($r->updatedBy)->name }}</td>
                                    <td class="px-3 py-2 text-right">
                                        @if(auth()->user()->role !== 'usuario')
                                            <a href="{{ route('registro-dietas.edit', $r) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Editar</a>
                                            <form action="{{ route('registro-dietas.destroy', $r) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar registro?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
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
