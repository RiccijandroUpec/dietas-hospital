@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="font-semibold text-2xl text-gray-800">🔖 Subtipos de Dieta</h2>
                        <p class="text-gray-600 text-sm mt-1">Gestión de subcategorías de dietas</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('tipos-dieta.index') }}" class="inline-flex items-center px-4 py-2 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-md transition" title="Ver Tipos">
                            <span class="hidden md:inline">🏷️ Ver Tipos</span>
                            <span class="md:hidden text-lg">🏷️</span>
                        </a>
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'nutricionista')
                            <a href="{{ route('subtipos-dieta.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition" title="Nuevo Subtipo">
                                <span class="hidden md:inline">➕ Nuevo Subtipo</span>
                                <span class="md:hidden text-lg">➕</span>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-md flex items-start">
                        <span class="mr-3">✓</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Vista TABLA para Desktop -->
                <div class="hidden md:block w-full overflow-x-auto">
                    @if($subtipos->count() > 0)
                        <table class="w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Tipo</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Nombre</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Descripción</th>
                                    <th class="px-4 py-3 text-center font-semibold text-gray-700">Dietas</th>
                                    <th class="px-4 py-3 text-center font-semibold text-gray-700">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($subtipos as $subtipo)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3">
                                            <span class="inline-block bg-indigo-100 text-indigo-800 rounded-full px-3 py-1 text-xs font-semibold">
                                                {{ optional($subtipo->tipo)->nombre ?? '—' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $subtipo->nombre }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $subtipo->descripcion ?? '—' }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-block bg-purple-100 text-purple-800 rounded-full px-3 py-1 text-xs font-semibold">
                                                {{ $subtipo->dietas_count }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex justify-center gap-2">
                                                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'nutricionista')
                                                    <a href="{{ route('subtipos-dieta.edit', $subtipo) }}" class="px-3 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded text-xs font-medium transition">✏️ Editar</a>
                                                @endif
                                                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'administrador')
                                                    <form action="{{ route('subtipos-dieta.destroy', $subtipo) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar este subtipo de dieta?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 hover:bg-red-200 rounded text-xs font-medium transition">🗑️ Eliminar</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">📭</div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">No hay subtipos de dieta</h3>
                            <p class="text-gray-600 mb-4">Comienza creando el primer subtipo de dieta.</p>
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'nutricionista')
                                <a href="{{ route('subtipos-dieta.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                    ➕ Crear primer subtipo
                                </a>
                            @endif
                        </div>
                    @endif

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $subtipos->links() }}
                    </div>
                </div>

                <!-- Vista TARJETAS para Móvil -->
                <div class="md:hidden">
                    @if($subtipos->count() > 0)
                        <div class="space-y-4">
                            @foreach($subtipos as $subtipo)
                                <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500">
                                    <!-- Tipo -->
                                    <div class="mb-3">
                                        <div class="text-xs font-semibold text-gray-500 uppercase mb-1">🏷️ Tipo</div>
                                        <span class="inline-block bg-indigo-100 text-indigo-800 rounded-full px-3 py-1 text-sm font-semibold">
                                            {{ optional($subtipo->tipo)->nombre ?? 'Sin tipo' }}
                                        </span>
                                    </div>

                                    <!-- Nombre -->
                                    <div class="mb-3 pb-3 border-b border-gray-200">
                                        <div class="text-base font-bold text-gray-900">{{ $subtipo->nombre }}</div>
                                    </div>

                                    <!-- Descripción -->
                                    @if($subtipo->descripcion)
                                        <div class="mb-3">
                                            <div class="text-xs font-semibold text-gray-500 uppercase mb-1">📝 Descripción</div>
                                            <div class="text-sm text-gray-700">{{ $subtipo->descripcion }}</div>
                                        </div>
                                    @endif

                                    <!-- Contador de Dietas -->
                                    <div class="mb-4">
                                        <div class="text-xs font-semibold text-gray-500 uppercase mb-1">🥗 Dietas</div>
                                        <span class="inline-block bg-purple-100 text-purple-800 rounded-full px-3 py-1 text-sm font-semibold">
                                            {{ $subtipo->dietas_count }}
                                        </span>
                                    </div>

                                    <!-- Acciones -->
                                    <div class="flex justify-center gap-2">
                                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'nutricionista')
                                            <a href="{{ route('subtipos-dieta.edit', $subtipo) }}" class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-lg rounded-lg transition" title="Editar">
                                                ✏️
                                            </a>
                                        @endif
                                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'administrador')
                                            <form action="{{ route('subtipos-dieta.destroy', $subtipo) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Eliminar este subtipo de dieta?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-lg rounded-lg transition" title="Eliminar">🗑️</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $subtipos->links() }}
                        </div>
                    @else
                        <div class="bg-white rounded-lg shadow-md p-8 text-center">
                            <div class="text-6xl mb-4">📭</div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">No hay subtipos de dieta</h3>
                            <p class="text-gray-600 mb-4">Comienza creando el primer subtipo de dieta.</p>
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'nutricionista')
                                <a href="{{ route('subtipos-dieta.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                    ➕ Crear primer subtipo
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
