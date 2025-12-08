<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo y Enlaces de Navegación (Izquierda) -->
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <!-- LOGO UPB (Imagen Real) -->
                        <!-- Asegúrate de que la imagen esté en public/images/logo2-upb.png -->
                        <img src="{{ asset('images/logo2-upb.png') }}" 
                             class="h-12 w-auto object-contain" 
                             alt="Logo UPB"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        
                        <!-- Fallback (Solo se muestra si la imagen falla o no existe) -->
                        <div class="h-10 w-32 bg-blue-600/10 rounded flex items-center justify-center text-xs text-blue-900 font-bold border border-blue-200 hidden">
                            LOGO UPB
                        </div>
                    </a>
                </div>

                <!-- ELIMINAMOS los Navigation Links que ahora están en el Sidebar -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <!-- Dejamos este espacio intencionalmente vacío -->
                </div>
            </div>

            <!-- Settings (Notificaciones y Perfil - Derecha) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Grupo de Notificaciones y Perfil -->
                <div class="flex items-center space-x-4">
                    
                    <!-- 1. Notificaciones (Campana) -->
                    <button class="relative p-2 text-gray-500 hover:text-gray-700 transition duration-150 ease-in-out rounded-full hover:bg-gray-100">
                        <!-- Icono de Campana -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341A6.002 6.002 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <!-- Indicador de Notificación -->
                        <span class="absolute top-1 right-1 block h-2 w-2 rounded-full ring-2 ring-white bg-red-600"></span>
                    </button>
                    
                    <!-- 2. Menú Desplegable del Usuario -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center space-x-3 text-sm font-medium text-gray-700 hover:text-gray-900 hover:border-gray-300 focus:outline-none transition ease-in-out duration-150">
                                
                                <!-- Círculo de Perfil (Avatar Placeholder) -->
                                <!-- Puedes reemplazar esto con <img src="..." class="w-10 h-10 rounded-full"> si tienes avatars -->
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-gray-600 border border-blue-300">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>

                                <!-- Nombre del Usuario -->
                                <div>{{ Auth::user()->name }}</div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Contenido del Dropdown (Perfil y Logout) -->
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Perfil') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Cerrar Sesión') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger (Solo visible en Mobile) -->
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

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        
        <!-- Enlaces de Dashboard (Los dejamos en mobile para fácil acceso) -->
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <!-- Nota: Aquí se podrían añadir los enlaces de Admin y Solicitante si no se usa el Sidebar en mobile -->
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>