@extends('layouts.app')

@section('content')
<div class="py-6 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-4 md:mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h1 class="text-xl md:text-3xl font-bold text-gray-900">🛏️ Camas</h1>
                <p class="mt-1 md:mt-2 text-xs md:text-base text-gray-600">Gestiona las camas del sistema</p>
            </div>
            <a href="{{ route('camas.create') }}" class="inline-flex items-center justify-center px-3 md:px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition duration-200 ease-in-out hover:shadow-md text-sm md:text-base">
                <svg class="w-4 h-4 md:w-5 md:h-5 mr-1 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nueva Cama
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-4 md:mb-6 p-3 md:p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-start">
                <svg class="w-4 h-4 md:w-5 md:h-5 mr-2 md:mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="font-medium text-sm md:text-base">Éxito</p>
                    <p class="text-xs md:text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Main Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            @if($camas->count() > 0)
                <!-- Search Input -->
                <div class="px-3 md:px-6 py-3 md:py-4 bg-white border-b border-gray-200">
                    <div class="relative">
                        <svg class="absolute left-2 md:left-3 top-2 md:top-3 h-4 w-4 md:h-5 md:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" id="searchInput" placeholder="Buscar por código o servicio..." class="w-full pl-8 md:pl-10 pr-3 md:pr-4 py-1.5 md:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition text-sm md:text-base" />
                    </div>
                </div>

                <!-- Vista Desktop (Tabla) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="camasTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Código</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Servicio</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 hover:bg-gray-50 transition" id="camasTbody">
                            @foreach($camas as $cama)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $codigoNumero = \Illuminate\Support\Str::afterLast($cama->codigo, '-');
                                        @endphp
                                        <span class="font-medium text-gray-900">{{ $codigoNumero !== '' ? $codigoNumero : $cama->codigo }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            {{ optional($cama->servicio)->nombre ?? 'Sin servicio' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                        <a href="{{ route('camas.edit', $cama) }}" class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-md transition duration-200 font-medium">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Editar
                                        </a>
                                        <form action="{{ route('camas.destroy', $cama) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cama?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="color: white !important;" class="inline-flex items-center px-3 py-1 bg-red-600 text-white hover:bg-red-700 rounded-md transition duration-200 font-medium">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Vista Móvil (Tarjetas) -->
                <div class="md:hidden space-y-3 p-3" id="camasCardsMobile">
                    @foreach($camas as $cama)
                        <div class="bg-white rounded-lg shadow-md p-3 border-l-4 border-blue-500">
                            <div class="mb-3">
                                @php
                                    $codigoNumero = \Illuminate\Support\Str::afterLast($cama->codigo, '-');
                                @endphp
                                <h3 class="text-base font-bold text-gray-900">🛏️ Cama {{ $codigoNumero !== '' ? $codigoNumero : $cama->codigo }}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-2">
                                    {{ optional($cama->servicio)->nombre ?? 'Sin servicio' }}
                                </span>
                            </div>

                            <div class="flex justify-center gap-2">
                                <a href="{{ route('camas.edit', $cama) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-md transition duration-200 font-medium text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Editar
                                </a>
                                <form action="{{ route('camas.destroy', $cama) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cama?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="color: white !important;" class="w-full inline-flex items-center justify-center px-3 py-2 bg-red-600 text-white hover:bg-red-700 rounded-md transition duration-200 font-medium text-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($camas->hasPages())
                    <div class="bg-gray-50 px-3 md:px-6 py-3 md:py-4 border-t border-gray-200">
                        {{ $camas->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-8 md:py-12">
                    <svg class="mx-auto h-10 w-10 md:h-12 md:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-3 md:mt-4 text-base md:text-lg font-medium text-gray-900">No hay camas registradas</h3>
                    <p class="mt-1 text-xs md:text-base text-gray-500">Comienza creando una nueva cama.</p>
                    <div class="mt-4 md:mt-6">
                        <a href="{{ route('camas.create') }}" class="inline-flex items-center px-3 md:px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition duration-200 text-sm md:text-base">
                            <svg class="w-4 h-4 md:w-5 md:h-5 mr-1 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Nueva Cama
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('camasTable');
        const tbody = document.getElementById('camasTbody');
        const cardsMobile = document.getElementById('camasCardsMobile');

        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase().trim();
                let visibleCount = 0;

                // Filtrar tabla desktop
                if (tbody) {
                    const rows = tbody.querySelectorAll('tr');
                    rows.forEach(row => {
                        const codigo = row.cells[0]?.textContent.toLowerCase() || '';
                        const servicio = row.cells[1]?.textContent.toLowerCase() || '';
                        const matches = codigo.includes(searchTerm) || servicio.includes(searchTerm);

                        if (matches || searchTerm === '') {
                            row.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }

                // Filtrar tarjetas móvil
                if (cardsMobile) {
                    const cards = cardsMobile.querySelectorAll('div.bg-white');
                    cards.forEach(card => {
                        const text = card.textContent.toLowerCase();
                        const matches = text.includes(searchTerm);

                        if (matches || searchTerm === '') {
                            card.style.display = '';
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    });
                }

                // Mostrar mensaje si no hay resultados en tabla
                if (tbody) {
                    let noResultsRow = tbody.querySelector('.no-results-row');
                    if (visibleCount === 0 && searchTerm !== '') {
                        if (!noResultsRow) {
                            noResultsRow = document.createElement('tr');
                            noResultsRow.className = 'no-results-row';
                            noResultsRow.innerHTML = `
                                <td colspan="3" class="px-6 py-8 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-gray-500 font-medium">No se encontraron resultados</p>
                                    <p class="text-gray-400 text-sm">Intenta con otros términos de búsqueda</p>
                                </td>
                            `;
                            tbody.appendChild(noResultsRow);
                        }
                    } else if (noResultsRow) {
                        noResultsRow.remove();
                    }
                }

                // Mostrar mensaje si no hay resultados en móvil
                if (cardsMobile) {
                    let noResultsCard = cardsMobile.querySelector('.no-results-card');
                    if (visibleCount === 0 && searchTerm !== '') {
                        if (!noResultsCard) {
                            noResultsCard = document.createElement('div');
                            noResultsCard.className = 'no-results-card text-center py-8';
                            noResultsCard.innerHTML = `
                                <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-gray-500 font-medium text-sm">No se encontraron resultados</p>
                                <p class="text-gray-400 text-xs">Intenta con otros términos</p>
                            `;
                            cardsMobile.appendChild(noResultsCard);
                        }
                    } else if (noResultsCard) {
                        noResultsCard.remove();
                    }
                }
            });
        }
    });
</script>
@endsection
