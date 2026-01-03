

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header con título y botón -->
            <div class="mb-4 md:mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <h1 class="text-xl md:text-3xl font-bold text-gray-900">👥 <span class="hidden sm:inline">Usuarios del Sistema</span><span class="sm:hidden">Usuarios</span></h1>
                    <p class="text-gray-600 text-xs md:text-base mt-1">Administra usuarios y roles</p>
                </div>
                <a href="{{ route('usuarios.create') }}" class="inline-flex items-center justify-center px-3 md:px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-lg shadow-md transition-all duration-200 transform hover:scale-105 text-sm md:text-base" title="Nuevo Usuario">
                    <span class="md:hidden text-lg">➕</span>
                    <span class="hidden md:flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Nuevo Usuario
                    </span>
                </a>
            </div>

            <!-- Tarjetas de estadísticas -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-4 mb-4 md:mb-6">
                <div class="bg-white rounded-lg shadow-md p-3 md:p-6 border-l-4 border-indigo-500">
                    <div class="flex items-center justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-xs md:text-sm font-medium text-gray-600">Total</p>
                            <p class="text-xl md:text-3xl font-bold text-gray-900 mt-0.5 md:mt-1">{{ $usuarios->count() }}</p>
                        </div>
                        <div class="p-2 md:p-3 bg-indigo-100 rounded-full flex-shrink-0">
                            <svg class="w-5 h-5 md:w-8 md:h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-3 md:p-6 border-l-4 border-red-500">
                    <div class="flex items-center justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-xs md:text-sm font-medium text-gray-600">Admin</p>
                            <p class="text-xl md:text-3xl font-bold text-gray-900 mt-0.5 md:mt-1">{{ $usuarios->where('role', 'admin')->count() }}</p>
                        </div>
                        <div class="p-2 md:p-3 bg-red-100 rounded-full flex-shrink-0">
                            <svg class="w-5 h-5 md:w-8 md:h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-3 md:p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-xs md:text-sm font-medium text-gray-600">Nutricionistas</p>
                            <p class="text-xl md:text-3xl font-bold text-gray-900 mt-0.5 md:mt-1">{{ $usuarios->where('role', 'nutricionista')->count() }}</p>
                        </div>
                        <div class="p-2 md:p-3 bg-green-100 rounded-full flex-shrink-0">
                            <svg class="w-5 h-5 md:w-8 md:h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-3 md:p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-xs md:text-sm font-medium text-gray-600">Enfermeros</p>
                            <p class="text-xl md:text-3xl font-bold text-gray-900 mt-0.5 md:mt-1">{{ $usuarios->where('role', 'enfermero')->count() }}</p>
                        </div>
                        <div class="p-2 md:p-3 bg-blue-100 rounded-full flex-shrink-0">
                            <svg class="w-5 h-5 md:w-8 md:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Búsqueda en vivo -->
            <div class="mb-4 md:mb-6">
                <div class="relative">
                    <input 
                        type="text" 
                        id="usuariosSearchInput" 
                        placeholder="🔍 Buscar usuarios por nombre o email..." 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                    >
                    <div id="searchSpinner" class="hidden absolute right-3 top-3">
                        <svg class="animate-spin h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tabla de usuarios -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                @if($usuarios->count() > 0)
                    <!-- Vista Desktop (Tabla) -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="usuariosTable">
                            <thead class="bg-gradient-to-r from-indigo-50 to-indigo-100">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Usuario
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Rol
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="usuariosTbody">
                                @foreach($usuarios as $usuario)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                                    <span class="text-indigo-600 font-bold text-lg">{{ substr($usuario->name, 0, 1) }}</span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-gray-900">{{ $usuario->name }}</div>
                                                    <div class="text-xs text-gray-500">ID: {{ $usuario->id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center text-sm text-gray-700">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $usuario->email }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($usuario->role === 'admin')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    🛡️ Admin
                                                </span>
                                            @elseif($usuario->role === 'nutricionista')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    🥗 Nutricionista
                                                </span>
                                            @elseif($usuario->role === 'enfermero')
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    💊 Enfermero
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    👤 Usuario
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('usuarios.edit', $usuario->id) }}" class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-md transition duration-200 font-medium">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Editar
                                                </a>
                                                <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?')">
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Vista Móvil (Tarjetas) -->
                    <div class="md:hidden space-y-3 p-3" id="usuariosMobile">
                        @foreach($usuarios as $usuario)
                            <div class="bg-white rounded-lg shadow-md p-3 border-l-4 border-indigo-500">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-start gap-2 flex-1 min-w-0">
                                        <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <span class="text-indigo-600 font-bold">{{ substr($usuario->name, 0, 1) }}</span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h3 class="text-base font-bold text-gray-900 break-words">{{ $usuario->name }}</h3>
                                            <p class="text-xs text-gray-500 mt-0.5">ID: {{ $usuario->id }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 pb-3 border-b border-gray-100">
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">📧 Email</p>
                                    <p class="text-xs text-gray-700 break-words">{{ $usuario->email }}</p>
                                </div>

                                <div class="mb-3">
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">👤 Rol</p>
                                    @if($usuario->role === 'admin')
                                        <span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            🛡️ Admin
                                        </span>
                                    @elseif($usuario->role === 'nutricionista')
                                        <span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            🥗 Nutricionista
                                        </span>
                                    @elseif($usuario->role === 'enfermero')
                                        <span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            💊 Enfermero
                                        </span>
                                    @else
                                        <span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            👤 Usuario
                                        </span>
                                    @endif
                                </div>

                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('usuarios.edit', $usuario->id) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-md transition duration-200 font-medium text-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Editar
                                    </a>
                                    <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?')">
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
                @else
                    <div class="text-center py-8 md:py-12">
                        <svg class="mx-auto h-10 w-10 md:h-12 md:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <h3 class="mt-3 md:mt-4 text-base md:text-lg font-semibold text-gray-900">No hay usuarios registrados</h3>
                        <p class="mt-1 text-xs md:text-base text-gray-500">Comienza creando un nuevo usuario.</p>
                        <div class="mt-4 md:mt-6">
                            <a href="{{ route('usuarios.create') }}" class="inline-flex items-center px-4 md:px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-md transition-colors duration-200 text-sm md:text-base">
                                <svg class="w-4 h-4 md:w-5 md:h-5 mr-1 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Nuevo Usuario
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

<script>
// Debug: script loaded
console.log('Usuarios index script loaded');
setTimeout(function() {
    const searchInput = document.getElementById('usuariosSearchInput');
    const usuariosTbody = document.getElementById('usuariosTbody');
    const usuariosMobile = document.getElementById('usuariosMobile');
    const spinner = document.getElementById('searchSpinner');
    const searchUrl = "{{ route('usuarios.search') }}";

    console.log('Search URL:', searchUrl);
    console.log('Search Input:', searchInput);
    console.log('Usuarios Tbody:', usuariosTbody);
    console.log('Usuarios Mobile:', usuariosMobile);

    if (searchInput) {
        console.log('Adding event listener to search input');
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            console.log('Search query:', query);
            fetchUsuarios(query);
        });
    } else {
        console.error('Search input element not found!');
    }

    function fetchUsuarios(query) {
        if (spinner) spinner.classList.remove('hidden');

        const url = `${searchUrl}?q=${encodeURIComponent(query)}`;
        console.log('Fetching from URL:', url);
        
        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);
            renderRows(data.usuarios);
            if (spinner) spinner.classList.add('hidden');
        })
        .catch(error => {
            console.error('Error fetching usuarios:', error);
            if (spinner) spinner.classList.add('hidden');
        });

    }

    function renderRows(usuarios) {
        if (!usuariosTbody && !usuariosMobile) return;

        if (usuarios.length === 0) {
            if (usuariosTbody) {
                usuariosTbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center py-8 text-gray-500">
                            <div class="text-4xl mb-2">🔍</div>
                            No se encontraron usuarios
                        </td>
                    </tr>
                `;
            }
            if (usuariosMobile) {
                usuariosMobile.innerHTML = `
                    <div class="bg-white rounded-lg shadow-md p-8 text-center">
                        <div class="text-6xl mb-4">🔍</div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">No se encontraron usuarios</h3>
                        <p class="text-gray-600">Intenta con otros criterios de búsqueda.</p>
                    </div>
                `;
            }
            return;
        }

        // Renderizar tabla de escritorio
        if (usuariosTbody) {
            usuariosTbody.innerHTML = usuarios.map(u => {
                const roleBadges = {
                    'admin': '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">🛡️ Admin</span>',
                    'nutricionista': '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">🥗 Nutricionista</span>',
                    'enfermero': '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">💊 Enfermero</span>',
                    'usuario': '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">👤 Usuario</span>'
                };

                const roleBadge = roleBadges[u.role] || roleBadges['usuario'];

                return `
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <span class="text-indigo-600 font-bold text-lg">${u.name.charAt(0)}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">${u.name}</div>
                                    <div class="text-xs text-gray-500">ID: ${u.id}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center text-sm text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                ${u.email}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ${roleBadge}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="${u.edit_url}" class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-md transition duration-200 font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Editar
                                </a>
                                <form action="${u.delete_url}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?')">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" style="color: white !important;" class="inline-flex items-center px-3 py-1 bg-red-600 text-white hover:bg-red-700 rounded-md transition duration-200 font-medium">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Renderizar tarjetas móviles
        if (usuariosMobile) {
            usuariosMobile.innerHTML = usuarios.map(u => {
                const roleSpans = {
                    'admin': '<span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800">🛡️ Admin</span>',
                    'nutricionista': '<span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">🥗 Nutricionista</span>',
                    'enfermero': '<span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">💊 Enfermero</span>',
                    'usuario': '<span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">👤 Usuario</span>'
                };

                const roleSpan = roleSpans[u.role] || roleSpans['usuario'];

                return `
                    <div class="bg-white rounded-lg shadow-md p-3 border-l-4 border-indigo-500">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-start gap-2 flex-1 min-w-0">
                                <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <span class="text-indigo-600 font-bold">${u.name.charAt(0)}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-base font-bold text-gray-900 break-words">${u.name}</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">ID: ${u.id}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-b border-gray-100">
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">📧 Email</p>
                            <p class="text-xs text-gray-700 break-words">${u.email}</p>
                        </div>

                        <div class="mb-3">
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">👤 Rol</p>
                            ${roleSpan}
                        </div>

                        <div class="flex justify-center gap-2">
                            <a href="${u.edit_url}" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-md transition duration-200 font-medium text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Editar
                            </a>
                            <form action="${u.delete_url}" method="POST" class="flex-1" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?')">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" style="color: white !important;" class="w-full inline-flex items-center justify-center px-3 py-2 bg-red-600 text-white hover:bg-red-700 rounded-md transition duration-200 font-medium text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                `;
            }).join('');
        }
    }
}, 100);
</script>

