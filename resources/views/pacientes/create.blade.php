@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="font-semibold text-xl text-gray-800 mb-4">Crear Paciente</h2>

                <form action="{{ route('pacientes.store') }}" method="POST">
                    @csrf

                    @if(session('error'))
                        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
                    @endif

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" class="mt-1 block w-full border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Apellido</label>
                            <input type="text" name="apellido" value="{{ old('apellido') }}" class="mt-1 block w-full border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cédula</label>
                            <input id="cedula_input" type="text" name="cedula" value="{{ old('cedula') }}" class="mt-1 block w-full border-gray-300 rounded-md">
                            <div id="cedula_feedback" class="text-sm mt-1"></div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Edad</label>
                            <input type="number" name="edad" value="{{ old('edad') }}" class="mt-1 block w-full border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Condición</label>
                            @php $oldCond = old('condicion', []); @endphp
                            <div class="mt-1 space-x-4">
                                <label><input type="checkbox" name="condicion[]" value="normal" @if(in_array('normal', (array)$oldCond)) checked @endif> Normal</label>
                                <label><input type="checkbox" name="condicion[]" value="diabetico" @if(in_array('diabetico', (array)$oldCond)) checked @endif> Diabético</label>
                                <label><input type="checkbox" name="condicion[]" value="hiposodico" @if(in_array('hiposodico', (array)$oldCond)) checked @endif> Hiposódico</label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Servicio</label>
                            <select id="servicio_select" name="servicio_id" class="mt-1 block w-full border-gray-300 rounded-md">
                                <option value="">-- Seleccione --</option>
                                @foreach($servicios as $servicio)
                                    <option value="{{ $servicio->id }}" @if(old('servicio_id') == $servicio->id) selected @endif>{{ $servicio->nombre }}</option>
                                @endforeach
                            </select>
                            @error('servicio_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cama</label>
                            <select id="cama_select" name="cama_id" class="mt-1 block w-full border-gray-300 rounded-md" disabled>
                                <option value="">-- Seleccione servicio primero --</option>
                            </select>
                            @error('cama_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="pt-4">
                            <button id="submit_btn" type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md">Guardar</button>
                            <a href="{{ route('pacientes.index') }}" class="ml-2 text-gray-600">Cancelar</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const servicioSelect = document.getElementById('servicio_select');
    const camaSelect = document.getElementById('cama_select');
    const cedulaInput = document.getElementById('cedula_input');
    const cedulaFeedback = document.getElementById('cedula_feedback');
    const submitBtn = document.getElementById('submit_btn');

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
