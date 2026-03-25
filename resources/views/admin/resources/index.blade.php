<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Inventario (Recursos)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Mensajes de estado y errores --}}
            <div class="mb-4">
                <x-auth-session-status :status="session('status')" />

                @if($errors->any())
                    <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
            </div>


            {{-- Aqui empieza --}}
            
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6">
                
                <form action="{{ route('admin.resources.index') }}" method="GET" class="flex flex-col gap-4">
                    
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        
                        {{-- 1. Buscador --}}
                        <div class="flex items-center gap-2 w-full md:w-auto">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Nombre o No. Inventario..."
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2 w-full md:w-64">
                            <button type="submit"
                                class="px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Botones de Acción --}}
                        <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                            
                            {{-- 4. Botón Dar de Alta --}}
                            <a href="{{ route('admin.resources.create') }}" class="w-full sm:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2 shadow-md transition-all text-center whitespace-nowrap">
                                + Nuevo Recurso
                            </a>
                            <a href="{{ route('admin.resources.download-inventory') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Descargar Inventario
                            </a>

                            {{-- 5. Botón de Recursos Dados de Baja --}}
                            @if($disabledResources->isNotEmpty())
                                <button type="button" onclick="document.getElementById('modal-dados-baja').classList.remove('hidden')"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-bold rounded-lg hover:bg-gray-200 transition-colors border border-gray-300 whitespace-nowrap shadow-sm">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    DADOS DE BAJA ({{ $disabledResources->count() }})
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        
                        {{-- 2. Filtro Tipo --}}
                        <div class="flex items-center">
                            <label for="type" class="text-sm font-medium text-gray-700 mr-2">Tipo:</label>
                            <select name="type" id="type" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2">
                                <option value="">Todos</option>
                                <option value="equipo" {{ request('type') == 'equipo' ? 'selected' : '' }}>Equipos</option>
                                <option value="laboratorio" {{ request('type') == 'laboratorio' ? 'selected' : '' }}>Laboratorios</option>
                                <option value="insumo" {{ request('type') == 'insumo' ? 'selected' : '' }}>Insumos</option>
                            </select>
                        </div>

                        {{-- 3. Filtro Fecha --}}
                        <div class="flex items-center">
                            <label for="date" class="text-sm font-medium text-gray-700 mr-2">Fecha:</label>
                            <input type="date" name="date" id="date" value="{{ request('date') }}" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2">
                        </div>

                        {{-- Botón Limpiar --}}
                        @if(request('type') || request('date') || request('search'))
                            <a href="{{ route('admin.resources.index') }}" class="text-xs text-red-600 hover:underline">Limpiar filtros</a>
                        @endif
                    </div>
                    
                </form>
            </div>
            
            {{-- Aqui termina --}}


            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 table-auto">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-center w-24">Imagen</th>
                                <th scope="col" class="px-4 py-3 min-w-[150px]">Nombre</th>
                                <th scope="col" class="px-4 py-3">Tipo</th>
                                <th scope="col" class="px-4 py-3">Carrera</th>
                                <th scope="col" class="px-4 py-3">No. Inventario</th>
                                <th scope="col" class="px-4 py-3 text-center">Stock</th>
                                <th scope="col" class="px-4 py-3 text-center">Estado</th>
                                <th scope="col" class="px-4 py-3 text-right w-1 whitespace-nowrap">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($resources as $resource)
                            <tr class="bg-white hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-center">
                                    <div class="flex justify-center">
                                        @if($resource->image_path)
                                            <div class="h-10 w-16 bg-white rounded border border-gray-200 p-0.5 flex items-center justify-center overflow-hidden shadow-sm">
                                                <img src="{{ asset('storage/' . $resource->image_path) }}" 
                                                     class="max-h-full max-w-full object-contain hover:scale-125 transition-transform duration-200 cursor-pointer"
                                                     onclick="window.open(this.src, '_blank')">
                                            </div>
                                        @else
                                            <div class="h-10 w-16 bg-gray-50 rounded border border-gray-200 flex items-center justify-center text-gray-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-4 py-3 font-medium text-gray-900 leading-tight">
                                    {{ $resource->name }}
                                </td>

                                <td class="px-4 py-3 capitalize text-xs">
                                    {{ $resource->type }}
                                </td>
                                
                                <td class="px-4 py-3 text-xs font-medium text-gray-700">
                                    {{ $resource->assignedCareer?->name ?? 'General' }}
                                </td>

                                <td class="px-4 py-3 font-mono text-[11px]">
                                    {{ $resource->inventory_number ?? '---' }}
                                </td>

                                <td class="px-4 py-3 text-center font-bold {{ $resource->total_stock == 0 ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ $resource->total_stock }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    @php
                                        $statusClasses = [
                                            'disponible'   => 'bg-green-100 text-green-800 border-green-200',
                                            'prestado'     => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'mantenimiento'=> 'bg-orange-100 text-orange-800 border-orange-200',
                                        ];
                                        $color = ($resource->total_stock == 0) ? 'bg-red-100 text-red-800 border-red-200' : ($statusClasses[$resource->status] ?? 'bg-gray-100 text-gray-800 border-gray-200');
                                        $label = ($resource->total_stock == 0) ? 'No disponible' : $resource->status;
                                    @endphp
                                    <span class="{{ $color }} text-[10px] uppercase tracking-wider font-bold px-2 py-0.5 rounded-full border">
                                        {{ $label }}
                                    </span>
                                </td>
                                
                                <!-- Botones -->
                                <td class="px-4 py-3 text-right w-1 whitespace-nowrap">
                                    <div class="flex justify-end items-center gap-2">
                                        
                                        {{-- Botón: Editar (Estilo Soft Azul) --}}
                                        <a href="{{ route('admin.resources.edit', $resource->id) }}" 
                                        class="inline-flex items-center justify-center text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 font-bold rounded-lg text-xs px-3 py-1.5 transition-all shadow-sm active:scale-95 group"
                                        title="Editar recurso">
                                            <svg class="w-4 h-4 mr-1.5 text-blue-500 group-hover:text-blue-700 group-hover:-rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            <span class="hidden lg:inline">Editar</span>
                                        </a>

                                        {{-- Botón: Dar de Baja (Estilo Soft Rojo) --}}
                                        <form action="{{ route('admin.resources.disable', $resource->id) }}" method="POST" class="inline m-0 p-0" onsubmit="return confirm('¿Estás seguro de dar de baja este recurso? Podrás recuperarlo después.');">
                                            @csrf @method('PATCH')
                                            <button type="submit" 
                                                class="inline-flex items-center justify-center text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 font-bold rounded-lg text-xs px-3 py-1.5 transition-all shadow-sm active:scale-95 group"
                                                title="Mover a papelera">
                                                <svg class="w-4 h-4 mr-1.5 text-red-500 group-hover:text-red-700 group-hover:translate-y-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                <span class="hidden lg:inline">Dar Baja</span>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                                <!-- Fin Botones -->
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center text-gray-400">
                                    @if(request('search'))
                                        No se encontraron recursos para "{{ request('search') }}".
                                    @else
                                        No hay recursos.
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-lg mt-0">
                        {{ $resources->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- Modal de recursos dados de baja --}}
    <div id="modal-dados-baja" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl mx-4 max-h-[80vh] flex flex-col">
            
            {{-- Header --}}
            <div class="flex justify-between items-center p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">🗑️ Recursos Dados de Baja ({{ $disabledResources->count() }})</h3>
                <button onclick="document.getElementById('modal-dados-baja').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>

            {{-- Contenido scrollable --}}
            <div class="overflow-y-auto flex-1 p-6">
                <p class="text-sm text-gray-500 mb-4">
                    Puedes recuperar un recurso o eliminarlo permanentemente.
                    <strong class="text-red-600">La eliminación borrará también su historial de préstamos.</strong>
                </p>

                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-3">Nombre</th>
                            <th class="px-4 py-3">Tipo</th>
                            <th class="px-4 py-3">No. Inventario</th>
                            <th class="px-4 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($disabledResources as $dr)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $dr->name }}</td>
                                <td class="px-4 py-3 capitalize text-xs">{{ $dr->type }}</td>
                                <td class="px-4 py-3 font-mono text-xs">{{ $dr->inventory_number ?? '---' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-center gap-2">
                                        {{-- Recuperar --}}
                                        <form action="{{ route('admin.resources.recover', $dr->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="px-3 py-1.5 text-xs font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                                                Recuperar
                                            </button>
                                        </form>

                                        {{-- Eliminar permanentemente --}}
                                        <form action="{{ route('admin.resources.destroy', $dr->id) }}" method="POST"
                                            onsubmit="return confirm('⚠️ ADVERTENCIA: Esto eliminará el recurso y TODO su historial de préstamos permanentemente. ¿Estás seguro?');">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                                                Eliminar permanentemente
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="p-4 border-t bg-gray-50 flex justify-between items-center rounded-b-lg">
                @if($disabledResources->isNotEmpty())
                    <a href="{{ route('admin.resources.download-bajas') }}" 
                       class="inline-flex items-center justify-center text-red-700 bg-red-100 hover:bg-red-200 border border-red-300 font-bold rounded-lg text-xs px-4 py-2 transition-all shadow-sm active:scale-95 group">
                        <svg class="w-4 h-4 mr-2 text-red-600 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Descargar Reporte (PDF)
                    </a>
                @else
                    <div></div> @endif

                <button onclick="document.getElementById('modal-dados-baja').classList.add('hidden')"
                    class="px-4 py-2 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors shadow-sm">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

</x-app-layout>