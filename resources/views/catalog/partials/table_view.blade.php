<div class="overflow-x-auto rounded-lg shadow-sm border border-gray-200">
    <table class="w-full text-sm text-center text-gray-600 bg-white">
        <thead class="text-xs text-gray-700 uppercase bg-gray-400 text-white">
            <tr>
                <th scope="col" class="px-6 py-3 text-left">Recurso / Tipo</th> 
                <th scope="col" class="px-6 py-3">No. Inventario</th> 
                <th scope="col" class="px-6 py-3 text-left">Descripción</th> 
                <th scope="col" class="px-6 py-3">Estado</th> 
                <th scope="col" class="px-6 py-3">Cantidad</th> 
                <th scope="col" class="px-6 py-3">Carrera</th> 
            </tr>
        </thead>
        <tbody>
            @forelse($items as $resource)
                @php
                    // Ahora validamos el estado basándonos en tu nueva columna total_stock
                    $isAvailable = $resource->total_stock > 0;
                @endphp
                <tr class="bg-white border-b hover:bg-gray-50 transition-colors h-16">
                    
                    <td class="px-6 py-4 whitespace-nowrap text-left">
                        <div class="font-bold text-gray-900">{{ $resource->name }}</div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide">{{ $resource->type }}</div>
                    </td>
                    
                    <td class="px-6 py-4 font-mono text-sm">
                        {{ $resource->inventory_number ?? 'S/N' }}
                    </td>
                    
                    <td class="px-6 py-4 text-left text-gray-500">
                        {{ Str::limit($resource->description, 35, '...') ?: 'Sin descripción' }}
                    </td>
                    
                    <td class="px-6 py-4">
                        @if($isAvailable)
                            <span class="bg-green-100 text-green-700 px-3 py-1.5 rounded-full text-xs font-bold">Disponible</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-3 py-1.5 rounded-full text-xs font-bold">Agotado</span>
                        @endif
                    </td>
                    
                    <td class="px-6 py-4 font-bold text-lg text-gray-800">
                        {{ $resource->total_stock }}
                    </td>
                    
                    <td class="px-6 py-4 font-medium text-gray-700">
                        {{-- OJO: Si 'career' es una relación en tu base de datos (clave foránea), deberás cambiar esto por $resource->career->name --}}
                        {{ $resource->career ?? 'General' }}
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        <p class="text-base font-medium">No hay recursos de este tipo registrados.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>