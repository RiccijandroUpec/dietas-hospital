@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="font-semibold text-2xl text-gray-800 mb-2">📝 Registrar Dieta a Paciente</h2>
                <p class="text-gray-600 text-sm mb-6">Solo pacientes hospitalizados pueden tener registros de dieta</p>

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md flex items-start">
                        <span class="mr-3">⚠️</span>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if(auth()->check() && (auth()->user()->role === 'nutricionista' || auth()->user()->role === 'enfermero' || auth()->user()->role === 'admin'))
                    @if($pacientes->count() > 0)
                        <form action="{{ route('registro-dietas.store') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">🔍 Buscar Paciente (Hospitalizado)</label>
                                    <input type="text" id="buscador_paciente" placeholder="Nombre, apellido o cédula" class="mt-1 block w-full border-gray-300 rounded-md">
                                    <div id="buscador_paciente_results" class="border border-gray-200 bg-white absolute z-10 w-96 max-h-64 overflow-y-auto rounded-md shadow-lg"></div>
                                    <select id="paciente_select" name="paciente_id" class="hidden">
                                        <option value="">-- Seleccione --</option>
                                        @foreach($pacientes as $p)
                                            <option value="{{ $p->id }}" data-condicion="{{ $p->condicion }}" data-estado="{{ $p->estado }}" @if(old('paciente_id') == $p->id) selected @endif>{{ $p->nombre }} {{ $p->apellido }} ({{ $p->cedula }})</option>
                                        @endforeach
                                    </select>
                                    <div id="condicion_paciente_box" class="mt-2"></div>
                                    @error('paciente_id')<div class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</div>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">🥗 Dietas</label>
                                    @if($dietas->count() > 0)
                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 p-3 bg-gray-50 rounded-md border border-gray-200">
                                            @foreach($dietas as $d)
                                                <label class="inline-flex items-center p-2 bg-white rounded border border-gray-300 hover:border-blue-500 hover:bg-blue-50 cursor-pointer transition">
                                                    <input type="checkbox" name="dieta_id[]" value="{{ $d->id }}" class="form-checkbox text-blue-600" @if(is_array(old('dieta_id')) && in_array($d->id, old('dieta_id'))) checked @endif>
                                                    <span class="ml-2 text-sm font-medium text-gray-700">{{ $d->nombre }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-gray-500 p-3">No hay dietas disponibles</div>
                                    @endif
                                    @error('dieta_id')<div class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</div>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">🍽️ Tipo de Comida</label>
                                    <select name="tipo_comida" class="mt-1 block w-full border-gray-300 rounded-md">
                                        <option value="">-- Seleccione --</option>
                                        <option value="desayuno" @if(old('tipo_comida') == 'desayuno') selected @endif>Desayuno</option>
                                        <option value="almuerzo" @if(old('tipo_comida') == 'almuerzo') selected @endif>Almuerzo</option>
                                        <option value="merienda" @if(old('tipo_comida') == 'merienda') selected @endif>Merienda</option>
                                    </select>
                                    @error('tipo_comida')<div class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</div>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">📅 Fecha</label>
                                    <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md">
                                    @error('fecha')<div class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</div>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">📝 Observaciones</label>
                                    <textarea name="observaciones" placeholder="Agregar notas o comentarios..." class="mt-1 block w-full border-gray-300 rounded-md h-24" maxlength="500">{{ old('observaciones') }}</textarea>
                                    <div class="text-xs text-gray-500 mt-1">Máximo 500 caracteres</div>
                                </div>

                                <div class="flex gap-2 pt-4">
                                    <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium transition">✓ Guardar Registro</button>
                                    <a href="{{ route('registro-dietas.index') }}" class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md font-medium transition">✕ Cancelar</a>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                            <div class="text-5xl mb-4">🏥</div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">No hay pacientes hospitalizados</h3>
                            <p class="text-gray-600 mb-4">No existen pacientes con estado "Hospitalizado" para registrar dietas.</p>
                            <a href="{{ route('pacientes.index') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                Ver Pacientes
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <div class="text-5xl mb-4">🔒</div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Acceso Denegado</h3>
                        <p class="text-gray-600">No tienes permisos para registrar dietas. Solo nutricionistas, enfermeros y administradores pueden hacerlo.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
window.PACIENTES_LIST = [
    @foreach($pacientes as $p)
        {id: {{ $p->id }}, nombre: @json($p->nombre), apellido: @json($p->apellido), cedula: @json($p->cedula), condicion: @json($p->condicion), estado: @json($p->estado)},
    @endforeach
];
window.DIETAS_LIST = [
    @foreach($dietas as $d)
        {id: {{ $d->id }}, nombre: @json($d->nombre)},
    @endforeach
];

// Preseleccionar paciente si viene en URL
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const pacienteId = urlParams.get('paciente_id');
    
    if (pacienteId) {
        const selectPaciente = document.getElementById('paciente_select');
        const buscadorInput = document.getElementById('buscador_paciente');
        
        if (selectPaciente && buscadorInput) {
            // Seleccionar en el select oculto
            selectPaciente.value = pacienteId;
            
            // Buscar el paciente en la lista
            const paciente = window.PACIENTES_LIST.find(p => p.id == pacienteId);
            
            if (paciente) {
                // Mostrar en el input de búsqueda
                buscadorInput.value = `${paciente.nombre} ${paciente.apellido} (${paciente.cedula})`;
                
                // Mostrar condición si existe
                const condicionBox = document.getElementById('condicion_paciente_box');
                if (condicionBox && paciente.condicion) {
                    const condiciones = paciente.condicion.split(',');
                    const labels = {
                        'normal': 'Normal',
                        'diabetico': 'Diabético',
                        'hiposodico': 'Hiposódico'
                    };
                    const badges = condiciones.map(c => {
                        const label = labels[c.trim()] || c;
                        return `<span class="inline-block bg-yellow-100 text-yellow-800 rounded-full px-3 py-1 text-xs font-semibold mr-2">${label}</span>`;
                    }).join('');
                    condicionBox.innerHTML = `<div class="text-sm text-gray-600"><strong>Condición:</strong> ${badges}</div>`;
                }
            }
        }
    }
});
</script>
<script src="/js/buscador-paciente.js"></script>
@endsection
