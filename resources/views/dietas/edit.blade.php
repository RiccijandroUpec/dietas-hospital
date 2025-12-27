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
                            <label class="block text-sm font-medium text-gray-700">🏷️ Tipo de Dieta</label>
                            <select name="tipo_dieta_id" class="mt-1 block w-full border-gray-300 rounded-md" id="tipo_dieta_id">
                                <option value="">-- Seleccione un tipo (opcional) --</option>
                                @foreach($tipos as $tipo)
                                    <option value="{{ $tipo->id }}" {{ old('tipo_dieta_id', $dieta->tipo_dieta_id) == $tipo->id ? 'selected' : '' }}>{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                            @error('tipo_dieta_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">🔖 Subtipos de Dieta <span class="text-xs text-gray-500">(opcional, múltiples)</span></label>
                            <select name="subtipo_dieta_id[]" class="mt-1 block w-full border-gray-300 rounded-md" id="subtipo_dieta_id" multiple>
                                @foreach($subtipos as $subtipo)
                                    <option value="{{ $subtipo->id }}" {{ (is_array(old('subtipo_dieta_id')) && in_array($subtipo->id, old('subtipo_dieta_id'))) || $dieta->subtipos->contains($subtipo->id) ? 'selected' : '' }}>
                                        {{ $subtipo->nombre }} @if($subtipo->tipo) ({{ $subtipo->tipo->nombre }}) @endif
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Mantén Ctrl/Cmd presionado para seleccionar múltiples</p>
                            @error('subtipo_dieta_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre <span class="text-red-500">*</span></label>
                            <input type="text" name="nombre" value="{{ old('nombre', $dieta->nombre) }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
                            @error('nombre')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea name="descripcion" class="mt-1 block w-full border-gray-300 rounded-md" rows="5">{{ old('descripcion', $dieta->descripcion) }}</textarea>
                            @error('descripcion')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="pt-4 flex gap-2">
                            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium transition">💾 Actualizar</button>
                            <a href="{{ route('dietas.index') }}" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md font-medium transition">✕ Cancelar</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
