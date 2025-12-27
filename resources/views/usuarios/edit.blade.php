<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Usuario') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Header con botón volver -->
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">✏️ Editar Usuario</h1>
                    <p class="text-gray-600 mt-1">Actualiza la información del usuario</p>
                </div>
                <a href="{{ route('usuarios.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>

            <!-- Información del usuario -->
            <div class="mb-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-6 border border-indigo-100">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-16 w-16 bg-indigo-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold text-2xl">{{ substr($usuario->name, 0, 1) }}</span>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $usuario->name }}</h3>
                        <p class="text-sm text-gray-600">ID: {{ $usuario->id }} • Email: {{ $usuario->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Nombre -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Nombre Completo
                                </span>
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $usuario->name) }}" 
                                required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                placeholder="Ingrese el nombre completo"
                            />
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Correo Electrónico
                                </span>
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', $usuario->email) }}" 
                                required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                placeholder="correo@ejemplo.com"
                            />
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Rol -->
                        <div class="mb-6">
                            <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    Rol del Usuario
                                </span>
                            </label>
                            <select 
                                id="role" 
                                name="role" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                            >
                                <option value="usuario" {{ old('role', $usuario->role)=='usuario' ? 'selected' : '' }}>👤 Usuario</option>
                                <option value="admin" {{ old('role', $usuario->role)=='admin' ? 'selected' : '' }}>🛡️ Administrador</option>
                                <option value="nutricionista" {{ old('role', $usuario->role)=='nutricionista' ? 'selected' : '' }}>🥗 Nutricionista</option>
                                <option value="enfermero" {{ old('role', $usuario->role)=='enfermero' ? 'selected' : '' }}>💊 Enfermero</option>
                            </select>
                            @error('role')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">Selecciona el nivel de acceso para este usuario</p>
                        </div>

                        <!-- Contraseña (opcional) -->
                        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                    </svg>
                                    Nueva Contraseña (Opcional)
                                </span>
                            </label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="w-full px-4 py-3 border border-yellow-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all duration-200"
                                placeholder="Dejar en blanco para mantener la contraseña actual"
                            />
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-yellow-700">⚠️ Solo completa este campo si deseas cambiar la contraseña</p>
                        </div>

                        <!-- Botones de acción -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <a href="{{ route('usuarios.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-lg shadow-md transition-all duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Actualizar Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
