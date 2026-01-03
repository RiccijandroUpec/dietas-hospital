<div x-data="{ sidebarOpen: false, mobileSidebarOpen: false }" class="flex h-screen bg-gray-100">
    <!-- Overlay para mobile -->
    <div x-show="mobileSidebarOpen" 
         x-cloak
         @click="mobileSidebarOpen = false"
         class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    <div :class="[
        'fixed left-0 top-0 bottom-0 bg-gradient-to-b from-blue-900 to-blue-800 text-white transition-all duration-300 shadow-lg overflow-y-auto',
        'lg:z-40 z-50',
        sidebarOpen ? 'lg:w-64' : 'lg:w-20',
        mobileSidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full lg:translate-x-0'
    ]">
        
        <!-- Logo Section -->
        <div class="flex items-center justify-between p-4 border-b border-blue-700 h-16">
            <a href="{{ route('dashboard') }}" title="Ir al panel" aria-label="Ir al panel" class="flex items-center">
                <x-application-logo class="h-8 w-auto fill-current" />
            </a>
            <button @click="sidebarOpen = !sidebarOpen" class="p-1 hover:bg-blue-700 rounded hidden lg:block">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <button @click="mobileSidebarOpen = false" class="p-1 hover:bg-blue-700 rounded lg:hidden">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="mt-4 space-y-1 px-2">
            
            <!-- Dietas Link -->
            <a href="{{ route('dietas.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-all {{ request()->routeIs('dietas.*') ? 'bg-blue-600 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                <svg class="h-5 w-5 me-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 2h6a2 2 0 012 2v2h2a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h2V4a2 2 0 012-2zm0 6h6" />
                </svg>
                <span :class="!sidebarOpen && 'lg:hidden'" class="text-sm font-medium">Dietas</span>
            </a>

            @if(auth()->check())
                <!-- Pacientes Link -->
                <a href="{{ route('pacientes.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-all {{ request()->routeIs('pacientes.*') ? 'bg-blue-600 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                    <svg class="h-5 w-5 me-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V9l-2-2-3 3V4a2 2 0 00-2-2H9a2 2 0 00-2 2v6L4 7 2 9v11h5a3 3 0 006 0 3 3 0 006 0z" />
                    </svg>
                    <span :class="!sidebarOpen && 'lg:hidden'" class="text-sm font-medium">Pacientes</span>
                </a>

                <!-- Registros Collapsible -->
                <div x-data="{ registrosOpen: {{ request()->routeIs('registro-dietas.*', 'registro-refrigerios.*') ? 'true' : 'false' }} }">
                    <button @click="registrosOpen = !registrosOpen" class="w-full flex items-center px-4 py-3 rounded-lg transition-all text-blue-100 hover:bg-blue-700 group">
                        <svg class="h-5 w-5 me-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                        </svg>
                        <span :class="!sidebarOpen && 'lg:hidden'" class="text-sm font-medium flex-1 text-left">Registros</span>
                        <svg :class="registrosOpen && 'rotate-180'" class="h-4 w-4 transition-transform" :class="!sidebarOpen && 'lg:hidden'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </button>

                    <div x-show="registrosOpen" class="pl-8 mt-1 space-y-1" :class="!sidebarOpen && 'lg:hidden'">
                        <a href="{{ route('registro-dietas.index') }}" class="flex items-center px-4 py-2 rounded-lg text-sm transition-all {{ request()->routeIs('registro-dietas.index') ? 'bg-blue-600 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                            <svg class="h-4 w-4 me-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                            </svg>
                            Dietas
                        </a>
                        <a href="{{ route('registro-refrigerios.index') }}" class="flex items-center px-4 py-2 rounded-lg text-sm transition-all {{ request()->routeIs('registro-refrigerios.index') ? 'bg-blue-600 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                            <svg class="h-4 w-4 me-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                            </svg>
                            Refrigerios
                        </a>
                    </div>
                </div>

                @if(auth()->user()->role !== 'admin' && auth()->user()->role !== 'usuario')
                    <!-- Refrigerios Link -->
                    <a href="{{ route('refrigerios.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-all {{ request()->routeIs('refrigerios.*') ? 'bg-blue-600 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                        <svg class="h-5 w-5 me-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10M7 15h10" />
                        </svg>
                        <span :class="!sidebarOpen && 'lg:hidden'" class="text-sm font-medium">Refrigerios</span>
                    </a>
                @endif
            @endif

            @if(auth()->check() && auth()->user()->role === 'admin')
                <div class="border-t border-blue-700 my-2"></div>

                <!-- Admin Collapsible -->
                <div x-data="{ adminOpen: {{ request()->routeIs('usuarios.*', 'camas.*', 'camas-grafica.*', 'servicios.*', 'tipos-dieta.*') ? 'true' : 'false' }} }">
                    <button @click="adminOpen = !adminOpen" class="w-full flex items-center px-4 py-3 rounded-lg transition-all text-blue-100 hover:bg-blue-700">
                        <svg class="h-5 w-5 me-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span :class="!sidebarOpen && 'lg:hidden'" class="text-sm font-medium flex-1 text-left">Administración</span>
                        <svg :class="adminOpen && 'rotate-180'" class="h-4 w-4 transition-transform" :class="!sidebarOpen && 'lg:hidden'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    </button>

                    <div x-show="adminOpen" class="pl-8 mt-1 space-y-1" :class="!sidebarOpen && 'lg:hidden'">
                        <a href="{{ route('usuarios.index') }}" class="flex items-center px-4 py-2 rounded-lg text-sm transition-all {{ request()->routeIs('usuarios.*') ? 'bg-blue-600 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                            <svg class="h-4 w-4 me-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.048M12 12H8m4 0h4m-2-2v4m5.5-10.5a1 1 0 11-2 0 1 1 0 012 0z" />
                            </svg>
                            Usuarios
                        </a>
                        <a href="{{ route('servicios.index') }}" class="flex items-center px-4 py-2 rounded-lg text-sm transition-all {{ request()->routeIs('servicios.*') ? 'bg-blue-600 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                            <svg class="h-4 w-4 me-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16v4H4V4zm0 6h16v10H4V10z" />
                            </svg>
                            Servicios
                        </a>
                        <a href="{{ route('camas.index') }}" class="flex items-center px-4 py-2 rounded-lg text-sm transition-all {{ request()->routeIs('camas.index') ? 'bg-blue-600 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                            <svg class="h-4 w-4 me-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18v7H3v-7zm3-3h6v3H6V7z" />
                            </svg>
                            Camas
                        </a>
                        <a href="{{ route('camas-grafica.index') }}" class="flex items-center px-4 py-2 rounded-lg text-sm transition-all {{ request()->routeIs('camas-grafica.*') ? 'bg-blue-600 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                            <svg class="h-4 w-4 me-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h7v7H3V3zm11 0h7v7h-7V3zM3 14h7v7H3v-7zm11 0h7v7h-7v-7z" />
                            </svg>
                            📊 Camas Gráfica
                        </a>
                        <a href="{{ route('tipos-dieta.index') }}" class="flex items-center px-4 py-2 rounded-lg text-sm transition-all {{ request()->routeIs('tipos-dieta.*', 'subtipos-dieta.*') ? 'bg-blue-600 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                            <svg class="h-4 w-4 me-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Config. Dietas
                        </a>
                        <a href="{{ route('schedule-config.index') }}" class="flex items-center px-4 py-2 rounded-lg text-sm transition-all {{ request()->routeIs('schedule-config.*') ? 'bg-blue-600 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                            <svg class="h-4 w-4 me-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Horarios
                        </a>
                    </div>
                </div>
            @elseif(auth()->check() && auth()->user()->role === 'nutricionista')
                <div class="border-t border-blue-700 my-2"></div>
                <a href="{{ route('camas-grafica.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-all {{ request()->routeIs('camas-grafica.*') ? 'bg-blue-600 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                    <svg class="h-5 w-5 me-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h7v7H3V3zm11 0h7v7h-7V3zM3 14h7v7H3v-7zm11 0h7v7h-7v-7z" />
                    </svg>
                    <span :class="!sidebarOpen && 'lg:hidden'" class="text-sm font-medium">📊 Camas Gráfica</span>
                </a>
                <a href="{{ route('tipos-dieta.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-all {{ request()->routeIs('tipos-dieta.*', 'subtipos-dieta.*') ? 'bg-blue-600 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                    <svg class="h-5 w-5 me-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span :class="!sidebarOpen && 'lg:hidden'" class="text-sm font-medium">Config. Dietas</span>
                </a>
            @endif

            @if(auth()->check() && auth()->user()->role === 'usuario')
                <div class="border-t border-blue-700 my-2"></div>
                <a href="{{ route('servicios.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-all {{ request()->routeIs('servicios.*') ? 'bg-blue-600 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                    <svg class="h-5 w-5 me-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16v4H4V4zm0 6h16v10H4V10z" />
                    </svg>
                    <span :class="!sidebarOpen && 'lg:hidden'" class="text-sm font-medium">Servicios</span>
                </a>
            @endif

            @if(auth()->check())
                <div class="border-t border-blue-700 my-2"></div>
                <a href="{{ route('developer') }}" class="flex items-center px-4 py-3 rounded-lg transition-all {{ request()->routeIs('developer') ? 'bg-blue-600 text-white' : 'text-blue-100 hover:bg-blue-700' }}">
                    <svg class="h-5 w-5 me-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V4z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16h18M10 20h4" />
                    </svg>
                    <span :class="!sidebarOpen && 'lg:hidden'" class="text-sm font-medium">Programador</span>
                </a>
            @endif
        </nav>

        <!-- Footer Section -->
        <div class="absolute bottom-0 left-0 right-0 border-t border-blue-700 p-2">
            <div x-data="{ userOpen: false }" class="space-y-1">
                <button @click="userOpen = !userOpen" class="w-full flex items-center px-4 py-3 rounded-lg hover:bg-blue-700 transition-all">
                    <svg class="h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m0 0a4 4 0 004 4h8a4 4 0 004-4m-2-2a4 4 0 00-8 0" />
                    </svg>
                    <span :class="!sidebarOpen && 'lg:hidden'" class="text-sm font-medium ms-3 flex-1 text-left truncate">{{ Auth::user()->name }}</span>
                    <svg :class="userOpen && 'rotate-180'" class="h-4 w-4 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </button>

                <div x-show="userOpen" class="pl-8 mt-1 space-y-1" :class="!sidebarOpen && 'lg:hidden'">
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 rounded-lg text-sm text-blue-100 hover:bg-blue-700 transition-all">
                        <svg class="h-4 w-4 me-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Perfil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center px-4 py-2 rounded-lg text-sm text-blue-100 hover:bg-blue-700 transition-all">
                            <svg class="h-4 w-4 me-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Salir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-20'" class="flex-1 flex flex-col transition-all duration-300">
        <!-- Mobile Navbar -->
        <div class="lg:hidden bg-white border-b border-gray-200 sticky top-0 z-30">
            <div class="flex items-center justify-between px-4 py-3">
                <button @click="mobileSidebarOpen = true" class="p-2 hover:bg-gray-100 rounded-lg">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <a href="{{ route('dashboard') }}" class="text-lg font-bold text-blue-900">Sistema de Dietas</a>
                <div class="w-10"></div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            <div class="px-4 sm:px-0">
                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </main>
    </div>
</div>
