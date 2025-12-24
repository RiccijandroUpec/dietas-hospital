@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold text-xl text-gray-800">Dietas</h2>
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('dietas.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md">Nueva Dieta</a>
                    @endif
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
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                <th class="px-3 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($dietas as $dieta)
                            <tr>
                                <td class="px-3 py-2 whitespace-normal break-words">{{ $dieta->nombre }}</td>
                                <td class="px-3 py-2 whitespace-normal break-words">{{ Str::limit($dieta->descripcion, 200) }}</td>
                                <td class="px-3 py-2 whitespace-normal text-right text-sm font-medium">
                                    @if(auth()->check() && auth()->user()->role === 'admin')
                                        <a href="{{ route('dietas.edit', $dieta) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Editar</a>
                                        <form action="{{ route('dietas.destroy', $dieta) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar dieta?')">
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
                        {{ $dietas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
