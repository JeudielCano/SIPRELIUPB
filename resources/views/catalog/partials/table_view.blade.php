<div class="overflow-x-auto">
    <table class="w-full text-sm text-center text-gray-600 bg-white">
        <thead class="text-xs text-gray-700 uppercase bg-gray-400 text-white">
            <tr>
                <th scope="col" class="px-6 py-3">Tipo</th> <!-- Usaremos el nombre aquí -->
                <th scope="col" class="px-6 py-3">No. Inventario</th>
                <th scope="col" class="px-6 py-3">Descripción</th> <!-- Reemplaza Modelo/Marca por ahora -->
                <th scope="col" class="px-6 py-3">Estado</th>
                <th scope="col" class="px-6 py-3">Cantidad</th>
                <!-- <th scope="col" class="px-6 py-3">Carrera</th> (No tenemos este dato en BD aun) -->
            </tr>
        </thead>
        <tbody>
            @forelse($items as $resource)
                @php
                    $isAvailable = $resource->available_count > 0;
                @endphp
                <tr class="bg-white border-b hover:bg-gray-50 transition-colors h-16">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ $resource->name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $resource->inventory_number ?? '---' }}
                    </td>
                    <td class="px-6 py-4 text-left">
                        {{ Str::limit($resource->description, 30) ?? '---' }}
                    </td>
                    <td class="px-6 py-4">
                        @if($isAvailable)
                            <span class="text-green-600 font-bold">Activo</span>
                        @else
                            <span class="text-gray-500 font-bold">No disponible</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-bold text-lg">
                        {{ $resource->available_count }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                        No hay recursos de este tipo registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>