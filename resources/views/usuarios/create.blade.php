<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('Crear Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('usuarios.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full" />
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full" />
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Rol</label>
                            <select name="role" class="mt-1 block w-full">
                                <option value="usuario" {{ old('role')=='usuario' ? 'selected' : '' }}>Usuario</option>
                                <option value="admin" {{ old('role')=='admin' ? 'selected' : '' }}>Administrador</option>
                                <option value="nutricionista" {{ old('role')=='nutricionista' ? 'selected' : '' }}>Nutricionista</option>
                                <option value="enfermer@" {{ old('role')=='enfermer@' ? 'selected' : '' }}>Enfermer@</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Guardar
                            </button>
                            <a href="{{ route('usuarios.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Volver
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
