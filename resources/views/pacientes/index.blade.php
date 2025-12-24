@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold text-xl text-gray-800">Pacientes</h2>
                    <div class="flex gap-2">
                        @if(auth()->check() && auth()->user()->role !== 'usuario')
                            <a href="{{ route('pacientes.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md">Nuevo Paciente</a>
                        @endif
                        @if(auth()->check() && auth()->user()->role !== 'usuario')
                            <a href="{{ route('registro-dietas.create') }}" class="inline-flex items-center px-4 py-2 bg-pink-600 text-white rounded-md">Registrar Dieta</a>
                        @endif
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-4 text-green-600">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
                @endif

                <div class="w-full overflow-hidden">
                    <table class="table-auto w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Apellido</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Cédula</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Edad</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Condición</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Servicio</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Cama</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Creado por</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Actualizado por</th>
                                <th class="px-3 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pacientes as $paciente)
                            <tr>
                                <td class="px-3 py-2 whitespace-normal break-words">{{ $paciente->nombre }}</td>
                                <td class="px-3 py-2 whitespace-normal break-words">{{ $paciente->apellido }}</td>
                                <td class="px-3 py-2 whitespace-normal break-words">{{ $paciente->cedula }}</td>
                                <td class="px-3 py-2 whitespace-normal break-words">{{ $paciente->edad }}</td>
                                <td class="px-3 py-2 whitespace-normal break-words">
                                    @php
                                        $cond = $paciente->condicion;
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
                                <td class="px-3 py-2 whitespace-normal break-words">{{ optional($paciente->servicio)->nombre }}</td>
                                <td class="px-3 py-2 whitespace-normal break-words">{{ optional($paciente->cama)->codigo }}</td>
                                <td class="px-3 py-2 whitespace-normal break-words">{{ optional($paciente->createdBy)->name }}</td>
                                <td class="px-3 py-2 whitespace-normal break-words">{{ optional($paciente->updatedBy)->name }}</td>
                                <td class="px-3 py-2 whitespace-normal text-right text-sm font-medium">
                                    @if(auth()->check() && auth()->user()->role !== 'usuario')
                                        <a href="{{ route('pacientes.edit', $paciente) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Editar</a>
                                        <form action="{{ route('pacientes.destroy', $paciente) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar paciente?')">
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
                        {{ $pacientes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
