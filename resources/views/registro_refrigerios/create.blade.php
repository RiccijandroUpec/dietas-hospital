@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">➕ Registrar Refrigerio</h1>
                <p class="text-gray-600 mt-1">Asigna un refrigerio a un paciente hospitalizado</p>
            </div>
            <a href="{{ route('registro-refrigerios.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg">
                Volver
            </a>
        </div>

        @php
            $now = now();
            $currentTime = $now->format('H:i');
            $schedules = \App\Models\RegistrationSchedule::all();
            $isAllowed = false;
            
            foreach ($schedules as $schedule) {
                if ($schedule->isCurrentTimeAllowed()) {
                    $isAllowed = true;
                    break;
                }
            }
        @endphp

        @if(!$isAllowed)
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-md">
                <div class="flex items-start gap-3">
                    <span class="text-2xl">⛔</span>
                    <div>
                        <h3 class="font-bold text-red-900 text-lg">Fuera de Horario de Registro</h3>
                        <p class="text-red-800 mt-1">
                            No puedes registrar refrigerios en este momento. El registro está disponible solo durante los horarios configurados.
                        </p>
                        <div class="mt-3 p-3 bg-white rounded border border-red-200">
                            <p class="text-sm text-gray-600 font-semibold">⏰ Horarios disponibles:</p>
                            @php
                                $schedules = \App\Models\RegistrationSchedule::orderBy('start_time')->get();
                            @endphp
                            @if($schedules->count() > 0)
                                <ul class="mt-2 space-y-1">
                                    @foreach($schedules as $schedule)
                                        <li class="text-sm text-gray-700">
                                            <span class="font-medium">{{ ucfirst($schedule->meal_type) }}:</span>
                                            {{ \Carbon\Carbon::createFromFormat('H:i', $schedule->start_time)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::createFromFormat('H:i', $schedule->end_time)->format('H:i') }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-sm text-gray-600 mt-2">No hay horarios configurados</p>
                            @endif
                        </div>
                        <p class="text-red-700 text-sm mt-3 font-semibold">💡 Por favor, vuelve durante el horario permitido</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md"{{ !$isAllowed ? ' style="opacity: 0.5; pointer-events: none;"' : '' }}>
            <div class="p-6">
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg flex items-start gap-3">
                        <span class="text-2xl">❌</span>
                        <div>
                            <p class="font-semibold">Error</p>
                            <p class="text-sm mt-1">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @if($pacientes->isEmpty())
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded">No hay pacientes hospitalizados disponibles.</div>
                @else
                <form action="{{ route('registro-refrigerios.store') }}" method="POST" {{ !$isAllowed ? 'onsubmit="return false;"' : '' }}>
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Paciente</label>
                        <div class="relative">
                            <input type="text" id="buscador_paciente" class="w-full border rounded-lg px-3 py-2" placeholder="Nombre, apellido o cédula" {{ !$isAllowed ? 'disabled' : '' }}>
                            <div id="buscador_paciente_results" class="absolute z-10 mt-1 w-full bg-white border rounded-lg shadow max-h-60 overflow-auto"></div>
                            <select id="paciente_select" name="paciente_id" class="hidden">
                                <option value="">-- Seleccione --</option>
                                @foreach($pacientes as $p)
                                    <option value="{{ $p->id }}" data-condicion="{{ $p->condicion }}" data-estado="{{ $p->estado }}" @if(old('paciente_id') == $p->id) selected @endif>{{ $p->nombre }} {{ $p->apellido }} ({{ $p->cedula }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="condicion_paciente_box" class="mt-2"></div>
                        @error('paciente_id')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">🥤 Refrigerios</label>
                        <div class="space-y-2 bg-gray-50 p-4 rounded-lg border">
                            @if($refrigerios->isEmpty())
                                <p class="text-gray-500 text-sm">No hay refrigerios disponibles</p>
                            @else
                                @foreach($refrigerios as $r)
                                    <label class="flex items-center cursor-pointer hover:bg-white p-2 rounded transition">
                                        <input type="checkbox" name="refrigerio_ids[]" value="{{ $r->id }}" 
                                            class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500"
                                            @if(in_array($r->id, (array)old('refrigerio_ids', []))) checked @endif {{ !$isAllowed ? 'disabled' : '' }}>
                                        <span class="ml-3 text-gray-900">{{ $r->nombre }}</span>
                                        @if($r->descripcion)
                                            <span class="ml-2 text-xs text-gray-500">({{ Str::limit($r->descripcion, 50) }})</span>
                                        @endif
                                    </label>
                                @endforeach
                            @endif
                        </div>
                        @error('refrigerio_ids')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        @error('refrigerio_ids.*')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Fecha</label>
                            <input type="date" name="fecha" value="{{ old('fecha', now()->toDateString()) }}" class="w-full border rounded-lg px-3 py-2" required>
                            @error('fecha')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Momentos del día</label>
                            <div class="space-y-2 bg-gray-50 p-3 rounded-lg border">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="momentos[]" value="mañana" 
                                        class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500"
                                        @if(in_array('mañana', (array)old('momentos', []))) checked @endif>
                                    <span class="ml-3 text-gray-900 text-sm">🌅 Mañana</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="momentos[]" value="tarde" 
                                        class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500"
                                        @if(in_array('tarde', (array)old('momentos', []))) checked @endif>
                                    <span class="ml-3 text-gray-900 text-sm">🌞 Tarde</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="momentos[]" value="noche" 
                                        class="w-4 h-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500"
                                        @if(in_array('noche', (array)old('momentos', []))) checked @endif>
                                    <span class="ml-3 text-gray-900 text-sm">🌙 Noche</span>
                                </label>
                            </div>
                            @error('momentos')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            @error('momentos.*')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Observación (opcional)</label>
                        <textarea name="observacion" rows="3" class="w-full border rounded-lg px-3 py-2" placeholder="Notas adicionales">{{ old('observacion') }}</textarea>
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('registro-refrigerios.index') }}" class="px-4 py-2 bg-gray-100 rounded">Cancelar</a>
                        <button class="px-6 py-2 bg-orange-600 text-white rounded-lg">Registrar</button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Construir lista local de pacientes para el buscador (como en registro-dietas)
window.PACIENTES_LIST = [
    @foreach($pacientes as $p)
        {id: {{ $p->id }}, nombre: @json($p->nombre), apellido: @json($p->apellido), cedula: @json($p->cedula), condicion: @json($p->condicion), estado: @json($p->estado)},
    @endforeach
];

document.addEventListener('DOMContentLoaded', function () {
    const pacientes = window.PACIENTES_LIST || [];
    const input = document.getElementById('buscador_paciente');
    const results = document.getElementById('buscador_paciente_results');
    const selectPaciente = document.getElementById('paciente_select');
    const condicionBox = document.getElementById('condicion_paciente_box');

    // Preseleccionar paciente si viene en URL
    const urlParams = new URLSearchParams(window.location.search);
    const pacienteId = urlParams.get('paciente_id');
    
    if (pacienteId) {
        const paciente = pacientes.find(p => p.id == pacienteId);
        
        if (paciente && selectPaciente && input) {
            // Seleccionar en el select oculto
            selectPaciente.value = pacienteId;
            
            // Mostrar en el input de búsqueda
            input.value = `${paciente.nombre} ${paciente.apellido} (${paciente.cedula || '—'})`;
            
            // Mostrar condición
            if (condicionBox && paciente.condicion) {
                const condStr = (paciente.condicion || '').toLowerCase();
                const condList = condStr ? condStr.split(',').map(c => c.trim()).filter(Boolean) : [];
                const pretty = condList.length ? condList.map(c => {
                    if (c === 'diabetico') return 'Diabético';
                    if (c === 'hiposodico') return 'Hiposódico';
                    if (c === 'normal') return 'Normal';
                    return c;
                }).join(', ') : '—';
                condicionBox.innerHTML = `<span class="text-xs text-gray-700">Condición: <b>${pretty}</b></span>`;
            }
        }
    }

    input.addEventListener('input', function () {
        const val = input.value.trim().toLowerCase();
        results.innerHTML = '';
        if (!val) return;
        const filtrados = pacientes.filter(p =>
            (p.nombre || '').toLowerCase().includes(val) ||
            (p.apellido || '').toLowerCase().includes(val) ||
            (p.cedula || '').toLowerCase().includes(val)
        );
        filtrados.slice(0, 10).forEach(p => {
            const div = document.createElement('div');
            div.className = 'cursor-pointer px-3 py-2 hover:bg-gray-100';
            div.textContent = `${p.nombre} ${p.apellido} (${p.cedula || '—'})`;
            div.onclick = function () {
                selectPaciente.value = p.id;
                input.value = `${p.nombre} ${p.apellido} (${p.cedula || '—'})`;
                results.innerHTML = '';
                // Mostrar condición
                const condStr = (p.condicion || '').toLowerCase();
                const condList = condStr ? condStr.split(',').map(c => c.trim()).filter(Boolean) : [];
                const pretty = condList.length ? condList.map(c => {
                    if (c === 'diabetico') return 'Diabético';
                    if (c === 'hiposodico') return 'Hiposódico';
                    if (c === 'normal') return 'Normal';
                    return c;
                }).join(', ') : '—';
                condicionBox.innerHTML = `<span class="text-xs text-gray-700">Condición: <b>${pretty}</b></span>`;
            };
            results.appendChild(div);
        });
    });
});
</script>
@endpush