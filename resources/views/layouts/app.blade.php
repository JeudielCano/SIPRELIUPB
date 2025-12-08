<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIPRELI UPB') }}</title>

        <!-- FAVICON (Icono de la pestaña) -->
        <!-- Asegúrate de que la imagen exista en public/images/ -->
        <link rel="icon" href="{{ asset('images/icon-sipreli.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <!-- Fondo general gris muy suave -->
        <div class="min-h-screen bg-gray-50 flex flex-col justify-between">
            
            <!-- 1. Navegación Superior -->
            @include('layouts.navigation')

            <!-- Contenedor de Sidebar y Contenido -->
            <div class="flex flex-grow">

                <!-- Bloque DINÁMICO del SIDEBAR -->
                @if (Auth::check())
                    @php
                        $activeClasses = 'flex items-center p-2 text-gray-900 rounded-lg bg-gray-200 group';
                        $inactiveClasses = 'flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group';
                        // Color de icono activo/inactivo
                        $iconActive = 'text-gray-900';
                        $iconInactive = 'text-gray-500 group-hover:text-gray-900 transition duration-75';
                    @endphp

                    <!-- Sidebar para ADMINISTRADOR -->
                    @if (Auth::user()->role === 'administrador')
                        <aside class="w-64 flex-shrink-0 hidden md:block" aria-label="Sidebar">
                            <div class="h-full px-3 py-4 overflow-y-auto bg-white border-r border-gray-200 sticky top-0">
                                
                                <!-- Header del Sidebar (SOLO LOGO) -->
                                <div class="flex items-center justify-center p-2 mb-4 border-b border-gray-200 pb-4">
                                    <!-- LOGO SIPRELI (Sidebar) -->
                                    <img src="{{ asset('images/logo-sipreli.png') }}" 
                                         class="h-12 w-auto rounded-lg object-contain bg-gray-50 border border-gray-100 p-1" 
                                         alt="Logo SIPRELI UPB"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    
                                    <!-- Fallback -->
                                    <div class="h-12 w-32 bg-blue-900 rounded-lg flex items-center justify-center text-white hidden">
                                        <span class="text-sm font-bold">SIPRELI UPB</span>
                                    </div>
                                </div>
                                
                                <!-- MENÚ DE NAVEGACIÓN DEL ADMIN -->
                                <ul class="space-y-2 font-medium">
                                    <!-- 1. Dashboard -->
                                    <li>
                                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? $activeClasses : $inactiveClasses }}">
                                            <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                            <span class="ms-3">Dashboard</span>
                                        </a>
                                    </li>
                                    
                                    <!-- 2. Gestionar usuarios -->
                                    <li>
                                        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? $activeClasses : $inactiveClasses }}">
                                            <svg class="w-5 h-5 {{ request()->routeIs('admin.users.*') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                            <span class="ms-3">Gestionar usuarios</span>
                                        </a>
                                    </li>
                                    
                                    <!-- 3. Solicitudes -->
                                    <li>
                                        <a href="{{ route('admin.loans.index') }}" class="{{ request()->routeIs('admin.loans.index') || request()->routeIs('admin.loans.show') ? $activeClasses : $inactiveClasses }}">
                                            <svg class="w-5 h-5 {{ request()->routeIs('admin.loans.index') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                            <span class="ms-3">Solicitudes</span>
                                        </a>
                                    </li>

                                    <!-- 4. Préstamo externo -->
                                    <li>
                                        <a href="{{ route('admin.loans.external') }}" class="{{ request()->routeIs('admin.loans.external') ? $activeClasses : $inactiveClasses }}">
                                            <svg class="w-5 h-5 {{ request()->routeIs('admin.loans.external') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            <span class="ms-3">Préstamo externo</span>
                                        </a>
                                    </li>

                                    <!-- 5. Préstamos activos -->
                                    <li>
                                        <a href="{{ route('admin.active-loans') }}" class="{{ request()->routeIs('admin.active-loans') ? $activeClasses : $inactiveClasses }}">
                                            <svg class="w-5 h-5 {{ request()->routeIs('admin.active-loans') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span class="ms-3">Préstamos activos</span>
                                        </a>
                                    </li>
                                    
                                    <hr class="my-3 border-gray-200" />

                                    <!-- 6. Inventario -->
                                    <li>
                                        <a href="{{ route('admin.resources.index') }}" class="{{ request()->routeIs('admin.resources.index') || request()->routeIs('admin.resources.edit') ? $activeClasses : $inactiveClasses }}">
                                            <svg class="w-5 h-5 {{ request()->routeIs('admin.resources.index') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                            <span class="ms-3">Inventario</span>
                                        </a>
                                    </li>
                                    
                                    <!-- 7. Registrar recursos -->
                                    <li>
                                        <a href="{{ route('admin.resources.create') }}" class="{{ request()->routeIs('admin.resources.create') ? $activeClasses : $inactiveClasses }}">
                                            <svg class="w-5 h-5 {{ request()->routeIs('admin.resources.create') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span class="ms-3">Registrar recursos</span>
                                        </a>
                                    </li>

                                    <!-- 8. Reportes -->
                                    <li>
                                        <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? $activeClasses : $inactiveClasses }}">
                                            <svg class="w-5 h-5 {{ request()->routeIs('admin.reports.*') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <span class="ms-3">Reportes</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </aside>

                    <!-- Sidebar para SOLICITANTE -->
                    @elseif (Auth::user()->role === 'solicitante')
                        <aside class="w-64 flex-shrink-0 hidden md:block" aria-label="Sidebar">
                            <div class="h-full px-3 py-4 overflow-y-auto bg-white border-r border-gray-200 sticky top-0">
                                
                                <!-- Header del Sidebar (SOLO LOGO) -->
                                <div class="flex items-center justify-center p-2 mb-4 border-b border-gray-200 pb-4">
                                    <!-- LOGO SIPRELI (Sidebar) - Adaptado al ancho -->
                                    <img src="{{ asset('images/logo-sipreli.png') }}" 
                                         class="w-full h-auto object-contain" 
                                         alt="Logo SIPRELI UPB"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    
                                    <!-- Fallback -->
                                    <div class="h-12 w-full bg-blue-900 rounded-lg flex items-center justify-center text-white hidden">
                                        <span class="text-sm font-bold">SIPRELI UPB</span>
                                    </div>
                                </div>
                                
                                <!-- Menú de Navegación del SOLICITANTE -->
                                <ul class="space-y-2 font-medium">
                                    
                                    <!-- 1. Inicio -->
                                    <li>
                                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? $activeClasses : $inactiveClasses }}">
                                            <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                            <span class="ms-3">Inicio</span>
                                        </a>
                                    </li>
                                    
                                    <!-- 2. Solicitar Préstamo -->
                                    <li>
                                        <a href="{{ route('loans.create') }}" class="{{ request()->routeIs('loans.create') ? $activeClasses : $inactiveClasses }}">
                                            <svg class="w-5 h-5 {{ request()->routeIs('loans.create') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span class="ms-3">Solicitar préstamo</span>
                                        </a>
                                    </li>
                                    
                                    <!-- 3. Préstamo externo -->
                                    <li>
                                        <a href="{{ route('loans.external.create') }}" class="{{ request()->routeIs('loans.external.*') ? $activeClasses : $inactiveClasses }}">
                                            <svg class="w-5 h-5 {{ request()->routeIs('loans.external.*') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="ms-3">Préstamo externo</span>
                                        </a>
                                    </li>

                                    <!-- 4. Mis Préstamos -->
                                    <li>
                                        <a href="{{ route('loans.index') }}" class="{{ request()->routeIs('loans.index') || request()->routeIs('loans.show') ? $activeClasses : $inactiveClasses }}">
                                            <svg class="w-5 h-5 {{ request()->routeIs('loans.index') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                            <span class="ms-3">Mis préstamos</span>
                                        </a>
                                    </li>

                                    <!-- 5. Ver Recursos Disponibles -->
                                    <li>
                                        <a href="{{ route('catalog.index') }}" class="{{ request()->routeIs('catalog.*') ? $activeClasses : $inactiveClasses }}">
                                            <svg class="w-5 h-5 {{ request()->routeIs('catalog.*') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                            <span class="ms-3">Ver recursos disponibles</span>
                                        </a>
                                    </li>

                                    <!-- 6. Reglamentos -->
                                    <li>
                                        <a href="{{ route('regulations.index') }}" class="{{ request()->routeIs('regulations.*') ? $activeClasses : $inactiveClasses }}">
                                            <svg class="w-5 h-5 {{ request()->routeIs('regulations.*') ? $iconActive : $iconInactive }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                            <span class="ms-3">Reglamentos internos</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </aside>
                    @endif
                @endif
                <!-- FIN DEL BLOQUE DINÁMICO del SIDEBAR -->
                
                <!-- Contenido Principal -->
                <div class="flex-grow min-w-0">
                    
                    <!-- Page Heading -->
                    @if (isset($header))
                        <header class="bg-white border-b border-gray-100 shadow-sm">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    <!-- Page Content -->
                    <main class="py-12">
                        <!-- El margen se maneja automáticamente gracias a la estructura flex -->
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                             {{ $slot }}
                        </div>
                    </main>

                </div><!-- Fin flex-grow -->
            </div><!-- Fin flex -->

            <!-- FOOTER SIPRELI UPB -->
            <footer class="bg-slate-600 text-white mt-10 border-t border-slate-500">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative">
                    
                    <!-- PARTE SUPERIOR: Logo, Contacto y Carreras -->
                    <div class="flex flex-col md:flex-row items-start justify-between gap-8 mb-6">
                        
                        <!-- 1. Logo Principal y Título (Izquierda) -->
                        <div class="flex-shrink-0 flex items-start space-x-4">
                            <!-- LOGO UPB (Imagen Real) -->
                            <img src="{{ asset('images/logo-upb.png') }}" 
                                 class="h-20 w-auto object-contain" 
                                 alt="Logo Universidad Politécnica de Bacalar"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            
                            <!-- Fallback (Solo se muestra si la imagen falla) -->
                            <div class="h-20 w-20 bg-white/10 rounded-lg flex items-center justify-center border border-white/50 hidden">
                                <span class="text-xs font-bold text-center leading-tight">LOGO<br>UPB</span>
                            </div>
                            
                            <div class="flex flex-col justify-start pt-2">
                                <span class="text-xl font-bold tracking-wide leading-none">Universidad Politécnica de Bacalar</span>
                                
                                <!-- 2. Información de Contacto -->
                                <div class="mt-2 text-sm font-light leading-relaxed text-gray-200">
                                    <p>Avenida 39, REG 12 MZ 325 LT 1 entre calle 56 y 46-A, C.P. 77930,</p>
                                    <p>Bacalar, Q.Roo. Tel: 983 128 1591</p>
                                    
                                    <a href="#" class="text-yellow-400 hover:text-yellow-300 text-sm underline mt-1 inline-block transition-colors">
                                        Protección de Datos Personales
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Logos de Carreras (Derecha) -->
                        <div class="flex items-center space-x-4 ml-auto md:ml-0 md:pt-4">
                            <!-- Logo iSoftware -->
                            <div class="h-16 w-16 bg-gradient-to-br from-teal-400 to-green-600 rounded-lg flex items-center justify-center text-white shadow-lg">
                                <span class="text-sm font-bold">iSoftware</span>
                            </div>

                            <!-- Logo AFX -->
                            <div class="h-16 w-16 bg-blue-900 rounded-lg flex items-center justify-center text-white shadow-lg">
                                <span class="text-sm font-bold">AFX</span>
                            </div>
                        </div>
                    </div>

                    <!-- LÍNEA DIVISORIA -->
                    <hr class="border-white/30 my-6" />

                    <!-- PARTE INFERIOR: Copyright e Icono Correo -->
                    <div class="flex justify-between items-center relative">
                        
                        <!-- Copyright -->
                        <p class="text-xs text-gray-300">
                            &copy; {{ date('Y') }} Universidad Politécnica de Bacalar. All rights reserved.
                        </p>

                        <!-- Icono de Correo -->
                        <a href="mailto:soporte@upbacalar.edu.mx" class="hover:scale-110 transition-transform duration-200 group">
                            <div class="bg-white p-1.5 rounded-full shadow-md">
                                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                </svg>
                            </div>
                        </a>
                    </div>

                </div>
            </footer>

        </div>
    </body>
</html>