@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="font-semibold text-xl text-gray-800 mb-4">Crear Cama</h2>

                <form action="{{ route('camas.store') }}" method="POST">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Código</label>
                        <input type="text" name="codigo" value="{{ old('codigo') }}" class="mt-1 block w-full border-gray-300 rounded-md">
                        @error('codigo')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Servicio</label>
                        <select name="servicio_id" class="mt-1 block w-full border-gray-300 rounded-md">
                            <option value="">-- Ninguno --</option>
                            @foreach($servicios as $servicio)
                                <option value="{{ $servicio->id }}" @if(old('servicio_id') == $servicio->id) selected @endif>{{ $servicio->nombre }}</option>
                            @endforeach
                        </select>
                        @error('servicio_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md">Guardar</button>
                        <a href="{{ route('camas.index') }}" class="ml-2 text-gray-600">Cancelar</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
