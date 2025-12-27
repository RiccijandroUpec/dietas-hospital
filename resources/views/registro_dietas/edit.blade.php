@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="font-semibold text-2xl text-gray-800 mb-2">✏️ Editar Registro de Dieta</h2>
                <p class="text-gray-600 text-sm mb-6">Actualizar información del registro dietético</p>

                <form action="{{ route('registro-dietas.update', $registro_dieta) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Paciente -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">👤 Paciente</label>
                            <select name="paciente_id" class="w-full border-gray-300 rounded-md" required>
                                <option value="">-- Seleccione --</option>
                                @foreach($pacientes as $p)
                                    <option value="{{ $p->id }}" @if(old('paciente_id', $registro_dieta->paciente_id) == $p->id) selected @endif>{{ $p->nombre }} {{ $p->apellido }} ({{ $p->cedula }})</option>
                                @endforeach
                            </select>
                            @error('paciente_id')<div class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</div>@enderror
                        </div>

                        <!-- Dietas -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">🥗 Dietas</label>
                            @php
                                $dietasSeleccionadas = old('dieta_id', $registro_dieta->dietas->pluck('id')->toArray());
                            @endphp
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 p-3 bg-gray-50 rounded-md border border-gray-200">
                                @foreach($dietas as $d)
                                    <label class="inline-flex items-center p-2 bg-white rounded border border-gray-300 hover:border-blue-500 hover:bg-blue-50 cursor-pointer transition">
                                        <input type="checkbox" name="dieta_id[]" value="{{ $d->id }}" class="form-checkbox" @if(in_array($d->id, $dietasSeleccionadas)) checked @endif>
                                        <span class="ml-2 text-sm font-medium">{{ $d->nombre }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('dieta_id')<div class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</div>@enderror
                        </div>

                        <!-- Tipo de comida -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">🍽️ Tipo de Comida</label>
                            <select name="tipo_comida" class="w-full border-gray-300 rounded-md" required>
                                <option value="">-- Seleccione --</option>
                                <option value="desayuno" @if(old('tipo_comida', $registro_dieta->tipo_comida) == 'desayuno') selected @endif>Desayuno</option>
                                <option value="almuerzo" @if(old('tipo_comida', $registro_dieta->tipo_comida) == 'almuerzo') selected @endif>Almuerzo</option>
                                <option value="merienda" @if(old('tipo_comida', $registro_dieta->tipo_comida) == 'merienda') selected @endif>Merienda</option>
                            </select>
                            @error('tipo_comida')<div class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</div>@enderror
                        </div>

                        <!-- Fecha -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">📅 Fecha</label>
                            <input type="date" name="fecha" value="{{ old('fecha', $registro_dieta->fecha) }}" class="w-full border-gray-300 rounded-md" required>
                            @error('fecha')<div class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</div>@enderror
                        </div>

                        <!-- Observaciones -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">📝 Observaciones</label>
                            <textarea name="observaciones" placeholder="Agregar notas..." class="w-full border-gray-300 rounded-md h-24" maxlength="500">{{ old('observaciones', $registro_dieta->observaciones) }}</textarea>
                            <div class="text-xs text-gray-500 mt-1">Máximo 500 caracteres</div>
                            @error('observaciones')<div class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</div>@enderror
                        </div>

                        <div class="flex gap-2 pt-4">
                            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium transition">💾 Actualizar</button>
                            <a href="{{ route('registro-dietas.index') }}" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md font-medium transition">✕ Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
