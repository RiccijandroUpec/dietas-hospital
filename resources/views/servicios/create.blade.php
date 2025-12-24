@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="font-semibold text-xl text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="h-5 w-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Crear Servicio
                </h2>

                <form action="{{ route('servicios.store') }}" method="POST">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" class="mt-1 block w-full border-gray-300 rounded-md">
                        @error('nombre')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md">
                            <svg class="h-4 w-4 me-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Guardar
                        </button>
                        <a href="{{ route('servicios.index') }}" class="ml-2 text-gray-600">Cancelar</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
