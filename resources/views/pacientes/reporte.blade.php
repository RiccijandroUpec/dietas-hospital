@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="font-semibold text-2xl text-gray-800">Reporte de Pacientes</h2>
                        <p class="text-sm text-gray-600">Busca por nombre, cédula, estado o servicio</p>
                    </div>
                    <div class="text-sm text-gray-500">Resultados: <span class="font-semibold text-gray-800">{{ $pacientes->total() }}</span></div>
                </div>

                <!-- Filtros -->
                <form method="GET" action="{{ route('pacientes.reporte') }}" class="mb-6 bg-gray-50 border border-gray-200 rounded-xl p-4 space-y-4" id="filtrosForm">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-800">Buscar</label>
                            <div class="mt-1 relative">
                                <input
                                    type="text"
                                    id="buscarInput"
                                    name="q"
                                    value="{{ request('q') }}"
                                    placeholder="Nombre, apellido o cédula"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                                </svg>
                                <div id="searchSpinner" class="hidden absolute right-3 top-2.5">
                                    <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-800">Estado</label>
                            <select name="estado" id="estadoSelect" class="mt-1 block w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Todos</option>
                                <option value="hospitalizado" @selected(request('estado') == 'hospitalizado')>Hospitalizado</option>
                                <option value="alta" @selected(request('estado') == 'alta')>Alta</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-800">Servicio</label>
                            <select name="servicio_id" id="servicioSelect" class="mt-1 block w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Todos</option>
                                @foreach($servicios as $servicio)
                                    <option value="{{ $servicio->id }}" @selected(request('servicio_id') == $servicio->id)>{{ $servicio->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button type="submit" class="px-5 py-2.5 bg-blue-300 text-blue-900 rounded-lg hover:bg-blue-400 transition font-semibold shadow-md">Aplicar filtros</button>
                        <a href="{{ route('pacientes.reporte') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition">Limpiar</a>
                        @if(request()->hasAny(['q','estado','servicio_id']))
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-sm">
                                Filtros activos
                            </span>
                        @endif
                    </div>
                </form>

                <!-- Resumen de totales -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <div class="text-sm font-medium text-blue-600">Hospitalizados</div>
                        <div class="text-2xl font-bold text-blue-900">{{ $totales['hospitalizado'] }}</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <div class="text-sm font-medium text-green-600">Alta</div>
                        <div class="text-2xl font-bold text-green-900">{{ $totales['alta'] }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="text-sm font-medium text-gray-600">Total</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $totales['hospitalizado'] + $totales['alta'] }}</div>
                    </div>
                </div>

                <!-- Tabla de pacientes -->
                <div class="w-full overflow-auto">
                    <table class="table-auto w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Cédula</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Edad</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Condición</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Servicio</th>
                                <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Cama</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="pacientesReporteTbody">
                            @forelse($pacientes as $p)
                                <tr>
                                    <td class="px-3 py-2">{{ $p->nombre }} {{ $p->apellido }}</td>
                                    <td class="px-3 py-2">{{ $p->cedula }}</td>
                                    <td class="px-3 py-2">
                                        @if($p->estado === 'hospitalizado')
                                            <span class="inline-block bg-blue-100 text-blue-800 rounded px-2 py-1 text-xs font-semibold">Hospitalizado</span>
                                        @else
                                            <span class="inline-block bg-green-100 text-green-800 rounded px-2 py-1 text-xs font-semibold">Alta</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2">{{ $p->edad ?? '–' }}</td>
                                    <td class="px-3 py-2">
                                        @php
                                            $cond = $p->condicion;
                                            $labels = [
                                                'normal' => 'Normal',
                                                'diabetico' => 'Diabético',
                                                'hiposodico' => 'Hiposódico',
                                            ];
                                            $condArr = $cond ? explode(',', $cond) : [];
                                        @endphp
                                        @if($condArr && $condArr[0] !== '')
                                            @foreach($condArr as $c)
                                                <span class="inline-block bg-gray-100 text-gray-800 rounded px-2 py-0.5 mr-1 text-xs">{{ $labels[trim($c)] ?? $c }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-gray-400">–</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2">{{ optional($p->servicio)->nombre }}</td>
                                    <td class="px-3 py-2">{{ optional($p->cama)->codigo ?? '–' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-3 py-2 text-center text-gray-500">No se encontraron pacientes.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4" id="paginationContainer">
                        {{ $pacientes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const searchInput = document.getElementById('buscarInput');
const estadoSelect = document.getElementById('estadoSelect');
const servicioSelect = document.getElementById('servicioSelect');
const tbody = document.getElementById('pacientesReporteTbody');
const spinner = document.getElementById('searchSpinner');
const paginationContainer = document.getElementById('paginationContainer');
const searchUrl = "{{ route('pacientes.search') }}";

let debounceTimer = null;

function toggleSpinner(show) {
    if (!spinner) return;
    spinner.classList.toggle('hidden', !show);
}

function fetchPacientesLive() {
    const q = searchInput ? searchInput.value.trim() : '';
    const estado = estadoSelect ? estadoSelect.value : '';
    const servicio = servicioSelect ? servicioSelect.value : '';

    // Mostrar spinner mientras se teclea
    toggleSpinner(true);

    const params = new URLSearchParams();
    if (q) params.append('q', q);
    if (estado) params.append('estado', estado);
    if (servicio) params.append('servicio_id', servicio);

    fetch(`${searchUrl}?${params.toString()}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(res => res.json())
    .then(data => {
        renderRows(data.pacientes || []);
        toggleSpinner(false);
        if (paginationContainer) {
            // Oculta la paginación cuando hay filtros o búsqueda activa
            paginationContainer.style.display = (q || estado || servicio) ? 'none' : 'block';
        }
    })
    .catch(() => toggleSpinner(false));
}

function renderRows(pacientes) {
    if (!tbody) return;

    if (!pacientes.length) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-3 py-2 text-center text-gray-500">No se encontraron pacientes.</td>
            </tr>
        `;
        return;
    }

    const labels = {
        'normal': 'Normal',
        'diabetico': 'Diabético',
        'hiposodico': 'Hiposódico',
    };

    tbody.innerHTML = pacientes.map(p => {
        const conds = (p.condicion || '').split(',').filter(Boolean);
        const condBadges = conds.length
            ? conds.map(c => `<span class="inline-block bg-gray-100 text-gray-800 rounded px-2 py-0.5 mr-1 text-xs">${labels[c.trim()] || c}</span>`).join('')
            : '<span class="text-gray-400">–</span>';

        const estadoBadge = p.estado === 'hospitalizado'
            ? '<span class="inline-block bg-blue-100 text-blue-800 rounded px-2 py-1 text-xs font-semibold">Hospitalizado</span>'
            : '<span class="inline-block bg-green-100 text-green-800 rounded px-2 py-1 text-xs font-semibold">Alta</span>';

        return `
            <tr class="hover:bg-gray-50 transition">
                <td class="px-3 py-2">${p.nombre} ${p.apellido}</td>
                <td class="px-3 py-2">${p.cedula}</td>
                <td class="px-3 py-2">${estadoBadge}</td>
                <td class="px-3 py-2">${p.edad ?? '–'}</td>
                <td class="px-3 py-2">${condBadges}</td>
                <td class="px-3 py-2">${p.servicio || ''}</td>
                <td class="px-3 py-2">${p.cama || '–'}</td>
            </tr>
        `;
    }).join('');
}

function debounceFetch() {
    if (debounceTimer) clearTimeout(debounceTimer);
    debounceTimer = setTimeout(fetchPacientesLive, 250);
}

if (searchInput) searchInput.addEventListener('input', debounceFetch);
if (estadoSelect) estadoSelect.addEventListener('change', fetchPacientesLive);
if (servicioSelect) servicioSelect.addEventListener('change', fetchPacientesLive);

// Si ya hay texto inicial, disparar búsqueda al cargar
if ((searchInput && searchInput.value.trim()) || (estadoSelect && estadoSelect.value) || (servicioSelect && servicioSelect.value)) {
    fetchPacientesLive();
}
</script>
@endpush
