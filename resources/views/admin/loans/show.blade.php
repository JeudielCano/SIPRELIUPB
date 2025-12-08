<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalle de Solicitud #') . $loan->id }}
            </h2>

            @php
                $statusClasses = [
                    'pendiente' => 'bg-yellow-100 text-yellow-800',
                    'aprobado'  => 'bg-blue-100 text-blue-800',
                    'activo'    => 'bg-green-100 text-green-800',
                    'rechazado' => 'bg-red-100 text-red-800',
                    'finalizado'=> 'bg-gray-100 text-gray-800',
                ];
                $currentClass = $statusClasses[$loan->status] ?? 'bg-gray-100 text-gray-800';
            @endphp

            <span class="px-3 py-1 rounded-full text-sm font-bold {{ $currentClass }}">
                {{ ucfirst($loan->status) }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Mensajes -->
            <div class="mb-4">
                <x-auth-session-status :status="session('status')" />
                <!-- Mostrar errores de validación si los hay -->
                @if ($errors->any())
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                        <span class="font-medium">¡Atención!</span>
                        <ul class="mt-1.5 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- 1. Información del Solicitante y Evento -->
            <div class="bg-white p-6 rounded-lg shadow border border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Datos del Solicitante</h3>
                    <p class="text-sm text-gray-600">Nombre: <span class="font-semibold text-gray-900">{{ $loan->user->name }}</span></p>
                    <p class="text-sm text-gray-600">Email: <span class="font-semibold text-gray-900">{{ $loan->user->email }}</span></p>
                    <p class="text-sm text-gray-600">Tipo: <span class="font-semibold text-gray-900">{{ ucfirst($loan->user->applicant_type) }}</span></p>
                    @if($loan->user->student_id)
                        <p class="text-sm text-gray-600">Matrícula: <span class="font-semibold text-gray-900">{{ $loan->user->student_id }}</span></p>
                    @endif
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Datos del Préstamo</h3>
                    <p class="text-sm text-gray-600">Actividad: <span class="font-semibold text-gray-900">{{ $loan->activityType->name }}</span></p>
                    <p class="text-sm text-gray-600">Asignatura: <span class="font-semibold text-gray-900">{{ $loan->subject->name }}</span></p>
                    <div class="mt-2 p-2 bg-gray-50 rounded">
                        <p class="text-sm">📅 Retiro: <strong>{{ $loan->pickup_at->format('d/m/Y h:i A') }}</strong></p>
                        <p class="text-sm">📅 Devolución: <strong>{{ $loan->due_at->format('d/m/Y h:i A') }}</strong></p>
                    </div>
                </div>
            </div>

            <!-- 2. Lista de Recursos Solicitados -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recursos Solicitados</h3>
                    <div class="relative overflow-x-auto border rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Recurso</th>
                                    <th class="px-6 py-3">Tipo</th>
                                    <th class="px-6 py-3">Cantidad Solicitada</th>
                                    <th class="px-6 py-3">No. Inventario</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loan->items as $item)
                                    <tr class="bg-white border-b">
                                        <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $item->resource->name }}
                                        </th>
                                        <td class="px-6 py-4">{{ ucfirst($item->resource->type) }}</td>
                                        <td class="px-6 py-4 font-bold">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4">{{ $item->resource->inventory_number ?? '---' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($loan->observations)
                        <div class="mt-4 p-4 bg-yellow-50 text-yellow-800 rounded-lg text-sm">
                            <strong>Observaciones del solicitante:</strong> {{ $loan->observations }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- 3. Botones de Acción (Solo si está PENDIENTE) -->
            @if($loan->status === 'pendiente')
                <div class="flex items-center justify-end space-x-4 bg-white p-6 rounded-lg shadow border border-gray-200">
                    <span class="text-sm text-gray-500 mr-2">¿Qué deseas hacer con esta solicitud?</span>
                    
                    <!-- Formulario Rechazar -->
                    <form action="{{ route('admin.loans.reject', $loan) }}" method="POST" onsubmit="return confirm('¿Rechazar esta solicitud?');">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200">
                            Rechazar
                        </button>
                    </form>

                    <!-- Formulario Aprobar -->
                    <form action="{{ route('admin.loans.approve', $loan) }}" method="POST" onsubmit="return confirm('¿Aprobar esta solicitud?');">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200">
                            ✓ Aprobar Solicitud
                        </button>
                    </form>
                </div>
            @endif

            <!-- 4. Botón de ENTREGA (Solo si está APROBADO) -->
            @if($loan->status === 'aprobado')
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6 flex flex-col md:flex-row items-center justify-between shadow-sm">
                    <div>
                        <h4 class="text-lg font-bold text-blue-900 flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Solicitud Aprobada - Lista para Entrega
                        </h4>
                        <p class="text-sm text-blue-700 mt-2">
                            Pide al solicitante su código de retiro y verifícalo aquí:
                        </p>
                        <div class="mt-3 inline-block bg-white px-4 py-2 rounded-lg border-2 border-blue-300 text-2xl font-mono font-black text-gray-800 tracking-widest">
                            {{ $loan->pickup_code }}
                        </div>
                    </div>

                    <form action="{{ route('admin.loans.deliver', $loan) }}" method="POST" onsubmit="return confirm('¿Confirmas que has entregado los materiales al solicitante?');">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="mt-4 md:mt-0 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 focus:outline-none shadow-md transition-all hover:scale-105">
                            Confirmar Entrega y Activar Préstamo
                        </button>
                    </form>
                </div>
            @endif

            <!-- 5. Botón DEVOLUCIÓN (Solo si está ACTIVO) -->
            @if($loan->status === 'activo')
                <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-6 flex flex-col md:flex-row items-center justify-between shadow-sm">
                    <div>
                        <h4 class="text-lg font-bold text-green-900 flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Préstamo en Curso (Activo)
                        </h4>
                        <p class="text-sm text-green-700 mt-1">
                            El material está en posesión del solicitante. <br>
                            Cuando regrese los equipos, registra la devolución aquí.
                        </p>
                    </div>

                    <a href="{{ route('admin.loans.return', $loan) }}" class="mt-4 md:mt-0 text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-3 focus:outline-none shadow hover:scale-105 transition-transform">
                        Registrar Devolución
                    </a>
                </div>
            @endif

            <!-- 6. Información de CIERRE (Solo si está FINALIZADO) -->
            @if($loan->status === 'finalizado')
                <div class="mt-6 bg-gray-100 border border-gray-200 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-bold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            📋 Reporte de Devolución
                        </h4>
                        <span class="text-sm text-gray-500 bg-white px-3 py-1 rounded border">
                            Devuelto el: {{ $loan->return_at ? $loan->return_at->format('d/m/Y H:i A') : 'N/A' }}
                        </span>
                    </div>
                    
                    <div class="relative overflow-x-auto border rounded-lg bg-white">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                                <tr>
                                    <th class="px-4 py-3">Recurso</th>
                                    <th class="px-4 py-3">Estado Final</th>
                                    <th class="px-4 py-3">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loan->items as $item)
                                    <tr class="bg-white border-b">
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $item->resource->name }}</td>
                                        <td class="px-4 py-3">
                                            @if($item->return_status == 'bueno')
                                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-400">Buen Estado</span>
                                            @elseif($item->return_status == 'dañado')
                                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-400">Dañado</span>
                                            @elseif($item->return_status == 'perdido')
                                                <span class="bg-gray-800 text-white text-xs font-medium px-2.5 py-0.5 rounded">Perdido</span>
                                            @else
                                                <span class="text-gray-800 font-bold capitalize">{{ $item->return_status }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 italic">{{ $item->return_observations ?? '---' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>