<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Solicitudes de Préstamo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if($loans->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            No hay solicitudes registradas en el sistema.
                        </div>
                    @else
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Folio</th>
                                        <th scope="col" class="px-6 py-3">Solicitante</th> <!-- COLUMNA NUEVA -->
                                        <th scope="col" class="px-6 py-3">Actividad</th>
                                        <th scope="col" class="px-6 py-3">Fecha Retiro</th>
                                        <th scope="col" class="px-6 py-3">Estado</th>
                                        <th scope="col" class="px-6 py-3">Acciones</th>
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
                                            <td class="px-6 py-4">
                                                {{ $loan->activityType->name }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $loan->pickup_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4">
                                                @php
                                                    // Definimos las clases COMPLETAS para que Tailwind las detecte
                                                    $statusClasses = [
                                                        'pendiente' => 'bg-yellow-100 text-yellow-800',
                                                        'aprobado'  => 'bg-blue-100 text-blue-800',
                                                        'activo'    => 'bg-green-100 text-green-800',
                                                        'rechazado' => 'bg-red-100 text-red-800',
                                                        'finalizado'=> 'bg-gray-100 text-gray-800',
                                                    ];
                                                    // Seleccionamos la clase correcta o gris por defecto
                                                    $currentClass = $statusClasses[$loan->status] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                
                                                <span class="{{ $currentClass }} text-xs font-medium px-2.5 py-0.5 rounded-full capitalize">
                                                    {{ $loan->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <!-- Botón para Ver Detalles (Lo haremos en el siguiente paso) -->
                                                <a href="{{ route('admin.loans.show', $loan) }}" class="font-medium text-blue-600 hover:underline">Revisar Solicitud</a>                                            </td>
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