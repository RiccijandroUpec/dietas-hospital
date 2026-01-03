<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Camas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Selector de Servicio -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <label for="servicio" class="block text-sm font-medium mb-2">Seleccionar Servicio:</label>
                    <select id="servicio" name="servicio_id" class="flex-1 w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg">
                        <option value="">-- Selecciona un servicio --</option>
                        @foreach($servicios as $servicio)
                            <option value="{{ $servicio->id }}" {{ $servicioId == $servicio->id ? 'selected' : '' }}>
                                {{ $servicio->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if($servicioId && $camas->count() > 0)
                <!-- Grid de Camas -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-6">Camas del Servicio</h3>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            @foreach($camas as $cama)
                                @php
                                    $paciente = $pacientesPorCama->get($cama->id);
                                    $ocupada = $paciente !== null;
                                @endphp
                                
                                <!-- Cama Card -->
                                <div class="relative group">
                                    <button 
                                        class="w-full aspect-square rounded-lg font-bold text-sm flex flex-col items-center justify-center transition-all duration-200 {{ $ocupada ? 'bg-red-500 hover:bg-red-600 text-white' : 'bg-green-500 hover:bg-green-600 text-white' }} shadow-md hover:shadow-lg"
                                        onclick="toggleMenu({{ $cama->id }}, {{ $ocupada ? 'true' : 'false' }})">
                                        <span class="text-xl font-bold">{{ $cama->codigo }}</span>
                                        @if($ocupada)
                                            <span class="text-xs mt-1 truncate px-1">{{ $paciente->nombre }}</span>
                                        @else
                                            <span class="text-xs mt-1">Libre</span>
                                        @endif
                                    </button>

                                    <!-- Menú Flotante -->
                                    <div class="hidden absolute top-full mt-2 left-0 right-0 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg shadow-xl z-50 min-w-max" id="menu-{{ $cama->id }}">
                                        <div class="p-2 space-y-1">
                                            @if($ocupada)
                                                <a href="{{ route('pacientes.show', $paciente->id) }}" class="block w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 rounded">
                                                    👤 Ver Paciente
                                                </a>
                                                <a href="{{ route('registro-dietas.create') }}?paciente_id={{ $paciente->id }}" class="block w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 rounded">
                                                    🍽️ Registrar Dieta
                                                </a>
                                                <a href="{{ route('registro-refrigerios.create') }}?paciente_id={{ $paciente->id }}" class="block w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 rounded">
                                                    ☕ Registrar Refrigerio
                                                </a>
                                                <button onclick="darDeAlta({{ $paciente->id }}, '{{ $paciente->nombre }}')" class="block w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded">
                                                    ❌ Dar de Alta
                                                </button>
                                            @else
                                                <a href="{{ route('pacientes.create') }}?cama_id={{ $cama->id }}&servicio_id={{ $servicioId }}" class="block w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 rounded">
                                                    ➕ Agregar Paciente
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Leyenda -->
                        <div class="mt-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg flex gap-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-green-500 rounded"></div>
                                <span class="text-sm">Cama Libre</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-red-500 rounded"></div>
                                <span class="text-sm">Cama Ocupada</span>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($servicioId)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                        <p class="text-gray-500">No hay camas registradas para este servicio.</p>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                        <p class="text-gray-500">Selecciona un servicio para ver las camas.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Cambiar servicio
        document.getElementById('servicio').addEventListener('change', function() {
            if (this.value) {
                window.location.href = '?servicio_id=' + this.value;
            } else {
                window.location.href = '{{ route('camas-grafica.index') }}';
            }
        });

        // Mostrar/ocultar menú flotante
        function toggleMenu(camaId, ocupada) {
            const menu = document.getElementById('menu-' + camaId);
            
            // Cerrar otros menús
            document.querySelectorAll('[id^="menu-"]').forEach(m => {
                if (m !== menu) m.classList.add('hidden');
            });

            menu.classList.toggle('hidden');
        }

        // Cerrar menú al hacer clic fuera
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.group')) {
                document.querySelectorAll('[id^="menu-"]').forEach(m => {
                    m.classList.add('hidden');
                });
            }
        });

        // Dar de alta al paciente
        function darDeAlta(pacienteId, pacienteNombre) {
            if (confirm('¿Estás seguro de que deseas dar de alta a ' + pacienteNombre + '?')) {
                fetch(`/pacientes/${pacienteId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ estado: 'alta' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Paciente dado de alta correctamente');
                        location.reload();
                    } else {
                        alert('Error al dar de alta: ' + (data.message || 'Error desconocido'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al procesar la solicitud');
                });
            }
        }
    </script>
</x-app-layout>
