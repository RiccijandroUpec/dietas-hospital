@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="font-semibold text-2xl text-gray-800">👥 Pacientes</h2>
                        <p class="text-gray-600 text-sm mt-1">Gestión de pacientes hospitalizados</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('pacientes.reporte') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition">📊 Reporte</a>
                        @if(auth()->check() && auth()->user()->role !== 'usuario')
                            <a href="{{ route('pacientes.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition">➕ Nuevo</a>
                        @endif
                        @if(auth()->check() && auth()->user()->role !== 'usuario')
                            <a href="{{ route('registro-dietas.create') }}" class="inline-flex items-center px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-md transition">🥗 Dieta</a>
                        @endif
                    </div>
                </div>

                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-md flex items-start">
                        <span class="mr-3">✓</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md flex items-start">
                        <span class="mr-3">⚠️</span>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 p-3 rounded-lg border border-blue-200">
                        <div class="text-xs font-medium text-blue-600">Total de Pacientes</div>
                        <div class="text-2xl font-bold text-blue-900">{{ $pacientes->total() }}</div>
                    </div>
                    <div class="bg-orange-50 p-3 rounded-lg border border-orange-200">
                        <div class="text-xs font-medium text-orange-600">En esta página</div>
                        <div class="text-2xl font-bold text-orange-900">{{ $pacientes->count() }}</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <div class="text-xs font-medium text-gray-600">Última actualización</div>
                        <div class="text-lg font-bold text-gray-900">{{ now()->format('d/m/Y H:i') }}</div>
                    </div>
                </div>

                <!-- Búsqueda en vivo -->
                <div class="mb-4">
                    <div class="relative">
                        <input 
                            type="text" 
                            id="pacientesSearchInput" 
                            placeholder="🔍 Buscar pacientes por nombre, apellido o cédula..." 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                        >
                        <div id="searchSpinner" class="hidden absolute right-3 top-3">
                            <svg class="animate-spin h-6 w-6 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="w-full overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Nombre</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Cédula</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Estado</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Edad</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Condición</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Servicio</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Cama</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-700">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="pacientesTbody" class="bg-white divide-y divide-gray-200">
                        @if($pacientes->count() > 0)
                            @foreach($pacientes as $paciente)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 font-medium text-gray-900">
                                    {{ $paciente->nombre }} {{ $paciente->apellido }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 font-mono text-sm">{{ $paciente->cedula }}</td>
                                <td class="px-4 py-3">
                                    @if($paciente->estado === 'hospitalizado')
                                        <span class="inline-block bg-blue-100 text-blue-800 rounded-full px-3 py-1 text-xs font-semibold">Hospitalizado</span>
                                    @else
                                        <span class="inline-block bg-green-100 text-green-800 rounded-full px-3 py-1 text-xs font-semibold">Alta</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $paciente->edad ?? '–' }} años</td>
                                <td class="px-4 py-3">
                                    @php
                                        $cond = $paciente->condicion;
                                        $labels = [
                                            'normal' => 'Normal',
                                            'diabetico' => 'Diabético',
                                            'hiposodico' => 'Hiposódico',
                                        ];
                                        $condArr = $cond ? explode(',', $cond) : [];
                                    @endphp
                                    @if($condArr && $condArr[0] !== '')
                                        <div class="flex flex-wrap gap-1">
                                        @foreach($condArr as $c)
                                            <span class="inline-block bg-yellow-100 text-yellow-800 rounded-full px-2 py-0.5 text-xs font-medium">{{ $labels[trim($c)] ?? $c }}</span>
                                        @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400">–</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ optional($paciente->servicio)->nombre ?? '–' }}</td>
                                <td class="px-4 py-3 text-gray-600 font-mono">{{ optional($paciente->cama)->codigo ?? '–' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('pacientes.show', $paciente) }}" class="px-3 py-1 bg-indigo-100 text-indigo-700 hover:bg-indigo-200 rounded text-xs font-medium transition">👁️ Ver</a>
                                        @if(auth()->check() && auth()->user()->role !== 'usuario')
                                            <a href="{{ route('pacientes.edit', $paciente) }}" class="px-3 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded text-xs font-medium transition">✏️ Editar</a>
                                        @endif
                                        @if(auth()->check() && in_array(auth()->user()->role, ['administrador', 'admin']))
                                            <form action="{{ route('pacientes.destroy', $paciente) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar este paciente?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 hover:bg-red-200 rounded text-xs font-medium transition">🗑️ Eliminar</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="text-center py-12">
                                    <div class="text-6xl mb-4">👥</div>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-2">No hay pacientes</h3>
                                    <p class="text-gray-600 mb-4">Comienza creando el primer paciente.</p>
                                    @if(auth()->check() && auth()->user()->role !== 'usuario')
                                        <a href="{{ route('pacientes.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                            ➕ Crear Paciente
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>

                    <div id="paginationContainer" class="mt-6">
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
const searchInput = document.getElementById('pacientesSearchInput');
const tbody = document.getElementById('pacientesTbody');
const spinner = document.getElementById('searchSpinner');
const paginationContainer = document.getElementById('paginationContainer');
const searchUrl = "{{ route('pacientes.search') }}";
const isUsuario = {{ auth()->user()->role === 'usuario' ? 'true' : 'false' }};
const isAdmin = {{ in_array(auth()->user()->role, ['administrador', 'admin']) ? 'true' : 'false' }};

if (searchInput && tbody) {
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        fetchPacientes(query);
    });
}

function fetchPacientes(query) {
    if (spinner) spinner.classList.remove('hidden');

    const url = `${searchUrl}?q=${encodeURIComponent(query)}`;
    
    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        renderRows(data.pacientes);
        if (spinner) spinner.classList.add('hidden');
        // Ocultar paginación durante búsqueda
        if (paginationContainer) {
            paginationContainer.style.display = query ? 'none' : 'block';
        }
    })
    .catch(error => {
        console.error('Error fetching pacientes:', error);
        if (spinner) spinner.classList.add('hidden');
    });
}

function renderRows(pacientes) {
    if (!tbody) return;

    if (pacientes.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-8 text-gray-500">
                    <div class="text-4xl mb-2">🔍</div>
                    No se encontraron pacientes
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = pacientes.map(p => {
        const condiciones = p.condicion ? p.condicion.split(',') : [];
        const labels = {
            'normal': 'Normal',
            'diabetico': 'Diabético',
            'hiposodico': 'Hiposódico'
        };
        
        const condBadges = condiciones.length && condiciones[0] !== '' 
            ? condiciones.map(c => {
                const label = labels[c.trim()] || c;
                return `<span class="inline-block bg-yellow-100 text-yellow-800 rounded-full px-2 py-0.5 text-xs font-medium">${label}</span>`;
              }).join('')
            : '<span class="text-gray-400">–</span>';

        const estadoBadge = p.estado === 'hospitalizado'
            ? '<span class="inline-block bg-blue-100 text-blue-800 rounded-full px-3 py-1 text-xs font-semibold">Hospitalizado</span>'
            : '<span class="inline-block bg-green-100 text-green-800 rounded-full px-3 py-1 text-xs font-semibold">Alta</span>';

        const showUrl = `/pacientes/${p.id}`;
        
        const editBtn = (!isUsuario && p.edit_url) ? `<a href="${p.edit_url}" class="px-3 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded text-xs font-medium transition">✏️ Editar</a>` : '';
        const deleteBtn = (isAdmin && p.delete_url) ? `
            <form action="${p.delete_url}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar este paciente?')">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 hover:bg-red-200 rounded text-xs font-medium transition">🗑️ Eliminar</button>
            </form>
        ` : '';

        const acciones = `
            <div class="flex justify-center gap-2">
                <a href="${showUrl}" class="px-3 py-1 bg-indigo-100 text-indigo-700 hover:bg-indigo-200 rounded text-xs font-medium transition">👁️ Ver</a>
                ${editBtn}
                ${deleteBtn}
            </div>
        `;

        return `
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3 font-medium text-gray-900">${p.nombre} ${p.apellido}</td>
                <td class="px-4 py-3 text-gray-600 font-mono text-sm">${p.cedula}</td>
                <td class="px-4 py-3">${estadoBadge}</td>
                <td class="px-4 py-3 text-gray-600">${p.edad || '–'} años</td>
                <td class="px-4 py-3"><div class="flex flex-wrap gap-1">${condBadges}</div></td>
                <td class="px-4 py-3 text-gray-600">${p.servicio || '–'}</td>
                <td class="px-4 py-3 text-gray-600 font-mono">${p.cama || '–'}</td>
                <td class="px-4 py-3 text-center">${acciones}</td>
            </tr>
        `;
    }).join('');
}
</script>
@endpush
