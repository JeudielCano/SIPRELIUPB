<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Préstamos Activos y Aprobados') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if($loans->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            No hay préstamos activos ni aprobados pendientes de retiro en este momento.
                        </div>
                    @else
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Solicitud</th>
                                        <th scope="col" class="px-6 py-3">Solicitante</th>
                                        <th scope="col" class="px-6 py-3">Recurso</th>
                                        <th scope="col" class="px-6 py-3">Fechas Clave</th>
                                        <th scope="col" class="px-6 py-3">Código Retiro</th>
                                        <th scope="col" class="px-6 py-3">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loans as $loan)
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                                #{{ $loan->id }}
                                            </th>
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-gray-900">{{ $loan->user->name }}</div>
                                                <div class="text-xs text-gray-500">{{ ucfirst($loan->user->applicant_type) }}</div>
                                            </td>
                                            <td class="px-6 py-4" title="{{ $loan->items ? $loan->items->map(fn($item) => $item->resource->name . ' (x' . $item->quantity . ')')->implode(', ') : 'Sin recursos' }}">
                                                <div class="font-medium text-gray-900">
                                                    @if($loan->items && $loan->items->count() > 0)
                                                        {{ Str::limit($loan->items->map(fn($item) => $item->resource->name)->implode(', '), 35, '...') }}
                                                    @else
                                                        <span class="text-gray-400 italic">No hay recursos</span>
                                                    @endif
                                                </div>
                                                <div class="text-[10px] text-gray-500 mt-1 uppercase tracking-wider">
                                                    {{ $loan->items ? $loan->items->sum('quantity') : 0 }} artículo(s)
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-xs">
                                                <div class="mb-1">Retiro: <strong class="text-gray-700">{{ $loan->pickup_at->format('d/m H:i') }}</strong></div>
                                                <div>Entrega: <strong class="text-gray-700">{{ $loan->due_at->format('d/m H:i') }}</strong></div>
                                                @if($loan->status === 'activo' && now() > $loan->due_at)
                                                    <span class="mt-1 inline-block text-red-600 font-bold bg-red-50 px-1 rounded">¡Vencido!</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 font-mono font-bold text-lg text-gray-700 tracking-wider">
                                                {{ $loan->pickup_code }}
                                            </td>
                                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                                @if($loan->status === 'aprobado')
                                                    <a href="{{ route('admin.loans.show', $loan) }}" 
                                                    class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-xs px-4 py-2 transition-all shadow-sm active:scale-95 group border border-blue-600">
                                                        <svg class="w-4 h-4 mr-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                                                        </svg>
                                                        Entregar Material
                                                    </a>
                                                @else
                                                    <a href="{{ route('admin.loans.show', $loan) }}" 
                                                    class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs px-4 py-2 transition-all shadow-sm active:scale-95 group border border-emerald-600">
                                                        <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                                                        </svg>
                                                        Devolución
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>