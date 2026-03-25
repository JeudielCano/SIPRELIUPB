<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                📊 Mis Reportes de Subresguardo
            </h2>
            <a href="{{ route('guardian.reports.download') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Descargar PDF
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Estadísticas --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 text-center">
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">Total</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 text-center">
                    <p class="text-3xl font-bold text-green-600">{{ $stats['finalizados'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">Finalizados</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 text-center">
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['activos'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">En curso</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 text-center">
                    <p class="text-3xl font-bold text-red-600">{{ $stats['rechazados'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">Rechazados</p>
                </div>
            </div>

            {{-- Tabla de historial --}}
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Historial de Préstamos</h3>

                    @if($loans->isEmpty())
                        <p class="text-center text-gray-400 py-8">No hay préstamos registrados aún.</p>
                    @else
                        <div class="relative overflow-x-auto border rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3">Folio</th>
                                        <th class="px-4 py-3">Solicitante</th>
                                        <th class="px-4 py-3">Recursos</th>
                                        <th class="px-4 py-3">Retiro</th>
                                        <th class="px-4 py-3">Devolución</th>
                                        <th class="px-4 py-3">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loans as $loan)
                                        @php
                                            $statusClasses = [
                                                'aprobado'  => 'bg-blue-100 text-blue-800',
                                                'activo'    => 'bg-green-100 text-green-800',
                                                'rechazado' => 'bg-red-100 text-red-800',
                                                'finalizado'=> 'bg-gray-100 text-gray-800',
                                            ];
                                            $sc = $statusClasses[$loan->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="px-4 py-3 font-medium text-gray-900">#{{ $loan->id }}</td>
                                            <td class="px-4 py-3">
                                                <div class="font-medium text-gray-900">{{ $loan->user->name }}</div>
                                                <div class="text-xs text-gray-400">{{ ucfirst($loan->user->applicant_type) }}</div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($loan->items as $item)
                                                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">
                                                            {{ $item->resource->name }} ({{ $item->quantity }})
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 text-xs">{{ $loan->pickup_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-4 py-3 text-xs">
                                                {{ $loan->return_at ? $loan->return_at->format('d/m/Y H:i') : $loan->due_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="{{ $sc }} text-xs font-medium px-2.5 py-0.5 rounded-full capitalize">
                                                    {{ $loan->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div>
                <a href="{{ route('guardian.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    ← Volver a Mis Subresguardos
                </a>
            </div>

        </div>
    </div>
</x-app-layout>