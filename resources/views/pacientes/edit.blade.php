@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="font-semibold text-xl text-gray-800 mb-4">Editar Paciente</h2>

                <form action="{{ route('pacientes.update', $paciente) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre', $paciente->nombre) }}" class="mt-1 block w-full border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Apellido</label>
                            <input type="text" name="apellido" value="{{ old('apellido', $paciente->apellido) }}" class="mt-1 block w-full border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cédula</label>
                            <input
                                id="cedula_input"
                                type="text"
                                name="cedula"
                                value="{{ old('cedula', $paciente->cedula) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md"
                                inputmode="numeric"
                                pattern="\d{10}"
                                maxlength="10"
                                required
                            >
                            <div id="cedula_feedback" class="text-sm mt-1 text-gray-500"></div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estado</label>
                            <select id="estado_select" name="estado" class="mt-1 block w-full border-gray-300 rounded-md">
                                <option value="hospitalizado" @if(old('estado', $paciente->estado) == 'hospitalizado') selected @endif>Hospitalizado</option>
                                <option value="alta" @if(old('estado', $paciente->estado) == 'alta') selected @endif>Alta</option>
                            </select>
                            @error('estado')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Edad</label>
                            <input type="number" name="edad" value="{{ old('edad', $paciente->edad) }}" class="mt-1 block w-full border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Condición</label>
                            @php
                                $saved = old('condicion', isset($paciente->condicion) ? explode(',', $paciente->condicion) : []);
                                $saved = is_array($saved) ? $saved : [$saved];
                            @endphp
                            <div class="mt-2 grid grid-cols-2 gap-3 p-3 bg-gray-50 rounded-md border border-gray-200">
                                <label class="inline-flex items-center p-2 bg-white rounded border border-gray-300 hover:border-blue-500 cursor-pointer transition">
                                    <input type="checkbox" name="condicion[]" value="diabetico" @if(in_array('diabetico', $saved)) checked @endif>
                                    <span class="ml-2 text-sm font-medium">Diabético</span>
                                </label>
                                <label class="inline-flex items-center p-2 bg-white rounded border border-gray-300 hover:border-blue-500 cursor-pointer transition">
                                    <input type="checkbox" name="condicion[]" value="hiposodico" @if(in_array('hiposodico', $saved)) checked @endif>
                                    <span class="ml-2 text-sm font-medium">Hiposódico</span>
                                </label>
                            </div>
                        </div>

                        <div id="servicio_wrapper">
                            <label class="block text-sm font-medium text-gray-700">
                                Servicio <span class="text-red-500" id="servicio_required">*</span>
                            </label>
                            <select id="servicio_select" name="servicio_id" class="mt-1 block w-full border-gray-300 rounded-md">
                                <option value="">-- Seleccione --</option>
                                @foreach($servicios as $servicio)
                                    <option value="{{ $servicio->id }}" @if(old('servicio_id', $paciente->servicio_id) == $servicio->id) selected @endif>{{ $servicio->nombre }}</option>
                                @endforeach
                            </select>
                            @error('servicio_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div id="cama_wrapper">
                            <label class="block text-sm font-medium text-gray-700">Cama</label>
                            <select id="cama_select" name="cama_id" class="mt-1 block w-full border-gray-300 rounded-md" disabled>
                                <option value="">-- Seleccione servicio primero --</option>
                            </select>
                            @error('cama_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Actualizar</button>
                                <a href="{{ route('pacientes.index') }}" class="ml-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-100 px-4 py-2">Cancelar</a>
                        </div>
                    </div>
                </form>

                <div class="mt-4 text-sm text-gray-600">
                    <div>Creado por: {{ optional($paciente->createdBy)->name ?? '–' }}</div>
                    <div>Última actualización por: {{ optional($paciente->updatedBy)->name ?? '–' }}</div>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const estadoSelect = document.getElementById('estado_select');
    const servicioSelect = document.getElementById('servicio_select');
    const camaSelect = document.getElementById('cama_select');
    const servicioWrapper = document.getElementById('servicio_wrapper');
    const camaWrapper = document.getElementById('cama_wrapper');
    const servicioRequired = document.getElementById('servicio_required');
    const currentCama = '{{ old('cama_id', $paciente->cama_id) }}';
    const nombreInput = document.querySelector('input[name="nombre"]');
    const apellidoInput = document.querySelector('input[name="apellido"]');
    const cedulaInput = document.getElementById('cedula_input');
    const cedulaFeedback = document.getElementById('cedula_feedback');

    const CEDULA_TOTAL_DIGITS = 10;

    function toTitleCase(str) {
        return (str || '')
            .split(' ')
            .filter(Boolean)
            .map(w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase())
            .join(' ');
    }

    function normalizarNombreApellido() {
        if (nombreInput) nombreInput.value = toTitleCase(nombreInput.value);
        if (apellidoInput) apellidoInput.value = toTitleCase(apellidoInput.value);
    }

    function validarCedula() {
        if (!cedulaInput) return true;
        const raw = cedulaInput.value || '';
        const soloNumeros = raw.replace(/\D/g, '').slice(0, CEDULA_TOTAL_DIGITS);
        if (soloNumeros !== raw) {
            cedulaInput.value = soloNumeros;
        }

        if (!soloNumeros.length) {
            cedulaFeedback.textContent = 'Ingresa 10 dígitos numéricos.';
            cedulaFeedback.className = 'text-sm mt-1 text-gray-500';
            return false;
        }

        if (soloNumeros.length !== CEDULA_TOTAL_DIGITS) {
            cedulaFeedback.textContent = `Faltan dígitos: ${CEDULA_TOTAL_DIGITS - soloNumeros.length}`;
            cedulaFeedback.className = 'text-sm mt-1 text-red-600';
            return false;
        }

        cedulaFeedback.textContent = 'Formato válido (10 dígitos).';
        cedulaFeedback.className = 'text-sm mt-1 text-green-600';
        return true;
    }

    function toggleServicioCama() {
        const estado = estadoSelect.value;
        if (estado === 'alta') {
            servicioWrapper.style.display = 'none';
            camaWrapper.style.display = 'none';
            servicioSelect.value = '';
            camaSelect.value = '';
            servicioSelect.removeAttribute('required');
        } else {
            servicioWrapper.style.display = 'block';
            camaWrapper.style.display = 'block';
            servicioSelect.setAttribute('required', 'required');
            // Si hay un servicio seleccionado, cargar sus camas
            if (servicioSelect.value) {
                loadCamas(servicioSelect.value, currentCama ? parseInt(currentCama) : null);
            }
        }
    }

    estadoSelect.addEventListener('change', toggleServicioCama);
    toggleServicioCama(); // Ejecutar al cargar

    if (nombreInput) nombreInput.addEventListener('blur', normalizarNombreApellido);
    if (apellidoInput) apellidoInput.addEventListener('blur', normalizarNombreApellido);
    if (cedulaInput) {
        cedulaInput.addEventListener('input', validarCedula);
        cedulaInput.addEventListener('blur', validarCedula);
    }

    async function loadCamas(servicioId, selectedId = null) {
        camaSelect.innerHTML = '';
        if (!servicioId) {
            camaSelect.disabled = true;
            camaSelect.innerHTML = '<option value="">-- Seleccione servicio primero --</option>';
            return;
        }

        camaSelect.disabled = true;
        camaSelect.innerHTML = '<option>Cargando...</option>';

        try {
            const res = await fetch(`/servicios/${servicioId}/camas-available`);
            const camas = await res.json();
            if (camas.length === 0) {
                // Si la cama actual pertenece a este servicio y está asignada al paciente, mostrarla
                if (currentCama) {
                    camaSelect.innerHTML = `<option value="${currentCama}">Cama actual (ocupada por este paciente)</option>`;
                    camaSelect.disabled = false;
                } else {
                    camaSelect.innerHTML = '<option value="">No hay camas disponibles</option>';
                    camaSelect.disabled = true;
                }
                return;
            }

            camaSelect.disabled = false;
            camaSelect.innerHTML = '<option value="">-- Seleccione --</option>';
            camas.forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.id;
                opt.textContent = c.codigo;
                if (selectedId && selectedId == c.id) opt.selected = true;
                camaSelect.appendChild(opt);
            });

            // Si patient had a current cama but it's not in available list (occupied by them), add it
            if (currentCama && !Array.from(camaSelect.options).some(o => o.value == currentCama)) {
                const opt = document.createElement('option');
                opt.value = currentCama;
                opt.textContent = 'Cama actual (asignada)';
                opt.selected = true;
                camaSelect.appendChild(opt);
            }
        } catch (e) {
            camaSelect.innerHTML = '<option value="">Error cargando camas</option>';
            camaSelect.disabled = true;
        }
    }

    servicioSelect.addEventListener('change', function () {
        loadCamas(this.value);
    });

    // Validar al inicio
    normalizarNombreApellido();
    validarCedula();

    // Cargar inicial
    if (servicioSelect.value) {
        loadCamas(servicioSelect.value, currentCama ? parseInt(currentCama) : null);
    }

    // Evitar submit si cédula no es válida
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        normalizarNombreApellido();
        if (!validarCedula()) {
            e.preventDefault();
            cedulaInput?.focus();
        }
    });
});
</script>
@endsection
