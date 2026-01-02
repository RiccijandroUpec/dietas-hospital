@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="font-semibold text-2xl text-gray-800 mb-2">👤 Crear Nuevo Paciente</h2>
                <p class="text-gray-600 text-sm mb-6">Registro de un nuevo paciente en el sistema</p>

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md flex items-start">
                        <span class="mr-3">⚠️</span>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <form action="{{ route('pacientes.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nombre -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">👤 Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Ej: Juan" class="w-full border-gray-300 rounded-md" required>
                            @error('nombre')<div class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</div>@enderror
                        </div>

                        <!-- Apellido -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">👤 Apellido</label>
                            <input type="text" name="apellido" value="{{ old('apellido') }}" placeholder="Ej: Pérez" class="w-full border-gray-300 rounded-md" required>
                            @error('apellido')<div class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</div>@enderror
                        </div>

                        <!-- Cédula -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">🆔 Cédula</label>
                            <input id="cedula_input" type="text" name="cedula" value="{{ old('cedula') }}" placeholder="Ej: 12345678" class="w-full border-gray-300 rounded-md font-mono" required>
                            <div id="cedula_feedback" class="text-sm mt-1"></div>
                            @error('cedula')<div class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</div>@enderror
                        </div>

                        <!-- Estado -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">🏥 Estado</label>
                            <select id="estado_select" name="estado" class="w-full border-gray-300 rounded-md">
                                <option value="hospitalizado" @if(old('estado', 'hospitalizado') == 'hospitalizado') selected @endif>Hospitalizado</option>
                                <option value="alta" @if(old('estado') == 'alta') selected @endif>Alta</option>
                            </select>
                            @error('estado')<div class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</div>@enderror
                        </div>

                        <!-- Edad -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">🎂 Edad</label>
                            <input type="number" name="edad" value="{{ old('edad') }}" placeholder="Ej: 45" class="w-full border-gray-300 rounded-md" min="0" max="150">
                            @error('edad')<div class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</div>@enderror
                        </div>

                        <!-- Servicio -->
                        <div id="servicio_wrapper">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                🏢 Servicio <span class="text-red-500" id="servicio_required">*</span>
                            </label>
                            <select id="servicio_select" name="servicio_id" class="w-full border-gray-300 rounded-md" required>
                                <option value="">-- Seleccione --</option>
                                @foreach($servicios as $servicio)
                                    <option value="{{ $servicio->id }}" @if(old('servicio_id') == $servicio->id) selected @endif>{{ $servicio->nombre }}</option>
                                @endforeach
                            </select>
                            @error('servicio_id')<div class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</div>@enderror
                        </div>

                        <!-- Cama -->
                        <div id="cama_wrapper">
                            <label class="block text-sm font-medium text-gray-700 mb-1">🛏️ Cama</label>
                            <select id="cama_select" name="cama_id" class="w-full border-gray-300 rounded-md" disabled>
                                <option value="">-- Seleccione servicio primero --</option>
                            </select>
                            @error('cama_id')<div class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</div>@enderror
                        </div>

                        <!-- Condición -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">⚕️ Condición Médica</label>
                            @php $oldCond = old('condicion', []); @endphp
                            <div class="grid grid-cols-3 gap-3 p-3 bg-gray-50 rounded-md border border-gray-200">
                                <label class="inline-flex items-center p-2 bg-white rounded border border-gray-300 hover:border-blue-500 cursor-pointer transition">
                                    <input type="checkbox" name="condicion[]" value="normal" @if(in_array('normal', (array)$oldCond)) checked @endif>
                                    <span class="ml-2 text-sm font-medium">Normal</span>
                                </label>
                                <label class="inline-flex items-center p-2 bg-white rounded border border-gray-300 hover:border-blue-500 cursor-pointer transition">
                                    <input type="checkbox" name="condicion[]" value="diabetico" @if(in_array('diabetico', (array)$oldCond)) checked @endif>
                                    <span class="ml-2 text-sm font-medium">Diabético</span>
                                </label>
                                <label class="inline-flex items-center p-2 bg-white rounded border border-gray-300 hover:border-blue-500 cursor-pointer transition">
                                    <input type="checkbox" name="condicion[]" value="hiposodico" @if(in_array('hiposodico', (array)$oldCond)) checked @endif>
                                    <span class="ml-2 text-sm font-medium">Hiposódico</span>
                                </label>
                            </div>
                            @error('condicion')<div class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="flex gap-2 pt-6">
                        <button id="submit_btn" type="submit" class="px-6 py-2 bg-blue-300 text-blue-900 rounded-md font-semibold hover:bg-blue-400 transition shadow">✓ Guardar Paciente</button>
                        <a href="{{ route('pacientes.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md font-medium hover:bg-gray-100 transition">✕ Cancelar</a>
                    </div>
                </form>

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
    const cedulaInput = document.getElementById('cedula_input');
    const cedulaFeedback = document.getElementById('cedula_feedback');
    const submitBtn = document.getElementById('submit_btn');

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
            const oldCama = '{{ old('cama_id') }}';
            if (servicioSelect.value) {
                loadCamas(servicioSelect.value, oldCama ? parseInt(oldCama) : null);
            }
        }
    }

    estadoSelect.addEventListener('change', toggleServicioCama);
    toggleServicioCama(); // Ejecutar al cargar

    async function loadCamas(servicioId, selectedId = null) {
        camaSelect.innerHTML = '';
        if (!servicioId) {
            camaSelect.disabled = true;
            camaSelect.innerHTML = '<option value="">-- Seleccione servicio primero --</option>';
            return;
        }

        // Si el servicio es Diálisis, deshabilitar cama
        const selectedOption = servicioSelect.options[servicioSelect.selectedIndex];
        if (selectedOption && selectedOption.text.trim().toLowerCase() === 'diálisis') {
            camaSelect.disabled = true;
            camaSelect.innerHTML = '<option value="">No aplica para Diálisis</option>';
            return;
        }

        camaSelect.disabled = true;
        camaSelect.innerHTML = '<option>Cargando...</option>';

        try {
            const res = await fetch(`/servicios/${servicioId}/camas-available`);
            const camas = await res.json();
            if (camas.length === 0) {
                camaSelect.innerHTML = '<option value="">No hay camas disponibles</option>';
                camaSelect.disabled = true;
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
        } catch (e) {
            camaSelect.innerHTML = '<option value="">Error cargando camas</option>';
            camaSelect.disabled = true;
        }
    }

    servicioSelect.addEventListener('change', function () {
        loadCamas(this.value);
    });

    // Si hay un servicio ya seleccionado (old), cargar camas iniciales
    const initialServicio = servicioSelect.value;
    const oldCama = '{{ old('cama_id') }}';
    if (initialServicio) {
        loadCamas(initialServicio, oldCama ? parseInt(oldCama) : null);
    }

    // Verificar cedula (debounce)
    let cedulaTimer = null;
    function checkCedula() {
        const ced = cedulaInput.value.trim();
        if (!ced) {
            cedulaFeedback.textContent = '';
            submitBtn.disabled = false;
            return;
        }

        fetch(`/pacientes/check-cedula?cedula=${encodeURIComponent(ced)}`)
            .then(res => res.json())
            .then(data => {
                if (data.exists) {
                    cedulaFeedback.innerHTML = `<span class="text-red-600">Ya existe un paciente con esa cédula.</span> <a class="text-blue-600 underline" href="${data.edit_url}">Editar</a>`;
                    submitBtn.disabled = true;
                } else {
                    cedulaFeedback.textContent = '';
                    submitBtn.disabled = false;
                }
            })
            .catch(() => {
                cedulaFeedback.textContent = '';
                submitBtn.disabled = false;
            });
    }

    cedulaInput.addEventListener('input', function () {
        submitBtn.disabled = true; // prevent quick submit
        clearTimeout(cedulaTimer);
        cedulaTimer = setTimeout(checkCedula, 500);
    });
    cedulaInput.addEventListener('blur', checkCedula);
});
</script>
@endsection
