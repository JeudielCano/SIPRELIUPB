<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Inventario (Recursos)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Mensaje de Estado -->
            <div class="mb-4">
                <x-auth-session-status :status="session('status')" />
            </div>
            
            <!-- BARRA DE HERRAMIENTAS: Filtros y Acciones -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
                
                <!-- Filtros -->
                <form action="{{ route('admin.resources.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">
                    
                    <!-- Filtro Tipo -->
                    <div class="flex items-center">
                        <label for="type" class="text-sm font-medium text-gray-700 mr-2">Tipo:</label>
                        <select name="type" id="type" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                            <option value="">Todos</option>
                            <option value="equipo" {{ request('type') == 'equipo' ? 'selected' : '' }}>Equipos</option>
                            <option value="laboratorio" {{ request('type') == 'laboratorio' ? 'selected' : '' }}>Laboratorios</option>
                            <option value="insumo" {{ request('type') == 'insumo' ? 'selected' : '' }}>Insumos</option>
                        </select>
                    </div>

                    <!-- Filtro Fecha Alta -->
                    <div class="flex items-center">
                        <label for="date" class="text-sm font-medium text-gray-700 mr-2">Fecha Alta:</label>
                        <input type="date" name="date" id="date" value="{{ request('date') }}" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                    </div>

                    <!-- Botón Limpiar Filtros -->
                    @if(request('type') || request('date'))
                        <a href="{{ route('admin.resources.index') }}" class="text-sm text-red-600 hover:underline">Limpiar filtros</a>
                    @endif
                </form>

                <!-- Botón Dar de Alta -->
                <a href="{{ route('admin.resources.create') }}" class="w-full md:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 shadow-md transition-colors text-center">
                    Dar de Alta Recurso
                </a>
            </div>
            
            <!-- Tabla de Recursos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <!-- NUEVA COLUMNA: IMAGEN -->
                                <th scope="col" class="px-6 py-3 text-center">Imagen</th>
                                <th scope="col" class="px-6 py-3">Nombre</th>
                                <th scope="col" class="px-6 py-3">Tipo</th>
                                <th scope="col" class="px-6 py-3">Carrera</th>
                                <th scope="col" class="px-6 py-3">No. Inventario</th>
                                <th scope="col" class="px-6 py-3 text-center">Stock</th>
                                <th scope="col" class="px-6 py-3 text-center">Estado</th>
                                <th scope="col" class="px-6 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($resources as $resource)
                            <tr class="bg-white border-b hover:bg-gray-50 transition-colors">
                                
                                <!-- MOSTRAR IMAGEN O PLACEHOLDER (AJUSTADO A HORIZONTAL) -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center items-center">
                                        @if($resource->image_path)
                                            <!-- Contenedor blanco para resaltar logos o equipos -->
                                            <div class="h-12 w-20 bg-white rounded border border-gray-200 p-0.5 flex items-center justify-center overflow-hidden shadow-sm">
                                                <!-- object-contain: Muestra toda la imagen sin recortar -->
                                                <img src="{{ asset('storage/' . $resource->image_path) }}" 
                                                     alt="{{ $resource->name }}" 
                                                     class="max-h-full max-w-full object-contain cursor-pointer hover:scale-150 hover:z-10 hover:shadow-lg transition-all duration-200"
                                                     onclick="window.open(this.src, '_blank')"
                                                     title="Clic para ampliar">
                                            </div>
                                        @else
                                            <!-- Icono por defecto si no hay imagen -->
                                            <div class="h-12 w-20 bg-gray-50 rounded border border-gray-200 flex items-center justify-center text-gray-300">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $resource->name }}
                                </th>
                                <td class="px-6 py-4 capitalize">
                                    {{ $resource->type }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $resource->career ?? 'General' }}
                                </td>
                                <td class="px-6 py-4 font-mono text-xs">
                                    {{ $resource->inventory_number ?? '---' }}
                                </td>
                                <td class="px-6 py-4 text-center font-bold {{ $resource->total_stock == 0 ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ $resource->total_stock }}
                                </td>
                                
                                <!-- Estado condicional (Visual) -->
                                <td class="px-6 py-4 text-center">
                                    @if ($resource->total_stock == 0)
                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full border border-red-200">
                                            No disponible
                                        </span>
                                    @else
                                        @php
                                            $statusColors = [
                                                'disponible' => 'bg-green-100 text-green-800 border-green-200',
                                                'prestado' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                'mantenimiento' => 'bg-orange-100 text-orange-800 border-orange-200',
                                            ];
                                            $colorClass = $statusColors[$resource->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                        @endphp
                                        <span class="{{ $colorClass }} text-xs font-medium px-2.5 py-0.5 rounded-full border capitalize">
                                            {{ $resource->status }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 flex items-center justify-end space-x-3">
                                    <!-- Botón Editar -->
                                    <a href="{{ route('admin.resources.edit', $resource->id) }}" class="font-medium text-blue-600 hover:underline">
                                        Editar
                                    </a>

                                    <!-- Botón 'Dar de baja' -->
                                    <form action="{{ route('admin.resources.destroy', $resource->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres dar de baja este recurso? Esta acción es irreversible.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-medium text-red-600 hover:underline">
                                            Dar de baja
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr class="bg-white border-b">
                                <td colspan="8" class="px-6 py-8 text-center text-gray-400">
                                    No hay recursos registrados que coincidan con los filtros.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>