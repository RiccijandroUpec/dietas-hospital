@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold text-xl text-gray-800">Camas</h2>
                    <a href="{{ route('camas.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md">Nueva Cama</a>
                </div>

                @if(session('success'))<div class="mb-4 text-green-600">{{ session('success') }}</div>@endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servicio</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($camas as $cama)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $cama->codigo }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ optional($cama->servicio)->nombre }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('camas.edit', $cama) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Editar</a>
                                    <form action="{{ route('camas.destroy', $cama) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar cama?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $camas->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
