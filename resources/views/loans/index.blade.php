<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Solicitudes de Préstamo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Mensaje de Éxito -->
            <div class="mb-4">
                <x-auth-session-status :status="session('status')" />
            </div>

            <!-- Botones de Acción -->
            <div class="flex flex-col sm:flex-row justify-end gap-4 mb-6">
                <!-- Botón Préstamo Externo -->
                <a href="{{ route('loans.external.create') }}" class="text-blue-700 bg-white border border-blue-300 hover:bg-blue-50 focus:ring-4 focus:ring-blue-100 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Solicitud Externa
                </a>

                <!-- Botón Nueva Solicitud Normal -->
                <a href="{{ route('loans.create') }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center justify-center shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Nueva Solicitud
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    
                    @if($loans->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No tienes solicitudes</h3>
                            <p class="mt-1 text-sm text-gray-500">Comienza creando una nueva solicitud de préstamo.</p>
                        </div>
                    @else
                        <div class="relative overflow-x-auto rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Folio</th>
                                        <th scope="col" class="px-6 py-3">Detalles</th>
                                        <th scope="col" class="px-6 py-3">Periodo</th>
                                        <th scope="col" class="px-6 py-3 text-center">Estado</th>
                                        <th scope="col" class="px-6 py-3 text-center">Código de Retiro</th>
                                        <th scope="col" class="px-6 py-3 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($loans as $loan)
                                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                                            
                                            <!-- 1. Folio -->
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                                #{{ $loan->id }}
                                                <!-- Etiqueta si es externo -->
                                                @if(Str::contains($loan->observations, '(SOLICITUD EXTERNA)'))
                                                    <span class="block mt-1 text-[10px] bg-purple-100 text-purple-800 px-1.5 py-0.5 rounded border border-purple-200 w-fit">
                                                        Externa
                                                    </span>
                                                @endif
                                            </th>

                                            <!-- 2. Detalles (Actividad y Asignatura) -->
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-gray-800">{{ $loan->activityType->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $loan->subject->name }}</div>
                                                <div class="text-[10px] text-gray-400 mt-1">Solicitado: {{ $loan->created_at->format('d/m/Y') }}</div>
                                            </td>

                                            <!-- 3. Periodo (Retiro y Entrega) -->
                                            <td class="px-6 py-4">
                                                <div class="flex flex-col text-xs">
                                                    <span class="mb-1">
                                                        <span class="font-bold text-gray-700">Retiro:</span> 
                                                        {{ $loan->pickup_at->format('d/m H:i') }}
                                                    </span>
                                                    <span>
                                                        <span class="font-bold text-gray-700">Entrega:</span> 
                                                        {{ $loan->due_at->format('d/m H:i') }}
                                                    </span>
                                                </div>
                                            </td>

                                            <!-- 4. Estado -->
                                            <td class="px-6 py-4 text-center">
                                                @php
                                                    $statusClasses = [
                                                        'pendiente' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                        'aprobado'  => 'bg-blue-100 text-blue-800 border-blue-200',
                                                        'activo'    => 'bg-green-100 text-green-800 border-green-200',
                                                        'rechazado' => 'bg-red-100 text-red-800 border-red-200',
                                                        'finalizado'=> 'bg-gray-100 text-gray-800 border-gray-200',
                                                    ];
                                                    $currentClass = $statusClasses[$loan->status] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span class="{{ $currentClass }} text-xs font-medium px-2.5 py-0.5 rounded-full border capitalize block w-fit mx-auto">
                                                    {{ $loan->status }}
                                                </span>
                                            </td>

                                            <!-- 5. Código de Retiro (Solo visible si Aprobado) -->
                                            <td class="px-6 py-4 text-center">
                                                @if ($loan->status === 'aprobado' && $loan->pickup_code)
                                                    <div class="inline-block p-2 bg-gray-50 border-2 border-dashed border-blue-300 rounded-lg">
                                                        <span class="font-mono text-lg font-black text-gray-800 tracking-widest block leading-none">
                                                            {{ $loan->pickup_code }}
                                                        </span>
                                                        <span class="text-[10px] text-gray-500 uppercase font-bold mt-1 block">
                                                            Tu Código
                                                        </span>
                                                    </div>
                                                @elseif ($loan->status === 'activo')
                                                    <span class="text-xs font-bold text-green-600 flex items-center justify-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                        Entregado
                                                    </span>
                                                @else
                                                    <span class="text-gray-300 text-xl">• • •</span>
                                                @endif
                                            </td>

                                            <!-- 6. Acciones (Descarga de Permiso si es externo) -->
                                            <td class="px-6 py-4 text-right">
                                                @if(Str::contains($loan->observations, '(SOLICITUD EXTERNA)'))
                                                    <a href="{{ route('loans.external.permit') }}" target="_blank" class="text-purple-600 hover:text-purple-900 text-xs font-medium flex items-center justify-end group" title="Descargar formato para firmar">
                                                        <svg class="w-4 h-4 mr-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                        Permiso PDF
                                                    </a>
                                                @endif
                                                
                                                <!-- Futuro: Botón Ver Detalle -->
                                                <!-- <a href="#" class="block mt-2 text-gray-400 text-xs hover:text-gray-600">Ver items</a> -->
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