@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 sm:p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                    <div>
                        <h2 class="font-semibold text-2xl text-gray-800">Reporte de Pacientes</h2>
                        <p class="text-xs sm:text-sm text-gray-600">Busca por nombre, cédula, estado o servicio</p>
                    </div>
                    <div class="text-sm text-gray-500">Resultados: <span class="font-semibold text-gray-800">{{ $pacientes->total() }}</span></div>
                </div>

                <!-- Filtros -->
                <form method="GET" action="{{ route('pacientes.reporte') }}" class="mb-6 bg-gray-50 border border-gray-200 rounded-xl p-3 sm:p-4 space-y-3 sm:space-y-4" id="filtrosForm">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs sm:text-sm font-semibold text-gray-800">Buscar</label>
                            <div class="mt-1 relative">
                                <input
                                    type="text"
                                    id="buscarInput"
                                    name="q"
                                    value="{{ request('q') }}"
                                    placeholder="Nombre, apellido o cédula"
                                    class="w-full pl-10 pr-4 py-2 sm:py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                                <svg class="w-4 sm:w-5 h-4 sm:h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                                </svg>
                                <div id="searchSpinner" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                                    <svg class="animate-spin h-4 sm:h-5 w-4 sm:w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-gray-800">Estado</label>
                            <select name="estado" id="estadoSelect" class="mt-1 block w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Todos</option>
                                <option value="hospitalizado" @selected(request('estado') == 'hospitalizado')>Hospitalizado</option>
                                <option value="alta" @selected(request('estado') == 'alta')>Alta</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-semibold text-gray-800">Servicio</label>
                            <select name="servicio_id" id="servicioSelect" class="mt-1 block w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Todos</option>
                                @foreach($servicios as $servicio)
                                    <option value="{{ $servicio->id }}" @selected(request('servicio_id') == $servicio->id)>{{ $servicio->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 sm:gap-3">
                        <button type="submit" class="px-4 sm:px-5 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold shadow-md">Aplicar filtros</button>
                        <a href="{{ route('pacientes.reporte') }}" class="px-4 sm:px-5 py-2 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition">Limpiar</a>
                        @if(request()->hasAny(['q','estado','servicio_id']))
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs">
                                Filtros activos
                            </span>
                        @endif
                    </div>
                </form>

                <!-- Resumen de totales -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6">
                    <div class="bg-blue-50 p-3 sm:p-4 rounded-lg border border-blue-200">
                        <div class="text-xs sm:text-sm font-medium text-blue-600">Hospitalizados</div>
                        <div class="text-xl sm:text-2xl font-bold text-blue-900">{{ $totales['hospitalizado'] }}</div>
                    </div>
                    <div class="bg-green-50 p-3 sm:p-4 rounded-lg border border-green-200">
                        <div class="text-xs sm:text-sm font-medium text-green-600">Alta</div>
                        <div class="text-xl sm:text-2xl font-bold text-green-900">{{ $totales['alta'] }}</div>
                    </div>
                    <div class="bg-gray-50 p-3 sm:p-4 rounded-lg border border-gray-200 col-span-2 sm:col-span-1">
                        <div class="text-xs sm:text-sm font-medium text-gray-600">Total</div>
                        <div class="text-xl sm:text-2xl font-bold text-gray-900">{{ $totales['hospitalizado'] + $totales['alta'] }}</div>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="mb-6 bg-gradient-to-br from-purple-50 to-indigo-50 border border-purple-200 rounded-xl p-4 sm:p-5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                        <h3 class="text-lg font-bold text-gray-800">📊 Estadísticas</h3>
                        <a href="{{ route('pacientes.exportar') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Exportar a Excel
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                        <!-- Edad Promedio -->
                        <div class="bg-white rounded-lg p-3 border border-purple-200 shadow-sm">
                            <div class="text-xs font-medium text-gray-600">Edad Promedio</div>
                            <div class="text-2xl font-bold text-purple-600 mt-1">
                                @php
                                    $edadPromedio = \App\Models\Paciente::whereNotNull('edad')->avg('edad');
                                @endphp
                                {{ $edadPromedio ? number_format($edadPromedio, 1) : 'N/A' }}
                            </div>
                        </div>

                        <!-- Paciente más joven -->
                        <div class="bg-white rounded-lg p-3 border border-blue-200 shadow-sm">
                            <div class="text-xs font-medium text-gray-600">Edad Mínima</div>
                            <div class="text-2xl font-bold text-blue-600 mt-1">
                                @php
                                    $edadMin = \App\Models\Paciente::whereNotNull('edad')->min('edad');
                                @endphp
                                {{ $edadMin ?? 'N/A' }}
                            </div>
                        </div>

                        <!-- Paciente más viejo -->
                        <div class="bg-white rounded-lg p-3 border border-green-200 shadow-sm">
                            <div class="text-xs font-medium text-gray-600">Edad Máxima</div>
                            <div class="text-2xl font-bold text-green-600 mt-1">
                                @php
                                    $edadMax = \App\Models\Paciente::whereNotNull('edad')->max('edad');
                                @endphp
                                {{ $edadMax ?? 'N/A' }}
                            </div>
                        </div>

                        <!-- Pacientes con condiciones -->
                        <div class="bg-white rounded-lg p-3 border border-orange-200 shadow-sm">
                            <div class="text-xs font-medium text-gray-600">Con Condiciones</div>
                            <div class="text-2xl font-bold text-orange-600 mt-1">
                                @php
                                    $conCondiciones = \App\Models\Paciente::whereNotNull('condicion')->where('condicion', '!=', '')->count();
                                @endphp
                                {{ $conCondiciones }}
                            </div>
                        </div>
                    </div>

                    <!-- Gráfico de condiciones (pie) -->
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                            <h4 class="text-sm font-bold text-gray-800 mb-3">Distribución por Condición</h4>
                            <div class="space-y-2">
                                @php
                                    $condiciones = [];
                                    $allPacientes = \App\Models\Paciente::all();
                                    foreach ($allPacientes as $p) {
                                        if ($p->condicion) {
                                            $conds = explode(',', $p->condicion);
                                            foreach ($conds as $c) {
                                                $c = trim($c);
                                                $condiciones[$c] = ($condiciones[$c] ?? 0) + 1;
                                            }
                                        }
                                    }
                                    $labels = [
                                        'normal' => 'Normal',
                                        'diabetico' => 'Diabético',
                                        'hiposodico' => 'Hiposódico',
                                    ];
                                @endphp
                                @if(!empty($condiciones))
                                    @foreach($condiciones as $cond => $count)
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="font-medium">{{ $labels[$cond] ?? $cond }}</span>
                                            <div class="flex items-center gap-2">
                                                <div class="w-16 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                    <div class="h-full bg-purple-600" style="width: {{ ($count / count($allPacientes)) * 100 }}%"></div>
                                                </div>
                                                <span class="font-bold text-gray-800 w-6 text-right">{{ $count }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-gray-500 text-xs">Sin datos</p>
                                @endif
                            </div>
                        </div>

                        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                            <h4 class="text-sm font-bold text-gray-800 mb-3">Distribución por Servicio</h4>
                            <div class="space-y-2">
                                @php
                                    $pacientesConServicio = \App\Models\Paciente::whereNotNull('servicio_id')->get();
                                    $serviciosCount = \App\Models\Paciente::whereNotNull('servicio_id')
                                        ->groupBy('servicio_id')
                                        ->select(\Illuminate\Support\Facades\DB::raw('servicio_id, count(*) as total'))
                                        ->with('servicio')
                                        ->get();
                                @endphp
                                @if($serviciosCount->count() > 0)
                                    @foreach($serviciosCount as $sc)
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="font-medium truncate">{{ $sc->servicio->nombre ?? 'Sin servicio' }}</span>
                                            <div class="flex items-center gap-2">
                                                <div class="w-16 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                    <div class="h-full bg-blue-600" style="width: {{ ($sc->total / max($pacientesConServicio->count(), 1)) * 100 }}%"></div>
                                                </div>
                                                <span class="font-bold text-gray-800 w-6 text-right">{{ $sc->total }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-gray-500 text-xs">Sin datos</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vista Desktop (Tabla) -->
                <div class="w-full overflow-auto hidden md:block">
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

                <!-- Vista Mobile (Tarjetas) -->
                <div id="pacientesReporteMobile" class="md:hidden space-y-3">
                    @forelse($pacientes as $p)
                        <div class="bg-white border border-gray-200 rounded-lg p-3 shadow-sm hover:shadow-md transition">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-sm text-gray-900 truncate">{{ $p->nombre }} {{ $p->apellido }}</h3>
                                    <p class="text-xs text-gray-600">CI: <span class="font-mono font-semibold">{{ $p->cedula }}</span></p>
                                </div>
                                @if($p->estado === 'hospitalizado')
                                    <span class="inline-block bg-blue-100 text-blue-800 rounded px-2 py-1 text-xs font-semibold flex-shrink-0">Hospitalizado</span>
                                @else
                                    <span class="inline-block bg-green-100 text-green-800 rounded px-2 py-1 text-xs font-semibold flex-shrink-0">Alta</span>
                                @endif
                            </div>
                            
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div class="bg-gray-50 p-2 rounded">
                                    <p class="text-gray-600 font-medium">Edad</p>
                                    <p class="font-semibold text-gray-800">{{ $p->edad ?? '–' }}</p>
                                </div>
                                <div class="bg-gray-50 p-2 rounded">
                                    <p class="text-gray-600 font-medium">Servicio</p>
                                    <p class="font-semibold text-gray-800 truncate">{{ optional($p->servicio)->nombre ?? '–' }}</p>
                                </div>
                            </div>

                            @if($p->condicion)
                                <div class="mt-2">
                                    <p class="text-xs font-medium text-gray-600 mb-1">Condición:</p>
                                    <div class="flex flex-wrap gap-1">
                                        @php
                                            $cond = $p->condicion;
                                            $labels = [
                                                'normal' => 'Normal',
                                                'diabetico' => 'Diabético',
                                                'hiposodico' => 'Hiposódico',
                                            ];
                                            $condArr = $cond ? explode(',', $cond) : [];
                                        @endphp
                                        @foreach($condArr as $c)
                                            <span class="inline-block bg-gray-100 text-gray-800 rounded px-2 py-0.5 text-xs">{{ $labels[trim($c)] ?? $c }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($p->cama)
                                <div class="mt-2 p-2 bg-yellow-50 rounded border border-yellow-200">
                                    <p class="text-xs font-medium text-yellow-700">Cama: <span class="font-bold">{{ $p->cama->codigo }}</span></p>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <p>No se encontraron pacientes.</p>
                        </div>
                    @endforelse
                    <div class="mt-4" id="paginationContainerMobile">
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
const mobileContainer = document.getElementById('pacientesReporteMobile');
const spinner = document.getElementById('searchSpinner');
const paginationContainer = document.getElementById('paginationContainer');
const paginationMobileContainer = document.getElementById('paginationContainerMobile');
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
        renderMobileCards(data.pacientes || []);
        toggleSpinner(false);
        if (paginationContainer) {
            // Oculta la paginación cuando hay filtros o búsqueda activa
            paginationContainer.style.display = (q || estado || servicio) ? 'none' : 'block';
        }
        if (paginationMobileContainer) {
            paginationMobileContainer.style.display = (q || estado || servicio) ? 'none' : 'block';
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

function renderMobileCards(pacientes) {
    if (!mobileContainer) return;

    if (!pacientes.length) {
        mobileContainer.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <p>No se encontraron pacientes.</p>
            </div>
        `;
        return;
    }

    const labels = {
        'normal': 'Normal',
        'diabetico': 'Diabético',
        'hiposodico': 'Hiposódico',
    };

    mobileContainer.innerHTML = pacientes.map(p => {
        const conds = (p.condicion || '').split(',').filter(Boolean);
        const condBadges = conds.length
            ? conds.map(c => `<span class="inline-block bg-gray-100 text-gray-800 rounded px-2 py-0.5 text-xs">${labels[c.trim()] || c}</span>`).join('')
            : '';

        const estadoBadge = p.estado === 'hospitalizado'
            ? '<span class="inline-block bg-blue-100 text-blue-800 rounded px-2 py-1 text-xs font-semibold">Hospitalizado</span>'
            : '<span class="inline-block bg-green-100 text-green-800 rounded px-2 py-1 text-xs font-semibold">Alta</span>';

        const camaHTML = p.cama ? `<div class="mt-2 p-2 bg-yellow-50 rounded border border-yellow-200"><p class="text-xs font-medium text-yellow-700">Cama: <span class="font-bold">${p.cama}</span></p></div>` : '';

        const condHTML = condBadges ? `<div class="mt-2"><p class="text-xs font-medium text-gray-600 mb-1">Condición:</p><div class="flex flex-wrap gap-1">${condBadges}</div></div>` : '';

        return `
            <div class="bg-white border border-gray-200 rounded-lg p-3 shadow-sm hover:shadow-md transition">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-sm text-gray-900 truncate">${p.nombre} ${p.apellido}</h3>
                        <p class="text-xs text-gray-600">CI: <span class="font-mono font-semibold">${p.cedula}</span></p>
                    </div>
                    ${estadoBadge}
                </div>
                
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="bg-gray-50 p-2 rounded">
                        <p class="text-gray-600 font-medium">Edad</p>
                        <p class="font-semibold text-gray-800">${p.edad ?? '–'}</p>
                    </div>
                    <div class="bg-gray-50 p-2 rounded">
                        <p class="text-gray-600 font-medium">Servicio</p>
                        <p class="font-semibold text-gray-800 truncate">${p.servicio || '–'}</p>
                    </div>
                </div>

                ${condHTML}
                ${camaHTML}
            </div>
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
