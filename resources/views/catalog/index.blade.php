<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Recursos Disponibles') }}
        </h2>
    </x-slot>

    <!-- Estado Global de Alpine para manejar la navegación entre vistas -->
    <div class="py-12" x-data="{ 
            currentView: new URLSearchParams(window.location.search).has('categoria') ? 'list' : 'selection',
            activeCategory: new URLSearchParams(window.location.search).get('categoria') || '',
            searchQuery: '', 
            
            titles: {
                'equipo': 'Equipos de Cómputo',
                'laboratorio': 'Laboratorios y Espacios',
                'insumo': 'Insumos y Consumibles'
            },

            selectCategory(category) {
                this.activeCategory = category;
                this.currentView = 'list';
                this.searchQuery = ''; // Limpiamos el buscador al cambiar de categoría
                // Actualizamos la URL sin recargar la página para la paginación
                window.history.pushState(null, '', '?categoria=' + category);
            },

            goBack() {
                this.currentView = 'selection';
                this.activeCategory = '';
                this.searchQuery = '';
                window.history.pushState(null, '', window.location.pathname);
            }
            }" x-cloak>
            
        
 
       <!-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">--> 

        <!-- VISTA 1: SELECCIÓN DE CATEGORÍA (TARJETAS) -->
        <div x-show="currentView === 'selection'" class="animate-fade-in">
            
            <!-- Contenedor Azul Claro (Simulando tu mockup) -->
            <div class="bg-blue-50 p-8 rounded-xl border border-blue-100 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    
                    <!-- TARJETA: EQUIPOS -->
                    <div class="bg-gray-200 rounded-lg p-6 flex flex-col items-center justify-between h-80 shadow-sm hover:shadow-md transition-shadow">
                        <!-- ETIQUETA DE LA CARD-->
                        <div class="bg-gray-400 text-white font-bold py-2 px-8 rounded-full mb-8 w-3/4 text-center uppercase text-sm tracking-wider">
                            Equipos
                        </div>
                        <!-- IMAGEN -->
                        <div class="flex-grow flex items-center justify-center w-full overflow-hidden">
                            <img src="{{ asset('images/equipos.png') }}" 
                                alt="Icono Equipos" 
                                class="w-32 h-32 object-contain drop-shadow-sm">
                        </div>
                        <!-- BOTÓN -->
                        <button @click="selectCategory('equipo')" class="mt-6 bg-black text-white font-bold py-2 px-6 rounded-lg hover:bg-gray-800 transition-colors w-full">
                            Ver recursos
                        </button>
                    </div>

                    <!-- TARJETA: LABORATORIOS -->
                    <div class="bg-gray-200 rounded-lg p-6 flex flex-col items-center justify-between h-80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="bg-gray-400 text-white font-bold py-2 px-8 rounded-full mb-8 w-3/4 text-center uppercase text-sm tracking-wider">
                            Laboratorios
                        </div>
                        
                        <!-- IMAGEN -->
                        <div class="flex-grow flex items-center justify-center w-full overflow-hidden">
                            <img src="{{ asset('images/laboratorios.png') }}" 
                                alt="Icono Equipos" 
                                class="w-32 h-32 object-contain drop-shadow-sm">
                        </div>

                        <!-- Lógica: Si es alumno, botón deshabilitado o aviso -->
                        @if(Auth::user()->applicant_type === 'alumno')
                            <button disabled class="mt-6 bg-gray-500 text-gray-300 font-bold py-2 px-6 rounded-lg cursor-not-allowed w-full flex flex-col items-center justify-center leading-tight">
                                <span>No disponible</span>
                                <span class="text-[10px] font-normal lowercase">(solo docentes)</span>
                            </button>
                        @else
                            <button @click="selectCategory('laboratorio')" class="mt-6 bg-black text-white font-bold py-2 px-6 rounded-lg hover:bg-gray-800 transition-colors w-full">
                                Ver recursos
                            </button>
                        @endif
                    </div>

                    <!-- TARJETA: INSUMOS -->
                    <div class="bg-gray-200 rounded-lg p-6 flex flex-col items-center justify-between h-80 shadow-sm hover:shadow-md transition-shadow">
                        <div class="bg-gray-400 text-white font-bold py-2 px-8 rounded-full mb-8 w-3/4 text-center uppercase text-sm tracking-wider">
                            Insumos
                        </div>
                        
                        <!-- IMAGEN -->
                        <div class="flex-grow flex items-center justify-center w-full overflow-hidden">
                            <img src="{{ asset('images/insumos.png') }}" 
                                alt="Icono Equipos" 
                                class="w-32 h-32 object-contain drop-shadow-sm">
                        </div>
                        <!-- BOTÓN -->
                        <button @click="selectCategory('insumo')" class="mt-6 bg-black text-white font-bold py-2 px-6 rounded-lg hover:bg-gray-800 transition-colors w-full">
                            Ver recursos
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- VISTA 2: TABLA DE DETALLES -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div x-show="currentView === 'selection'" class="animate-fade-in">
            </div>

            <div x-show="currentView === 'list'" class="animate-fade-in" style="display: none;">
                
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900" x-text="titles[activeCategory]"></h2>
                    <button @click="goBack()" class="flex items-center text-gray-600 hover:text-blue-600 font-medium transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Volver a Categorías
                    </button>
                </div>

                <form method="GET" action="{{ route('catalog.index') }}" class="mb-8 relative w-full flex items-stretch shadow-sm">
                    <input type="hidden" name="categoria" x-bind:value="activeCategory">
                    
                    <div class="relative flex-grow">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="bg-white border border-gray-300 text-gray-900 text-base rounded-l-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full pl-12 p-3.5 transition-all outline-none" 
                            placeholder="Escribe el nombre del equipo o descripción que buscas...">
                    </div>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm px-8 rounded-r-xl border border-blue-600 transition-all active:scale-95">
                        BUSCAR
                    </button>
                    
                    @if(request('search'))
                        <a href="{{ route('catalog.index', ['categoria' => request('categoria')]) }}" 
                        class="ml-4 text-gray-500 hover:text-red-600 flex items-center text-sm font-medium">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                            Limpiar filtros
                        </a>
                    @endif
                </form>

                <div class="bg-blue-100/50 p-6 rounded-xl">
                    <div class="bg-blue-200/80 py-3 px-4 rounded-t-lg text-center border-b border-blue-300 mb-0">
                        <h3 class="text-xl font-bold text-gray-800 capitalize" x-text="activeCategory + 's'"></h3>
                    </div>

                    <div x-show="activeCategory === 'equipo'" class="overflow-hidden rounded-b-lg shadow-sm">
                        @include('catalog.partials.table_view', ['items' => $groupedResources['equipo'] ?? collect()])
                        
                        <div class="mt-4 p-4 bg-white border-t">
                            {{ ($groupedResources['equipo'] ?? collect())->appends(['categoria' => 'equipo'])->links() }}
                        </div>
                    </div>

                    <div x-show="activeCategory === 'laboratorio'" class="overflow-hidden rounded-b-lg shadow-sm">
                        @include('catalog.partials.table_view', ['items' => $groupedResources['laboratorio'] ?? collect()])
                        <div class="mt-4 p-4 bg-white border-t">
                            {{ ($groupedResources['laboratorio'] ?? collect())->appends(['categoria' => 'laboratorio'])->links() }}
                        </div>
                    </div>

                    <div x-show="activeCategory === 'insumo'" class="overflow-hidden rounded-b-lg shadow-sm">
                        @include('catalog.partials.table_view', ['items' => $groupedResources['insumo'] ?? collect()])
                        <div class="mt-4 p-4 bg-white border-t">
                            {{ ($groupedResources['insumo'] ?? collect())->appends(['categoria' => 'insumo'])->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</x-app-layout>




<!-- 


-->

