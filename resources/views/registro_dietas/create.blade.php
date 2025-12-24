@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="font-semibold text-xl text-gray-800 mb-4">Registrar Dieta a Paciente</h2>
                @if(auth()->check() && (auth()->user()->role === 'nutricionista' || auth()->user()->role === 'enfermero' || auth()->user()->role === 'admin'))
                    <form action="{{ route('registro-dietas.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Buscar paciente</label>
                                <input type="text" id="buscador_paciente" class="mt-1 block w-full border-gray-300 rounded-md" placeholder="Nombre, apellido o cédula">
                                <div id="buscador_paciente_results" class="border border-gray-200 bg-white absolute z-10 w-full"></div>
                                <select id="paciente_select" name="paciente_id" class="hidden">
                                    <option value="">-- Seleccione --</option>
                                    @foreach($pacientes as $p)
                                        <option value="{{ $p->id }}" data-condicion="{{ $p->condicion }}" @if(old('paciente_id') == $p->id) selected @endif>{{ $p->nombre }} {{ $p->apellido }} ({{ $p->cedula }})</option>
                                    @endforeach
                                </select>
                                <div id="condicion_paciente_box" class="mt-1"></div>
                                @error('paciente_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Dietas</label>
                                <div class="flex flex-wrap gap-4 mt-1">
                                    @foreach($dietas as $d)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="dieta_id[]" value="{{ $d->id }}" class="form-checkbox" @if(is_array(old('dieta_id')) && in_array($d->id, old('dieta_id'))) checked @endif>
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
                                    <option value="desayuno" @if(old('tipo_comida')=='desayuno') selected @endif>Desayuno</option>
                                    <option value="almuerzo" @if(old('tipo_comida')=='almuerzo') selected @endif>Almuerzo</option>
                                    <option value="merienda" @if(old('tipo_comida')=='merienda') selected @endif>Merienda</option>
                                </select>
                                @error('tipo_comida')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fecha</label>
                                <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md">
                                @error('fecha')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                                <textarea name="observaciones" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('observaciones') }}</textarea>
                            </div>
                            <div class="pt-4">
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md">Guardar</button>
                                <a href="{{ route('registro-dietas.index') }}" class="ml-2 text-gray-600">Cancelar</a>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Paciente</label>
                            <div class="mt-1 block w-full border-gray-300 rounded-md bg-gray-100 p-2">
                                @if(isset($paciente))
                                    {{ $paciente->nombre }} {{ $paciente->apellido }} ({{ $paciente->cedula }})
                                @else
                                    <span class="text-gray-500">No seleccionado</span>
                                @endif
                            </div>
                            <div id="condicion_paciente_box" class="mt-1">
                                @if(isset($paciente))
                                    <span class="text-sm text-gray-700">Condición: {{ implode(', ', (array) $paciente->condicion) }}</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dietas</label>
                            <div class="flex flex-wrap gap-4 mt-1">
                                @if(isset($dietasSeleccionadas) && count($dietasSeleccionadas))
                                    @foreach($dietasSeleccionadas as $d)
                                        <span class="inline-flex items-center px-2 py-1 bg-gray-200 rounded">{{ $d->nombre }}</span>
                                    @endforeach
                                @else
                                    <span class="text-gray-500">No seleccionadas</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo de comida</label>
                            <div class="mt-1 block w-full border-gray-300 rounded-md bg-gray-100 p-2">
                                @if(isset($tipo_comida))
                                    {{ ucfirst($tipo_comida) }}
                                @else
                                    <span class="text-gray-500">No seleccionado</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha</label>
                            <div class="mt-1 block w-full border-gray-300 rounded-md bg-gray-100 p-2">
                                @if(isset($fecha))
                                    {{ $fecha }}
                                @else
                                    <span class="text-gray-500">No seleccionada</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                            <div class="mt-1 block w-full border-gray-300 rounded-md bg-gray-100 p-2">
                                @if(isset($observaciones) && $observaciones)
                                    {{ $observaciones }}
                                @else
                                    <span class="text-gray-500">Sin observaciones</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
window.PACIENTES_LIST = [
    @foreach($pacientes as $p)
        {id: {{ $p->id }}, nombre: @json($p->nombre), apellido: @json($p->apellido), cedula: @json($p->cedula), condicion: @json($p->condicion)},
    @endforeach
];
window.DIETAS_LIST = [
    @foreach($dietas as $d)
        {id: {{ $d->id }}, nombre: @json($d->nombre)},
    @endforeach
];
</script>
<script src="/js/buscador-paciente.js"></script>
@endsection
