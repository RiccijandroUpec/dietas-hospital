@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="font-semibold text-2xl text-gray-800 mb-2">✏️ Editar Tipo de Dieta</h2>
                <p class="text-gray-600 text-sm mb-6">Actualizar información del tipo de dieta</p>

                <form action="{{ route('tipos-dieta.update', $tipo) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre', $tipo->nombre) }}" class="mt-1 block w-full border-gray-300 rounded-md" required maxlength="255">
                            @error('nombre')<div class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                            <textarea name="descripcion" class="mt-1 block w-full border-gray-300 rounded-md h-24" maxlength="1000">{{ old('descripcion', $tipo->descripcion) }}</textarea>
                            <div class="text-xs text-gray-500 mt-1">Máximo 1000 caracteres</div>
                            @error('descripcion')<div class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</div>@enderror
                        </div>

                        <div class="flex gap-2 pt-4">
                            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium transition">💾 Actualizar</button>
                            <a href="{{ route('tipos-dieta.index') }}" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md font-medium transition">✕ Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
