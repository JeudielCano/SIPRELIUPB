<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Inventario (Recursos)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Mensaje de Estado (Feedback) -->
            <div class="mb-4">
                <x-auth-session-status :status="session('status')" />
            </div>
            
            <!-- Botón para Crear Nuevo -->
            <div class="flex justify-end mb-4">
                <a href="{{ route('admin.resources.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 shadow-md transition-colors">
                    Dar de alta recursos
                </a>
            </div>
            
            <!-- Tabla de Recursos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
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
                                
                                <!-- CAMBIO 1: Estado condicional -->
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

                                    <!-- CAMBIO 2: Botón 'Dar de baja' (Eliminar) -->
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
                                <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                                    No hay recursos registrados en el inventario.
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