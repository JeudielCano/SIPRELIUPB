@if($items->isEmpty())
    <div class="text-center py-12 bg-white rounded-lg border border-gray-200">
        <p class="text-gray-500">No hay recursos de este tipo registrados en el sistema.</p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($items as $resource)
            @php
                $isAvailable = $resource->available_count > 0;
                $statusColor = $isAvailable ? 'green' : 'red';
                $statusText = $isAvailable ? 'Disponible' : 'No Disponible / Agotado';
            @endphp

            <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200 flex flex-col h-full relative overflow-hidden">
                
                <!-- Banda de Estado (Color lateral) -->
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-{{ $statusColor }}-500"></div>

                <div class="p-6 flex-grow">
                    <div class="flex justify-between items-start mb-2">
                        <h5 class="text-lg font-bold tracking-tight text-gray-900">{{ $resource->name }}</h5>
                        <!-- Badge de Estado -->
                        <span class="bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 text-xs font-medium px-2.5 py-0.5 rounded border border-{{ $statusColor }}-400">
                            {{ $statusText }}
                        </span>
                    </div>

                    <p class="font-normal text-gray-700 mb-4 text-sm">
                        {{ $resource->description ?? 'Sin descripción disponible.' }}
                    </p>

                    <div class="flex items-center text-sm text-gray-500 mb-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        No. Inventario: {{ $resource->inventory_number ?? 'N/A' }}
                    </div>
                </div>

                <!-- Footer de la Tarjeta -->
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 flex justify-between items-center">
                    <div class="text-sm">
                        <span class="font-bold {{ $isAvailable ? 'text-gray-900' : 'text-red-600' }}">{{ $resource->available_count }}</span> 
                        <span class="text-gray-500">unidades libres</span>
                    </div>

                    @if($isAvailable)
                        <!-- Si es laboratorio y es alumno, no mostramos botón solicitar -->
                        @if($resource->type === 'laboratorio' && Auth::user()->applicant_type === 'alumno')
                            <span class="text-xs text-gray-400 italic">Solo docentes</span>
                        @else
                            <a href="{{ route('loans.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center inline-flex items-center">
                                Solicitar
                                <svg class="rtl:rotate-180 w-3 h-3 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/></svg>
                            </a>
                        @endif
                    @else
                        <button disabled class="text-white bg-gray-400 cursor-not-allowed font-medium rounded-lg text-xs px-3 py-1.5 text-center">
                            Agotado
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif