@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="font-semibold text-xl text-gray-800 mb-4">Editar Registro de Dieta</h2>
                <form action="{{ route('registro-dietas.update', $registro_dieta) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Paciente</label>
                            <select name="paciente_id" class="mt-1 block w-full border-gray-300 rounded-md">
                                <option value="">-- Seleccione --</option>
                                @foreach($pacientes as $p)
                                    <option value="{{ $p->id }}" @if(old('paciente_id', $registro_dieta->paciente_id) == $p->id) selected @endif>{{ $p->nombre }} {{ $p->apellido }} ({{ $p->cedula }})</option>
                                @endforeach
                            </select>
                            @error('paciente_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dietas</label>
                            <div class="flex flex-wrap gap-4 mt-1">
                                @php
                                    $dietasSeleccionadas = old('dieta_id', $registro_dieta->dietas->pluck('id')->toArray());
                                @endphp
                                @foreach($dietas as $d)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="dieta_id[]" value="{{ $d->id }}" class="form-checkbox" @if(in_array($d->id, $dietasSeleccionadas)) checked @endif>
                                        <span class="ml-2">{{ $d->nombre }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('dieta_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo de comida</label>
                            <select name="tipo_comida" class="mt-1 block w-full border-gray-300 rounded-md">
                                <option value="">-- Seleccione --</option>
                                <option value="desayuno" @if(old('tipo_comida', $registro_dieta->tipo_comida)=='desayuno') selected @endif>Desayuno</option>
                                <option value="almuerzo" @if(old('tipo_comida', $registro_dieta->tipo_comida)=='almuerzo') selected @endif>Almuerzo</option>
                                <option value="merienda" @if(old('tipo_comida', $registro_dieta->tipo_comida)=='merienda') selected @endif>Merienda</option>
                            </select>
                            @error('tipo_comida')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha</label>
                            <input type="date" name="fecha" value="{{ old('fecha', $registro_dieta->fecha) }}" class="mt-1 block w-full border-gray-300 rounded-md">
                            @error('fecha')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                            <textarea name="observaciones" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('observaciones', $registro_dieta->observaciones) }}</textarea>
                        </div>
                        <div class="pt-4">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Actualizar</button>
                            <a href="{{ route('registro-dietas.index') }}" class="ml-2 text-gray-600">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
