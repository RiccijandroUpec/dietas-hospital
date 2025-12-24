@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold text-xl text-gray-800">Paciente - Dietas</h2>
                    <a href="{{ route('paciente-dietas.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md">Nuevo</a>
                </div>

                @if(session('success'))
                    <div class="mb-4 text-green-600">{{ session('success') }}</div>
                @endif

                <div class="w-full overflow-auto">
                    <table class="table-auto w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                                   <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Condición</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Dieta</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Registró</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Actualizó</th>
                                <th class="px-3 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($items as $i)
                                <tr>
                                    <td class="px-3 py-2">{{ optional($i->paciente)->nombre }} {{ optional($i->paciente)->apellido }} ({{ optional($i->paciente)->cedula }})</td>
                                       {{-- DEBUG: Mostrar valor crudo de condicion --}}
                                       <div class="text-xs text-gray-400">{{ optional($i->paciente)->condicion }}</div>
                                       <td class="px-3 py-2">
                                           @php
                                               $cond = optional($i->paciente)->condicion;
                                               $labels = [
                                                   'normal' => 'Normal',
                                                   'diabetico' => 'Diabético',
                                                   'hiposodico' => 'Hiposódico',
                                               ];
                                               $condArr = $cond ? explode(',', $cond) : [];
                                           @endphp
                                           @if($condArr)
                                               @foreach($condArr as $c)
                                                   <span class="inline-block bg-gray-100 text-gray-800 rounded px-2 py-0.5 mr-1 text-xs">{{ $labels[trim($c)] ?? $c }}</span>
                                               @endforeach
                                           @else
                                               <span class="text-gray-400">–</span>
                                           @endif
                                       </td>
                                    <td class="px-3 py-2">{{ optional($i->dieta)->nombre }}</td>
                                    <td class="px-3 py-2">{{ optional($i->createdBy)->name }}</td>
                                    <td class="px-3 py-2">{{ optional($i->updatedBy)->name }}</td>
                                    <td class="px-3 py-2 text-right">
                                        <a href="{{ route('paciente-dietas.edit', $i) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Editar</a>
                                        <form action="{{ route('paciente-dietas.destroy', $i) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar registro?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
