@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold text-xl text-gray-800 flex items-center gap-2">
                        <svg class="h-5 w-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                        </svg>
                        Servicios
                    </h2>
                    <a href="{{ route('servicios.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md">
                        <svg class="h-4 w-4 me-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nuevo Servicio
                    </a>
                </div>

                @if(session('success'))<div class="mb-4 text-green-600">{{ session('success') }}</div>@endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($servicios as $servicio)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $servicio->nombre }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('servicios.edit', $servicio) }}" class="text-indigo-600 hover:text-indigo-900 mr-2 inline-flex items-center">
                                        <svg class="h-4 w-4 me-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h6M4 21v-6l12-12 6 6-12 12H4z" />
                                        </svg>
                                        Editar
                                    </a>
                                    <form action="{{ route('servicios.destroy', $servicio) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar servicio?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 inline-flex items-center">
                                            <svg class="h-4 w-4 me-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $servicios->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
