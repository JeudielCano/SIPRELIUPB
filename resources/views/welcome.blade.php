<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Bienvenido a {{ config('app.name', 'SIPRELI UPB') }}</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('images/icon-sipreliupb.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans text-gray-900 bg-gray-50">
        
        <div class="min-h-screen flex flex-col justify-between">

            <!-- 1. HEADER / NAVEGACIÓN SUPERIOR (Transparente) -->
            <header class="absolute top-0 left-0 w-full z-50">
                <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-24 flex items-center justify-between">
                    
                    <!-- Logo / Marca -->
                    <div class="flex items-center gap-3">
                        <div class="flex flex-col">
                            <span class="text-white font-black text-2xl tracking-wider drop-shadow-md leading-none">SIPRELI</span>
                            <span class="text-blue-200 text-xs font-bold tracking-widest uppercase drop-shadow-sm">Universidad Politécnica de Bacalar</span>
                        </div>
                    </div>

                    <!-- Enlaces de Autenticación -->
                    @if (Route::has('login'))
                        <div class="flex items-center gap-4">
                            @auth
                                <!-- Redirección inteligente según el rol -->
                                <a href="{{ Auth::user()->role === 'administrador' ? route('admin.dashboard') : url('/dashboard') }}" class="text-white font-semibold hover:text-blue-200 transition text-sm md:text-base border border-white/30 px-4 py-2 rounded-lg backdrop-blur-sm hover:bg-white/10">
                                    Ir al Panel
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-white font-semibold hover:text-blue-200 transition text-sm md:text-base hidden sm:block">
                                    Iniciar Sesión
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-white text-blue-900 hover:bg-blue-50 px-5 py-2.5 rounded-full font-bold shadow-lg transition transform hover:-translate-y-0.5 text-sm md:text-base">
                                        Registrarse
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </nav>
            </header>

            <!-- LÓGICA DE VISTAS DIFERENCIADAS -->
            @if(Auth::check() && Auth::user()->role === 'administrador')
                
                <!-- ========================================== -->
                <!-- VISTA EXCLUSIVA PARA ADMINISTRADOR -->
                <!-- ========================================== -->
                <main class="flex-grow relative flex items-center justify-center min-h-screen">
                    <!-- Imagen de Fondo Admin (welcome2.png) -->
                    <div class="absolute inset-0 z-0 overflow-hidden">
                        <img src="{{ asset('images/welcome2.png') }}" 
                             alt="Fondo Admin" 
                             class="w-full h-full object-cover object-center"
                             onerror="this.style.display='none'; this.parentNode.classList.add('bg-slate-900');">
                        <!-- Overlay suave para leer el texto -->
                        <div class="absolute inset-0 bg-black/30"></div>
                    </div>

                    <!-- Contenido Simple y Elegante -->
                    <div class="relative z-10 text-center text-white px-4">
                        <p class="text-xl md:text-2xl font-light tracking-widest mb-2 opacity-90">SISTEMA DE GESTIÓN</p>
                        <h1 class="text-5xl md:text-7xl font-bold mb-6 drop-shadow-lg">
                            Buenos días, {{ Auth::user()->name }}
                        </h1>
                        <p class="text-lg text-gray-200 mb-8 max-w-2xl mx-auto">
                            Todo está listo para administrar los recursos de la universidad.
                        </p>
                        
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-slate-900 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10 shadow-xl transition-transform hover:scale-105">
                            Acceder al Dashboard
                            <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </main>

            @else

                <!-- ========================================== -->
                <!-- VISTA PARA SOLICITANTES Y VISITANTES -->
                <!-- ========================================== -->
                <main class="flex-grow relative flex items-center justify-center min-h-screen">
                    <!-- Imagen de Fondo General (welcome1.png) -->
                    <div class="absolute inset-0 z-0 overflow-hidden">
                        <img src="{{ asset('images/welcome1.png') }}" 
                             alt="Fondo UPB" 
                             class="w-full h-full object-cover object-center scale-105 animate-fade-in-slow"
                             onerror="this.style.display='none'; this.parentNode.classList.add('bg-gradient-to-br', 'from-blue-900', 'to-slate-800');">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 via-blue-900/70 to-slate-900/40"></div>
                    </div>

                    <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white mt-16">
                        <!-- Badge -->
                        <div class="inline-block mb-6 px-4 py-1.5 rounded-full bg-blue-500/20 border border-blue-400/30 backdrop-blur-sm">
                            <span class="text-blue-200 text-sm font-bold tracking-wider uppercase">Gestión de recursos de las ingenierías</span>
                        </div>

                        <!-- Título -->
                        <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold tracking-tight mb-6 leading-tight drop-shadow-xl">
                            Los préstamos son más <br class="hidden md:block">
                            rápidos y sencillos con <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-cyan-300">SIPRELI UPB</span>
                        </h1>

                        <!-- Texto -->
                        <p class="text-lg md:text-2xl text-gray-200 mb-10 max-w-3xl mx-auto leading-relaxed font-light drop-shadow-md">
                            Solicita un recurso de las ingenierías de la Universidad Politécnica de Bacalar completando el registro y llenando tu solicitud en minutos.
                        </p>

                        <!-- Botones -->
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto px-8 py-4 bg-white text-blue-900 text-lg font-bold rounded-xl shadow-2xl hover:bg-gray-100 hover:shadow-white/20 transition transform hover:-translate-y-1 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                    Ir a mi Dashboard
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white text-lg font-bold rounded-xl shadow-lg shadow-blue-900/50 transition transform hover:-translate-y-1 flex items-center justify-center ring-4 ring-blue-600/30">
                                    Crear una Cuenta
                                </a>
                                <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-transparent border-2 border-white/30 hover:border-white text-white text-lg font-bold rounded-xl hover:bg-white/10 transition backdrop-blur-sm flex items-center justify-center">
                                    Iniciar Sesión
                                </a>
                            @endauth
                        </div>

                        <!-- Estadísticas -->
                        <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8 border-t border-white/10 pt-8 max-w-5xl mx-auto text-blue-100/90">
                            <div class="flex flex-col items-center justify-center">
                                <span class="block text-2xl md:text-3xl font-bold text-white">24/7</span>
                                <span class="text-sm uppercase tracking-wider">Acceso Web</span>
                            </div>
                            <div class="flex flex-col items-center justify-center text-center">
                                <p class="text-lg font-medium text-white leading-tight">
                                    Accede a los recursos que las ingenierías tienen para ti
                                </p>
                            </div>
                            <div class="flex flex-col items-center justify-center">
                                <span class="block text-2xl md:text-3xl font-bold text-white text-center leading-none">Más ágil <br>y rápido</span>
                            </div>
                        </div>
                    </div>
                </main>

            @endif

            <!-- 3. FOOTER INTEGRADO (Mismo diseño para todos) -->
            <div class="bg-slate-600">
                <footer class="bg-slate-600 text-white border-t border-slate-500">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative">
                        
                        <!-- PARTE SUPERIOR -->
                        <div class="flex flex-col md:flex-row items-start justify-between gap-8 mb-6">
                            
                            <!-- Logo y Contacto -->
                            <div class="flex-shrink-0 flex items-start space-x-4">
                                <img src="{{ asset('images/logo-upb.png') }}" class="h-20 w-auto object-contain" alt="Logo UPB" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="h-20 w-20 bg-white/10 rounded-lg flex items-center justify-center border border-white/50 hidden">
                                    <span class="text-xs font-bold text-center leading-tight">LOGO<br>UPB</span>
                                </div>
                                <div class="flex flex-col justify-start pt-2">
                                    <span class="text-xl font-bold tracking-wide leading-none">Universidad Politécnica de Bacalar</span>
                                    <div class="mt-2 text-sm font-light leading-relaxed text-gray-200">
                                        <p>Avenida 39, REG 12 MZ 325 LT 1 entre calle 56 y 46-A, C.P. 77930,</p>
                                        <p>Bacalar, Q.Roo. Tel: 983 128 1591</p>
                                        <a href="#" class="text-yellow-400 hover:text-yellow-300 text-sm underline mt-1 inline-block transition-colors">
                                            Protección de Datos Personales
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Logos Carreras -->
                            <div class="flex items-center space-x-4 ml-auto md:ml-0 md:pt-4">
                                <div class="h-16 w-16 bg-gradient-to-br from-teal-400 to-green-600 rounded-lg flex items-center justify-center text-white shadow-lg">
                                    <span class="text-sm font-bold">iSoftware</span>
                                </div>
                                <div class="h-16 w-16 bg-blue-900 rounded-lg flex items-center justify-center text-white shadow-lg">
                                    <span class="text-sm font-bold">AFX</span>
                                </div>
                            </div>
                        </div>

                        <hr class="border-white/30 my-6" />

                        <!-- Copyright e Icono -->
                        <div class="flex justify-between items-center relative">
                            <p class="text-xs text-gray-300">
                                &copy; {{ date('Y') }} Universidad Politécnica de Bacalar. All rights reserved.
                            </p>
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

        </div>
    </body>
</html>