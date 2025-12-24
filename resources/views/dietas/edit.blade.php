@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="font-semibold text-xl text-gray-800 mb-4">Editar Dieta</h2>

                <form action="{{ route('dietas.update', $dieta) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if(session('error'))
                        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                            <ul class="list-disc pl-5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre', $dieta->nombre) }}" class="mt-1 block w-full border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea name="descripcion" class="mt-1 block w-full border-gray-300 rounded-md" rows="5">{{ old('descripcion', $dieta->descripcion) }}</textarea>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Guardar</button>
                            <a href="{{ route('dietas.index') }}" class="ml-2 text-gray-600">Cancelar</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
