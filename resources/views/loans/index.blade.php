<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Solicitudes de Préstamo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('status'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-green-800 font-medium">{{ session('status') }}</span>
                </div>
            @endif

            <div class="flex flex-col sm:flex-row justify-end gap-3 mb-6">
                <a href="{{ route('loans.external.create') }}" class="text-blue-700 bg-white border-2 border-blue-200 hover:border-blue-300 hover:bg-blue-50 focus:ring-4 focus:ring-blue-100 font-bold rounded-xl text-sm px-6 py-2.5 flex items-center justify-center shadow-sm transition-all active:scale-95">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Solicitud Externa
                </a>

                <a href="{{ route('loans.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-bold rounded-xl text-sm px-6 py-2.5 flex items-center justify-center shadow-md transition-all active:scale-95 border border-transparent">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Nueva Solicitud
                </a>
            </div>

            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 overflow-hidden">
                <div class="p-4 sm:p-6 text-gray-900">
                    
                    @if($loans->isEmpty())
                        <div class="text-center py-12 px-4 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                            <div class="bg-white w-14 h-14 rounded-full flex items-center justify-center mx-auto shadow-sm mb-4">
                                <svg class="h-7 w-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Aún no tienes solicitudes</h3>
                            <p class="mt-1 text-sm text-gray-500 max-w-sm mx-auto">Comienza creando una nueva solicitud para utilizar los laboratorios.</p>
                        </div>
                    @else

                        <div class="rounded-lg border border-gray-200 shadow-sm w-full overflow-hidden">
                            <table class="w-full text-sm text-left text-gray-600 bg-white">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b border-gray-200">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 font-bold w-1/4">Solicitud y Detalles</th>
                                        <th scope="col" class="px-4 py-3 font-bold w-1/4">Recursos</th>
                                        <th scope="col" class="px-4 py-3 font-bold w-1/6">Periodo</th>
                                        <th scope="col" class="px-4 py-3 font-bold text-center w-1/6">Estado y Retiro</th>
                                        <th scope="col" class="px-4 py-3 font-bold text-right w-1/6">Acciones</th>
                                    </tr>
                                </thead>
                                
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($loans as $loan)
                                        <tr class="hover:bg-blue-50/50 transition-colors">
                                            
                                            <td class="px-4 py-4 align-top">
                                                <div class="flex items-center gap-2 mb-1.5">
                                                    <span class="text-base font-bold text-gray-900">#{{ $loan->id }}</span>
                                                    @if(Str::contains($loan->observations, '(SOLICITUD EXTERNA)'))
                                                        <span class="text-[9px] font-bold bg-purple-100 text-purple-800 px-1.5 py-0.5 rounded border border-purple-200 uppercase tracking-wide">
                                                            Externa
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="font-bold text-gray-800 text-xs leading-tight">{{ $loan->activityType->name }}</div>
                                                <div class="text-[11px] text-gray-500 leading-tight mt-0.5">{{ $loan->subject->name }}</div>
                                                <div class="text-[10px] text-gray-400 mt-1.5 flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    Creada: {{ $loan->created_at->format('d/m/Y') }}
                                                </div>
                                            </td>

                                            <td class="px-4 py-4 align-top" title="{{ $loan->items ? $loan->items->map(fn($item) => $item->resource->name . ' (x' . $item->quantity . ')')->implode(', ') : 'Sin recursos' }}">
                                                <div class="text-sm text-gray-800 font-medium break-words line-clamp-2">
                                                    @if($loan->items && $loan->items->count() > 0)
                                                        {{ $loan->items->map(fn($item) => $item->resource->name)->implode(', ') }}
                                                    @else
                                                        <span class="text-gray-400 italic">No hay recursos</span>
                                                    @endif
                                                </div>
                                                <div class="text-[10px] text-gray-500 font-bold mt-1.5 uppercase tracking-wider">
                                                    <span class="bg-gray-100 px-1.5 py-0.5 rounded border border-gray-200">
                                                        {{ $loan->items ? $loan->items->sum('quantity') : 0 }} artículo(s)
                                                    </span>
                                                </div>
                                            </td>

                                            <td class="px-4 py-4 align-top whitespace-nowrap">
                                                <div class="flex flex-col text-xs space-y-1.5">
                                                    <div class="flex items-center text-gray-700">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 mr-1.5"></span>
                                                        <span class="font-bold mr-1">Retiro:</span> 
                                                        {{ $loan->pickup_at->format('d/m H:i') }}
                                                    </div>
                                                    <div class="flex items-center text-gray-700">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-red-400 mr-1.5"></span>
                                                        <span class="font-bold mr-1">Entrega:</span> 
                                                        {{ $loan->due_at->format('d/m H:i') }}
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-4 py-4 text-center align-top">
                                                @php
                                                    $statusClasses = [
                                                        'pendiente'  => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                        'aprobado'   => 'bg-blue-100 text-blue-800 border-blue-200',
                                                        'activo'     => 'bg-green-100 text-green-800 border-green-200',
                                                        'rechazado'  => 'bg-red-100 text-red-800 border-red-200',
                                                        'finalizado' => 'bg-gray-100 text-gray-800 border-gray-200',
                                                    ];
                                                    $currentClass = $statusClasses[$loan->status] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                
                                                <span class="{{ $currentClass }} text-[10px] font-bold px-2.5 py-1 rounded-full border uppercase tracking-wider inline-block mb-2 w-full max-w-[100px] truncate">
                                                    {{ $loan->status }}
                                                </span>

                                                @if ($loan->status === 'aprobado' && $loan->pickup_code)
                                                    <div class="bg-gray-50 border border-dashed border-gray-300 rounded mx-auto w-full max-w-[100px] py-1 shadow-sm">
                                                        <span class="font-mono text-sm font-black text-blue-700 tracking-widest block leading-none">
                                                            {{ $loan->pickup_code }}
                                                        </span>
                                                        <span class="text-[8px] text-gray-400 uppercase font-bold mt-0.5 block">PIN</span>
                                                    </div>
                                                @elseif ($loan->status === 'activo')
                                                    <span class="text-[10px] font-bold text-green-600 flex items-center justify-center bg-green-50 py-1 rounded border border-green-100 mx-auto w-full max-w-[100px]">
                                                        EN USO
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="px-4 py-4 text-right align-top whitespace-nowrap">
                                                @if(Str::contains($loan->observations, '(SOLICITUD EXTERNA)'))
                                                    <a href="{{ route('loans.permit.download', $loan) }}" 
                                                       class="text-purple-700 hover:text-purple-900 bg-purple-50 hover:bg-purple-100 px-2.5 py-1.5 rounded border border-purple-200 text-xs font-bold inline-flex items-center transition-colors">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                        Permiso
                                                    </a>
                                                @else
                                                    <span class="text-gray-300 text-sm font-bold">---</span>
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