<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" title="Ir al panel" aria-label="Ir al panel">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dietas.index')" :active="request()->routeIs('dietas.*')">
                        <span class="inline-flex items-center">
                            <svg class="{{ request()->routeIs('dietas.*') ? 'h-5 w-5 me-2 text-blue-600' : 'h-5 w-5 me-2 text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 2h6a2 2 0 012 2v2h2a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h2V4a2 2 0 012-2zm0 6h6" />
                            </svg>
                            Dietas
                        </span>
                    </x-nav-link>
                    @if(auth()->check())
                        <x-nav-link :href="route('pacientes.index')" :active="request()->routeIs('pacientes.*')">
                            <span class="inline-flex items-center">
                                <svg class="{{ request()->routeIs('pacientes.*') ? 'h-5 w-5 me-2 text-emerald-600' : 'h-5 w-5 me-2 text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V9l-2-2-3 3V4a2 2 0 00-2-2H9a2 2 0 00-2 2v6L4 7 2 9v11h5a3 3 0 006 0 3 3 0 006 0z" />
                                </svg>
                                Pacientes
                            </span>
                        </x-nav-link>
                        <x-nav-link :href="route('registro-dietas.index')" :active="request()->routeIs('registro-dietas.*')">
                            <span class="inline-flex items-center">
                                <svg class="{{ request()->routeIs('registro-dietas.*') ? 'h-5 w-5 me-2 text-pink-600' : 'h-5 w-5 me-2 text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                                </svg>
                                Registro de dietas
                            </span>
                        </x-nav-link>
                        <x-nav-link :href="route('registro-refrigerios.index')" :active="request()->routeIs('registro-refrigerios.*')">
                            <span class="inline-flex items-center">
                                <svg class="{{ request()->routeIs('registro-refrigerios.*') ? 'h-5 w-5 me-2 text-orange-700' : 'h-5 w-5 me-2 text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                                </svg>
                                Registro de refrigerios
                            </span>
                        </x-nav-link>
                    @endif
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <x-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')">
                                Usuarios
                        </x-nav-link>
                        <x-nav-link :href="route('camas.index')" :active="request()->routeIs('camas.*')">
                            <span class="inline-flex items-center">
                                <svg class="{{ request()->routeIs('camas.*') ? 'h-5 w-5 me-2 text-violet-600' : 'h-5 w-5 me-2 text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18v7H3v-7zm3-3h6v3H6V7z" />
                                </svg>
                                Camas
                            </span>
                        </x-nav-link>
                    @endif
                    @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'usuario'))
                        <x-nav-link :href="route('servicios.index')" :active="request()->routeIs('servicios.*')">
                            <span class="inline-flex items-center">
                                <svg class="{{ request()->routeIs('servicios.*') ? 'h-5 w-5 me-2 text-sky-600' : 'h-5 w-5 me-2 text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16v4H4V4zm0 6h16v10H4V10z" />
                                </svg>
                                Servicios
                            </span>
                        </x-nav-link>
                    @endif
                    @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'nutricionista'))
                        <x-nav-link :href="route('tipos-dieta.index')" :active="request()->routeIs('tipos-dieta.*') || request()->routeIs('subtipos-dieta.*')">
                            <span class="inline-flex items-center">
                                <svg class="{{ request()->routeIs('tipos-dieta.*') || request()->routeIs('subtipos-dieta.*') ? 'h-5 w-5 me-2 text-amber-600' : 'h-5 w-5 me-2 text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                Config. Dietas
                            </span>
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Perfil') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Salir') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('dietas.index')" :active="request()->routeIs('dietas.*')">
                    <span class="inline-flex items-center">
                        <svg class="{{ request()->routeIs('dietas.*') ? 'h-5 w-5 me-2 text-blue-600' : 'h-5 w-5 me-2 text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 2h6a2 2 0 012 2v2h2a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h2V4a2 2 0 012-2zm0 6h6" />
                        </svg>
                        {{ __('Dietas') }}
                    </span>
                </x-responsive-nav-link>
                @if(auth()->check())
                    <x-responsive-nav-link :href="route('pacientes.index')" :active="request()->routeIs('pacientes.*')">
                        <span class="inline-flex items-center">
                            <svg class="{{ request()->routeIs('pacientes.*') ? 'h-5 w-5 me-2 text-emerald-600' : 'h-5 w-5 me-2 text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V9l-2-2-3 3V4a2 2 0 00-2-2H9a2 2 0 00-2 2v6L4 7 2 9v11h5a3 3 0 006 0 3 3 0 006 0z" />
                            </svg>
                            {{ __('Pacientes') }}
                        </span>
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('registro-dietas.index')" :active="request()->routeIs('registro-dietas.*')">
                        <span class="inline-flex items-center">
                            <svg class="{{ request()->routeIs('registro-dietas.*') ? 'h-5 w-5 me-2 text-pink-600' : 'h-5 w-5 me-2 text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                            </svg>
                            {{ __('Registro de dietas') }}
                        </span>
                    </x-responsive-nav-link>
                           <x-responsive-nav-link :href="route('registro-dietas.dashboard')" :active="request()->routeIs('registro-dietas.dashboard')">
                               <span class="inline-flex items-center">
                                   <svg class="{{ request()->routeIs('registro-dietas.dashboard') ? 'h-5 w-5 me-2 text-indigo-600' : 'h-5 w-5 me-2 text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                   </svg>
                                   {{ __('Dashboard de dietas') }}
                               </span>
                           </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('refrigerios.index')" :active="request()->routeIs('refrigerios.*')">
                        <span class="inline-flex items-center">
                            <svg class="{{ request()->routeIs('refrigerios.*') ? 'h-5 w-5 me-2 text-orange-600' : 'h-5 w-5 me-2 text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10M7 15h10" />
                            </svg>
                            {{ __('Refrigerios') }}
                        </span>
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('registro-refrigerios.index')" :active="request()->routeIs('registro-refrigerios.*')">
                        <span class="inline-flex items-center">
                            <svg class="{{ request()->routeIs('registro-refrigerios.*') ? 'h-5 w-5 me-2 text-orange-700' : 'h-5 w-5 me-2 text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                            </svg>
                            {{ __('Registro de refrigerios') }}
                        </span>
                    </x-responsive-nav-link>
                @endif
            @if(auth()->check() && auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')">
                    {{ __('Usuarios') }}
                </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('servicios.index')" :active="request()->routeIs('servicios.*')">
                        <span class="inline-flex items-center">
                            <svg class="{{ request()->routeIs('servicios.*') ? 'h-5 w-5 me-2 text-sky-600' : 'h-5 w-5 me-2 text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16v4H4V4zm0 6h16v10H4V10z" />
                            </svg>
                            {{ __('Servicios') }}
                        </span>
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('camas.index')" :active="request()->routeIs('camas.*')">
                        <span class="inline-flex items-center">
                            <svg class="{{ request()->routeIs('camas.*') ? 'h-5 w-5 me-2 text-violet-600' : 'h-5 w-5 me-2 text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18v7H3v-7zm3-3h6v3H6V7z" />
                            </svg>
                            {{ __('Camas') }}
                        </span>
                    </x-responsive-nav-link>
            @endif
            @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'nutricionista'))
                <x-responsive-nav-link :href="route('tipos-dieta.index')" :active="request()->routeIs('tipos-dieta.*') || request()->routeIs('subtipos-dieta.*')">
                    <span class="inline-flex items-center">
                        <svg class="{{ request()->routeIs('tipos-dieta.*') || request()->routeIs('subtipos-dieta.*') ? 'h-5 w-5 me-2 text-amber-600' : 'h-5 w-5 me-2 text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        {{ __('Config. Dietas') }}
                    </span>
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
