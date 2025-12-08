<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::user()->role === 'administrador' ? __('Panel de Bienvenida') : __('Inicio') }}
        </h2>
    </x-slot>

    <!-- CONTENIDO DINÁMICO SEGÚN ROL -->
    @if(Auth::user()->role === 'administrador')

        <!-- ========================================== -->
        <!-- VISTA PARA ADMINISTRADOR (Simple y Elegante) -->
        <!-- ========================================== -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="relative w-full h-[500px] rounded-2xl overflow-hidden shadow-2xl group">
                    
                    <!-- Imagen de Fondo Admin -->
                    <img src="{{ asset('images/inicioAdmin.png') }}" 
                         alt="Fondo Administrativo" 
                         class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105"
                         onerror="this.style.display='none'; this.parentNode.classList.add('bg-slate-800');">
                    
                    <!-- Overlay Oscuro -->
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/60 to-transparent"></div>

                    <!-- Contenido Texto -->
                    <div class="absolute inset-0 flex flex-col justify-center px-10 md:px-20 text-white">
                        <p class="text-blue-400 font-bold tracking-widest uppercase text-sm mb-4">Sistema de Gestión SIPRELI</p>
                        
                        <h1 class="text-5xl md:text-6xl font-extrabold mb-6 leading-tight">
                            Bienvenido, <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-cyan-300">
                                {{ Auth::user()->name }}
                            </span>
                        </h1>
                        
                        <p class="text-lg text-gray-300 max-w-lg mb-10 font-light">
                            El sistema está operativo. Puedes acceder a las herramientas de gestión desde el menú lateral o ir directamente al panel de control principal.
                        </p>

                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('admin.dashboard') }}" class="px-8 py-3 bg-white text-slate-900 font-bold rounded-lg hover:bg-blue-50 transition shadow-lg flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                Ir al Panel de Control
                            </a>
                            <a href="{{ route('admin.loans.index') }}" class="px-8 py-3 bg-white/10 text-white font-medium border border-white/20 rounded-lg hover:bg-white/20 transition flex items-center backdrop-blur-sm">
                                Ver Solicitudes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @else

        <!-- ========================================== -->
        <!-- VISTA PARA SOLICITANTE (Informativa) -->
        <!-- ========================================== -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <!-- SECCIÓN 1: BANNER HERO -->
                <div class="relative w-full h-64 md:h-96 bg-gray-800 rounded-2xl overflow-hidden shadow-xl mb-10 group">
                    <!-- Imagen de Fondo Solicitante -->
                    <img src="{{ asset('images/inicio.png') }}" 
                         alt="Conoce SIPRELI UPB" 
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100"
                         onerror="this.style.display='none'; this.parentNode.classList.add('bg-gradient-to-r', 'from-blue-800', 'to-blue-600');">
                    
                    <!-- Texto Superpuesto -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex items-end">
                        <div class="p-8 md:p-10 max-w-3xl">
                            <h1 class="text-white text-4xl md:text-5xl font-extrabold tracking-tight mb-3 drop-shadow-md">
                                Conoce SIPRELI UPB
                            </h1>
                            <p class="text-gray-200 text-lg md:text-xl font-light drop-shadow-sm">
                                Sistema Integral de Préstamos de Laboratorios e Insumos. <br class="hidden md:block">
                                Gestiona tus recursos académicos de manera rápida y sencilla.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN 2: TARJETAS INFORMATIVAS -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    
                    <!-- Tarjeta 1: ¿Qué solicitar? -->
                    <a href="{{ route('catalog.index') }}" class="block group h-full">
                        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 h-full hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out"></div>
                            
                            <div class="relative z-10">
                                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-6">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </div>
                                
                                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition-colors">
                                    ¿Qué puedes solicitar?
                                </h3>
                                <p class="text-gray-600 leading-relaxed mb-4">
                                    Descubre todos los recursos que puedes solicitar en la sección <strong>Recursos Disponibles</strong>. Equipos, insumos y laboratorios a tu alcance.
                                </p>
                                <span class="text-blue-600 font-medium text-sm flex items-center group-hover:underline">
                                    Ver Catálogo <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </span>
                            </div>
                        </div>
                    </a>

                    <!-- Tarjeta 2: ¿Dónde recoger? -->
                    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 h-full hover:shadow-lg transition-shadow duration-300 relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-green-50 rounded-full"></div>
                        
                        <div class="relative z-10">
                            <div class="w-14 h-14 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-6">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-900 mb-3">
                                ¿Dónde recoger?
                            </h3>
                            <p class="text-gray-600 leading-relaxed">
                                Puedes recoger el recurso una vez que te hayan <strong>autorizado</strong> el préstamo en la <br>
                                <span class="text-green-700 font-semibold">Oficina del Coordinador de Ingenierías.</span>
                            </p>
                        </div>
                    </div>

                    <!-- Tarjeta 3: Reglamento -->
                    <a href="{{ route('regulations.index') }}" class="block group h-full">
                        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 h-full hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-purple-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-out"></div>
                            
                            <div class="relative z-10">
                                <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mb-6">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                
                                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-purple-600 transition-colors">
                                    Revisa el reglamento
                                </h3>
                                <p class="text-gray-600 leading-relaxed mb-4">
                                    Infórmate acerca del reglamento vigente, dirígete a la sección <strong>Reglamentos Internos</strong> para comenzar la descarga.
                                </p>
                                <span class="text-purple-600 font-medium text-sm flex items-center group-hover:underline">
                                    Ir a Reglamentos <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </span>
                            </div>
                        </div>
                    </a>

                </div>
            </div>
        </div>

    @endif
</x-app-layout>