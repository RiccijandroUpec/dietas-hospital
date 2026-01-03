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

                @php
                    $servicioDesdeGrafica = $servicios->firstWhere('id', $servicioId ?? null);
                    $camaDesdeGrafica = $camas->firstWhere('id', $camaId ?? null);
                @endphp
                @if($camaDesdeGrafica)
                    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 text-blue-900 rounded-md">
                        <div class="font-semibold">Asignando desde Camas gráficas</div>
                        <div class="text-sm mt-1 flex gap-3 flex-wrap">
                            <span>Servicio: <strong>{{ $servicioDesdeGrafica->nombre ?? 'N/D' }}</strong></span>
                            <span>Cama: <strong>{{ $camaDesdeGrafica->codigo }}</strong></span>
                            <span class="text-gray-600">Solo se llenan nombre, apellido y edad; la cama se mantiene.</span>
                        </div>
                    </div>
                @endif
                <form action="{{ route('pacientes.store') }}" method="POST" id="paciente-form">
                    @csrf
                    <!-- Input oculto para method spoofing en Laravel (PATCH/PUT) -->
                    <input type="hidden" name="_method" id="form_method" value="POST">


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
                            <input
                                id="cedula_input"
                                type="text"
                                name="cedula"
                                value="{{ old('cedula') }}"
                                placeholder="Ej: 1234567890"
                                class="w-full border-gray-300 rounded-md font-mono"
                                inputmode="numeric"
                                pattern="\d{10}"
                                maxlength="10"
                                required
                            >
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
                                    <option value="{{ $servicio->id }}" @if(old('servicio_id', $servicioId) == $servicio->id) selected @endif>{{ $servicio->nombre }}</option>
                                @endforeach
                            </select>
                            @error('servicio_id')<div class="text-red-600 text-sm mt-1">⚠️ {{ $message }}</div>@enderror
                        </div>

                        <!-- Cama -->
                        <div id="cama_wrapper">
                            <label class="block text-sm font-medium text-gray-700 mb-1">🛏️ Cama</label>
                            <select id="cama_select" name="cama_id" class="w-full border-gray-300 rounded-md" @if($servicioId && !$camaId) required @endif>
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
    const camaIdParam = @json($camaId);
    const servicioIdParam = @json($servicioId);
    const fromCamasGrafica = Boolean(camaIdParam || servicioIdParam);
    const form = document.getElementById('paciente-form');
    const pacienteStoreUrl = @json(route('pacientes.store'));
    const pacienteUpdateBase = @json(url('pacientes'));
    const formMethod = document.getElementById('form_method');
    const servicioHidden = document.createElement('input');
    const camaHidden = document.createElement('input');

    const CEDULA_TOTAL_DIGITS = 10;

    const nombreInput = document.querySelector('input[name="nombre"]');
    const apellidoInput = document.querySelector('input[name="apellido"]');

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

    cedulaInput.addEventListener('input', validarCedula);
    cedulaInput.addEventListener('blur', validarCedula);

    // Listener al submit para verificar estado del formulario
    form.addEventListener('submit', function(e) {
        normalizarNombreApellido();
        if (!validarCedula()) {
            e.preventDefault();
            cedulaInput.focus();
            return;
        }
        console.log('Form submitted - Action:', form.action, 'Method:', formMethod.value, '_method value:', formMethod.value);
        console.log('Form data:', new FormData(form));
    });

    if (nombreInput) nombreInput.addEventListener('blur', normalizarNombreApellido);
    if (apellidoInput) apellidoInput.addEventListener('blur', normalizarNombreApellido);

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

    // Si hay un servicio ya seleccionado (old o desde parámetro), cargar camas iniciales
    const initialServicio = servicioSelect.value;
    const oldCama = '{{ old('cama_id', $camaId ?? '') }}';
    if (initialServicio) {
        loadCamas(initialServicio, oldCama ? parseInt(oldCama) : null);
    }

    // Si venimos de camas gráficas, bloquear cambios de servicio/cama pero enviar valores
    if (fromCamasGrafica) {
        // Solo crear inputs si no existen ya (para evitar duplicados)
        if (!form.querySelector('input[type="hidden"][name="servicio_id"]')) {
            servicioHidden.type = 'hidden';
            servicioHidden.name = 'servicio_id';
            servicioHidden.value = servicioIdParam || '';
            form.appendChild(servicioHidden);
        }

        if (!form.querySelector('input[type="hidden"][name="cama_id"]')) {
            camaHidden.type = 'hidden';
            camaHidden.name = 'cama_id';
            camaHidden.value = camaIdParam || '';
            form.appendChild(camaHidden);
        }

        if (servicioIdParam) {
            servicioSelect.value = servicioIdParam;
            loadCamas(servicioIdParam, camaIdParam);
        }

        servicioSelect.disabled = true;
        camaSelect.disabled = true;
        servicioWrapper.classList.add('opacity-70');
        camaWrapper.classList.add('opacity-70');
    }

    // Verificar cedula (debounce)
    let cedulaTimer = null;
    // URL relativa para evitar problemas con APP_URL o dominios diferentes
    const checkCedulaUrl = '/pacientes/check-cedula';
    
    function checkCedula() {
        const ced = cedulaInput.value.trim();
        if (!ced) {
            cedulaFeedback.textContent = '';
            submitBtn.disabled = false;
            return;
        }

        cedulaFeedback.innerHTML = '<span class="text-blue-600">🔍 Buscando...</span>';

        fetch(`${checkCedulaUrl}?cedula=${encodeURIComponent(ced)}`, {
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(res => {
                console.log('Response status:', res.status);
                console.log('Response headers:', res.headers.get('content-type'));
                
                // Verificar si la respuesta es JSON
                const contentType = res.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    return res.text().then(text => {
                        console.error('Response is not JSON:', text.substring(0, 500));
                        throw new Error('Server returned non-JSON response');
                    });
                }
                
                return res.json();
            })
            .then(data => {
                console.log('Data received:', data);
                if (data.exists) {
                    // Llenar campos automáticamente con los datos del paciente
                    document.querySelector('input[name="nombre"]').value = data.nombre || '';
                    document.querySelector('input[name="apellido"]').value = data.apellido || '';
                    document.querySelector('input[name="edad"]').value = data.edad || '';

                    // Enviar como PATCH al paciente existente (evita error de duplicado)
                    let methodInput = form.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        form.appendChild(methodInput);
                    }
                    methodInput.value = 'PATCH';
                    formMethod.value = 'PATCH';
                    form.action = `${pacienteUpdateBase}/${data.id}`;
                    console.log('Form action cambiado a:', form.action, 'Method:', formMethod.value);
                    
                    // Si venimos de camas gráficas, asegurar estado hospitalizado
                    if (fromCamasGrafica) {
                        document.getElementById('estado_select').value = 'hospitalizado';
                        // Actualizar inputs ocultos para evitar duplicados
                        let existingServicio = form.querySelector('input[type="hidden"][name="servicio_id"]');
                        if (existingServicio) {
                            existingServicio.value = servicioIdParam;
                        }
                        let existingCama = form.querySelector('input[type="hidden"][name="cama_id"]');
                        if (existingCama) {
                            existingCama.value = camaIdParam;
                        }
                    }

                    if (!fromCamasGrafica) {
                        // Solo en flujo normal: estado, condiciones, servicio y cama del paciente existente
                        const estadoSelectElem = document.getElementById('estado_select');
                        estadoSelectElem.value = data.estado || 'hospitalizado';
                        toggleServicioCama();

                        const condiciones = Array.isArray(data.condicion) ? data.condicion : [];
                        document.querySelectorAll('input[name="condicion[]"]').forEach(cb => {
                            cb.checked = condiciones.includes(cb.value);
                        });

                        if (data.servicio_id && estadoSelectElem.value === 'hospitalizado') {
                            servicioSelect.value = data.servicio_id;
                            setTimeout(() => {
                                loadCamas(data.servicio_id, data.cama_id);
                            }, 100);
                        }

                        cedulaFeedback.innerHTML = `<span class="text-green-600 font-semibold">✓ Paciente encontrado - Puedes actualizar los datos y guardar</span>`;
                    } else {
                        // Enviado desde camas gráficas: preservar cama/servicio elegidos allí
                        cedulaFeedback.innerHTML = `<span class="text-green-600 font-semibold">✓ Paciente encontrado - Se cargaron nombre, apellido y edad. La cama se mantiene.</span>`;
                    }
                    submitBtn.disabled = false;
                    submitBtn.textContent = '✓ Actualizar Paciente';
                    submitBtn.classList.remove('bg-blue-300', 'hover:bg-blue-400');
                    submitBtn.classList.add('bg-green-500', 'hover:bg-green-600', 'text-white');
                } else {
                    // Paciente nuevo
                    cedulaFeedback.innerHTML = '<span class="text-blue-600">ℹ️ Cédula disponible - Nuevo paciente</span>';
                    submitBtn.disabled = false;
                    submitBtn.textContent = '✓ Guardar Paciente';
                    submitBtn.classList.remove('bg-green-500', 'hover:bg-green-600', 'text-white');
                    submitBtn.classList.add('bg-blue-300', 'hover:bg-blue-400', 'text-blue-900');

                    // Volver a POST de creación
                    formMethod.value = 'POST';
                    form.action = pacienteStoreUrl;
                    const methodInput = form.querySelector('input[name="_method"]');
                    if (methodInput && methodInput !== formMethod) {
                        methodInput.remove();
                    }
                    console.log('Form action reseteado a:', form.action, 'Method:', formMethod.value);
                }
            })
            .catch((err) => {
                console.error('Error checking cedula:', err);
                cedulaFeedback.innerHTML = '<span class="text-red-600">⚠️ Error al verificar cédula</span>';
                submitBtn.disabled = false;
            });
    }

    cedulaInput.addEventListener('input', function () {
        clearTimeout(cedulaTimer);
        cedulaTimer = setTimeout(checkCedula, 500);
    });
    cedulaInput.addEventListener('blur', checkCedula);
});
</script>
@endsection
