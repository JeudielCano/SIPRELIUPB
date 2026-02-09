<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Recursos Disponibles') }}
        </h2>
    </x-slot>

    <!-- Estado Global de Alpine para manejar la navegación entre vistas -->
    <div class="py-12" x-data="{ 
            currentView: 'selection', // 'selection' o 'list'
            activeCategory: '',       // 'equipo', 'laboratorio', 'insumo'
            
            titles: {
                'equipo': 'Equipos de Cómputo',
                'laboratorio': 'Laboratorios y Espacios',
                'insumo': 'Insumos y Consumibles'
            },

            selectCategory(category) {
                this.activeCategory = category;
                this.currentView = 'list';
            },

            goBack() {
                this.currentView = 'selection';
                this.activeCategory = '';
            }
         }" x-cloak>
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- VISTA 1: SELECCIÓN DE CATEGORÍA (TARJETAS) -->
            <div x-show="currentView === 'selection'" class="animate-fade-in">
                
                <!-- Contenedor Azul Claro (Simulando tu mockup) -->
                <div class="bg-blue-50 p-8 rounded-xl border border-blue-100 shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        
                        <!-- TARJETA: EQUIPOS -->
                        <div class="bg-gray-200 rounded-lg p-6 flex flex-col items-center justify-between h-80 shadow-sm hover:shadow-md transition-shadow">
                            <!-- Etiqueta Superior -->
                            <div class="bg-gray-400 text-white font-bold py-2 px-8 rounded-full mb-8 w-3/4 text-center uppercase text-sm tracking-wider">
                                Equipos
                            </div>
                            
                            <!-- Icono / Placeholder Imagen -->
                            <div class="flex-grow flex items-center justify-center w-full">
                                <div class="w-32 h-32 border-4 border-white text-white flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                            </div>

                            <!-- Botón -->
                            <button @click="selectCategory('equipo')" class="mt-6 bg-black text-white font-bold py-2 px-6 rounded-lg hover:bg-gray-800 transition-colors w-full">
                                Ver recursos
                            </button>
                        </div>

                        <!-- TARJETA: LABORATORIOS -->
                        <div class="bg-gray-200 rounded-lg p-6 flex flex-col items-center justify-between h-80 shadow-sm hover:shadow-md transition-shadow">
                            <div class="bg-gray-400 text-white font-bold py-2 px-8 rounded-full mb-8 w-3/4 text-center uppercase text-sm tracking-wider">
                                Laboratorios
                            </div>
                            
                            <div class="flex-grow flex items-center justify-center w-full">
                                <div class="w-32 h-32 border-4 border-white text-white flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                </div>
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
                            
                            <div class="flex-grow flex items-center justify-center w-full">
                                <div class="w-32 h-32 border-4 border-white text-white flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </div>
                            </div>

                            <button @click="selectCategory('insumo')" class="mt-6 bg-black text-white font-bold py-2 px-6 rounded-lg hover:bg-gray-800 transition-colors w-full">
                                Ver recursos
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <!-- VISTA 2: TABLA DE DETALLES -->
            <div x-show="currentView === 'list'" class="animate-fade-in" style="display: none;">
                
                <!-- Header de la Tabla -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900" x-text="titles[activeCategory]"></h2>
                    <button @click="goBack()" class="flex items-center text-gray-600 hover:text-blue-600 font-medium transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Volver a Categorías
                    </button>
                </div>

                <!-- Fondo Azul Claro (como en el mockup) -->
                <div class="bg-blue-100/50 p-6 rounded-xl">
                    
                    <!-- Título de la Tabla (Centrado como en mockup) -->
                    <div class="bg-blue-200/80 py-3 px-4 rounded-t-lg text-center border-b border-blue-300 mb-0">
                        <h3 class="text-xl font-bold text-gray-800 capitalize" x-text="activeCategory + 's'"></h3>
                    </div>

                    <!-- TABLAS (Renderizamos las 3 tablas ocultas y mostramos solo la activa) -->
                    
                    <!-- TABLA EQUIPOS -->
                    <div x-show="activeCategory === 'equipo'" class="overflow-hidden rounded-b-lg shadow-sm">
                        @include('catalog.partials.table_view', ['items' => $groupedResources['equipo'] ?? collect()])
                    </div>

                    <!-- TABLA LABORATORIOS -->
                    <div x-show="activeCategory === 'laboratorio'" class="overflow-hidden rounded-b-lg shadow-sm">
                        @include('catalog.partials.table_view', ['items' => $groupedResources['laboratorio'] ?? collect()])
                    </div>

                    <!-- TABLA INSUMOS -->
                    <div x-show="activeCategory === 'insumo'" class="overflow-hidden rounded-b-lg shadow-sm">
                        @include('catalog.partials.table_view', ['items' => $groupedResources['insumo'] ?? collect()])
                    </div>

                </div>
                
                <!-- Paginación Ficticia (Visual) -->
                <div class="mt-4 flex justify-center text-gray-600 text-sm">
                    <span>« 1 2 ... »</span>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>




<!-- 


-->

