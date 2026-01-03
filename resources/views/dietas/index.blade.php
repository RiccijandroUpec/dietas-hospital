@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header con título y botón -->
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">🍽️ Dietas</h1>
                <p class="text-gray-600 mt-1">Gestiona los tipos de dietas disponibles en el sistema</p>
            </div>
            @if(auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('dietas.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg shadow-md transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="hidden md:inline">Nueva Dieta</span>
                    <span class="md:hidden text-lg">➕</span>
                </a>
            @endif
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total de Dietas</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $dietas->total() }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Página Actual</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $dietas->count() }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Última Actualización</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ now()->format('d/m/Y') }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensajes de éxito/error -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Tabla de dietas -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Live search input -->
            <div class="px-6 pt-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar dietas</label>
                <div class="relative">
                    <input type="text" id="dietasSearchInput" class="w-full border-gray-300 rounded-md text-sm px-3 py-2 pr-10" placeholder="Escribe para buscar por nombre o descripción...">
                    <div id="dietasSearchStatus" class="absolute right-3 top-2.5 text-xs text-gray-500 hidden flex items-center">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-1">Búsqueda en vivo (muestra hasta 50 coincidencias)</p>
            </div>
            <div class="overflow-x-auto">
                <!-- Vista de escritorio (tabla) -->
                <table class="hidden md:table min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-green-50 to-green-100">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Nombre
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Descripción
                            </th>
                            @if(auth()->check() && auth()->user()->role === 'admin')
                                <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Acciones
                                </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="dietasTbody" class="bg-white divide-y divide-gray-200">
                        @if($dietas->count() > 0)
                            @foreach($dietas as $dieta)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                                <span class="text-green-600 font-bold text-lg">{{ substr($dieta->nombre, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900">{{ $dieta->nombre }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-700 max-w-md">
                                            {{ Str::limit($dieta->descripcion, 150) }}
                                        </div>
                                    </td>
                                    @if(auth()->check() && auth()->user()->role === 'admin')
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('dietas.edit', $dieta) }}" class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-md transition duration-200 font-medium">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Editar
                                                </a>
                                                <form action="{{ route('dietas.destroy', $dieta) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta dieta?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="color: white !important;" class="inline-flex items-center px-3 py-1 bg-red-600 text-white hover:bg-red-700 rounded-md transition duration-200 font-medium">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="3" class="px-6 py-10 text-center text-gray-600">No hay dietas registradas</td></tr>
                        @endif
                    </tbody>
                </table>

                <!-- Vista móvil (tarjetas) -->
                <div id="dietasCardsMobile" class="md:hidden space-y-4 p-4">
                    @if($dietas->count() > 0)
                        @foreach($dietas as $dieta)
                            <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12 bg-green-100 rounded-full flex items-center justify-center">
                                            <span class="text-green-600 font-bold text-xl">{{ substr($dieta->nombre, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-base font-bold text-gray-900">{{ $dieta->nombre }}</h3>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <p class="text-sm text-gray-700">{{ Str::limit($dieta->descripcion, 150) }}</p>
                                </div>

                                @if(auth()->check() && auth()->user()->role === 'admin')
                                    <div class="flex justify-center gap-2 pt-3 border-t border-gray-200">
                                        <a href="{{ route('dietas.edit', $dieta) }}" 
                                           class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-150 text-lg" 
                                           title="Editar">
                                            ✏️
                                        </a>
                                        <form action="{{ route('dietas.destroy', $dieta) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta dieta?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-150 text-lg" 
                                                    title="Eliminar">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-10 text-gray-600">No hay dietas registradas</div>
                    @endif
                </div>
            </div>

            <!-- Paginación -->
            <div id="dietasPagination" class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                @if($dietas->count() > 0)
                    {{ $dietas->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('dietasSearchInput');
    const status = document.getElementById('dietasSearchStatus');
    const tbody = document.getElementById('dietasTbody');
    const cardsMobile = document.getElementById('dietasCardsMobile');
    const pagination = document.getElementById('dietasPagination');

    if (!input || !tbody) {
        console.error('Search elements not found');
        return;
    }

    const renderRows = (items) => {
        if (!items.length) {
            tbody.innerHTML = `<tr><td colspan="3" class="px-6 py-10 text-center text-gray-600">Sin resultados</td></tr>`;
            if (cardsMobile) {
                cardsMobile.innerHTML = `<div class="text-center py-10 text-gray-600">Sin resultados</div>`;
            }
            return;
        }
        
        // Vista de escritorio (tabla)
        tbody.innerHTML = items.map(d => `
            <tr class="hover:bg-gray-50 transition-colors duration-150">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="text-green-600 font-bold text-lg">${(d.nombre || '').charAt(0)}</span>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-semibold text-gray-900">${d.nombre || ''}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-700 max-w-md">${(d.descripcion || '').substring(0, 150)}</div>
                </td>
                ${d.edit_url ? `
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-2">
                        <a href="${d.edit_url}" class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors duration-150">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar
                        </a>
                        <button onclick="if(confirm('¿Estás seguro?')) { const form = document.createElement('form'); form.method = 'POST'; form.action = '${d.destroy_url}'; form.innerHTML = '<input type=\\'hidden\\' name=\\'_token\\' value=\\'{{ csrf_token() }}\\'><input type=\\'hidden\\' name=\\'_method\\' value=\\'DELETE\\'>'; document.body.appendChild(form); form.submit(); }" class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors duration-150">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Eliminar
                        </button>
                    </div>
                </td>
                ` : '<td></td>'}
            </tr>
        `).join('');
        
        // Vista móvil (tarjetas)
        if (cardsMobile) {
            cardsMobile.innerHTML = items.map(d => `
                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12 bg-green-100 rounded-full flex items-center justify-center">
                                <span class="text-green-600 font-bold text-xl">${(d.nombre || '').charAt(0)}</span>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-base font-bold text-gray-900">${d.nombre || ''}</h3>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-sm text-gray-700">${(d.descripcion || '').substring(0, 150)}</p>
                    </div>

                    ${d.edit_url ? `
                    <div class="flex justify-center gap-2 pt-3 border-t border-gray-200">
                        <a href="${d.edit_url}" 
                           class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-150 text-lg" 
                           title="Editar">
                            ✏️
                        </a>
                        <button onclick="if(confirm('¿Estás seguro?')) { const form = document.createElement('form'); form.method = 'POST'; form.action = '${d.destroy_url}'; form.innerHTML = '<input type=\\'hidden\\' name=\\'_token\\' value=\\'{{ csrf_token() }}\\'><input type=\\'hidden\\' name=\\'_method\\' value=\\'DELETE\\'>'; document.body.appendChild(form); form.submit(); }" 
                                class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-150 text-lg" 
                                title="Eliminar">
                            🗑️
                        </button>
                    </div>
                    ` : ''}
                </div>
            `).join('');
        }
    };

    const fetchDietas = async (q) => {
        const url = `{{ route('dietas.search') }}?q=${encodeURIComponent(q)}`;
        status.classList.remove('hidden');
        try {
            const res = await fetch(url, { 
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            });
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            const data = await res.json();
            renderRows(data);
        } catch (e) {
            console.error('Fetch error:', e);
            tbody.innerHTML = `<tr><td colspan="3" class="px-6 py-10 text-center text-red-600">Error al buscar: ${e.message}</td></tr>`;
            if (cardsMobile) {
                cardsMobile.innerHTML = `<div class="text-center py-10 text-red-600">Error al buscar: ${e.message}</div>`;
            }
        } finally {
            status.classList.add('hidden');
        }
    };

    input.addEventListener('input', () => {
        const q = input.value.trim();
        if (!q) {
            pagination.style.display = '';
            status.classList.add('hidden');
            return;
        }
        pagination.style.display = 'none';
        fetchDietas(q);
    });
});
</script>
@endpush
