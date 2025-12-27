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
                                $todasLasDietas = \App\Models\Dieta::whereNotNull('tipo_dieta_id')->get();
                                $dietasSeleccionadas = old('dieta_id', $registro_dieta->dietas->pluck('id')->toArray());
                                $hayDietas = $todasLasDietas->count() > 0;
                            @endphp
                            
                            @if($hayDietas)
                                <div class="space-y-4">
                                    @foreach($tipos as $tipo)
                                        @php
                                            $todasDietasTipo = \App\Models\Dieta::where('tipo_dieta_id', $tipo->id)->get();
                                        @endphp
                                        
                                        @if($todasDietasTipo->count() > 0)
                                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                                <h3 class="font-semibold text-sm text-gray-800 mb-3">
                                                    <span class="bg-blue-100 text-blue-800 rounded px-2 py-1 inline-block">{{ $tipo->nombre }}</span>
                                                </h3>
                                                
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                    @foreach($todasDietasTipo as $dieta)
                                                        <label class="inline-flex items-start p-3 bg-white rounded border border-gray-300 hover:border-blue-500 hover:bg-blue-50 cursor-pointer transition">
                                                            <input type="checkbox" name="dieta_id[]" value="{{ $dieta->id }}" class="form-checkbox text-blue-600 mt-1" @if(in_array($dieta->id, $dietasSeleccionadas)) checked @endif>
                                                            <div class="ml-2">
                                                                <span class="block text-sm font-medium text-gray-700">{{ $dieta->nombre }}</span>
                                                                @if($dieta->subtipos->count() > 0)
                                                                    <span class="block text-xs text-gray-500">{{ $dieta->subtipos->pluck('nombre')->join(', ') }}</span>
                                                                @endif
                                                            </div>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-gray-500 p-3 border border-gray-200 rounded bg-yellow-50">
                                    <p class="text-sm">⚠️ No hay dietas disponibles. Primero debes crear tipos, subtipos y dietas.</p>
                                </div>
                            @endif
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

                        <!-- Presentación -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">🧁 Presentación</label>
                            <div class="mt-1 flex items-center gap-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="vajilla" value="normal" class="form-radio text-indigo-600" @checked(old('vajilla', $registro_dieta->vajilla)==='normal')>
                                    <span class="ml-2 text-sm text-gray-700">Vajilla normal</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="vajilla" value="descartable" class="form-radio text-indigo-600" @checked(old('vajilla', $registro_dieta->vajilla)==='descartable')>
                                    <span class="ml-2 text-sm text-gray-700">Descartable</span>
                                </label>
                            </div>
                            @error('vajilla')<div class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</div>@enderror
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

                        <!-- Dieta Tardía -->
                        <div>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="es_tardia" value="1" class="form-checkbox h-5 w-5 text-red-600 rounded border-gray-300" @checked(old('es_tardia', $registro_dieta->es_tardia))>
                                <span class="ml-2 text-sm font-medium text-gray-700">🔴 Marcar como dieta tardía</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-1 ml-7">Para pacientes que ingresan después del registro inicial</p>
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

<script>
// Solo permitir un checkbox seleccionado a la vez
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[name="dieta_id[]"]');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                // Deseleccionar todos los demás checkboxes
                checkboxes.forEach(cb => {
                    if (cb !== this) {
                        cb.checked = false;
                    }
                });
            }
        });
    });
});
</script>
@endsection
