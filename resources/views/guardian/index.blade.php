<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                🛡️ {{ __('Mis subresguardos') }}
            </h2>
            {{-- Solo si tiene recursos asignados --}}
            @if($assignedResources->isNotEmpty())
                <a href="{{ route('guardian.reports') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                    📊 Ver Reportes
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if($assignedResources->isEmpty())
                {{-- Estado vacío --}}
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <div class="text-gray-300 mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <p class="text-lg font-medium text-gray-500">No te han asignado recursos aún.</p>
                        <p class="text-sm text-gray-400 mt-1">Cuando el administrador te asigne recursos, aparecerán aquí.</p>
                    </div>
                </div>
            @else
                {{-- Inventario asignado --}}
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">📦 Mis Recursos Asignados</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($assignedResources as $assignment)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <p class="font-medium text-gray-900">{{ $assignment->resource->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ ucfirst($assignment->resource->type) }}</p>
                                    <div class="mt-2 flex items-center justify-between">
                                        <span class="text-xs text-gray-400">Stock total:</span>
                                        <span class="text-sm font-bold text-gray-700">{{ $assignment->resource->total_stock }}</span>
                                    </div>
                                    <div class="mt-1">
                                        @if($assignment->resource->status === 'disponible')
                                            <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Disponible</span>
                                        @else
                                            <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full">{{ ucfirst($assignment->resource->status) }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Solicitudes pendientes --}}
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            ⏳ Solicitudes Pendientes
                            @if($pendingLoans->isNotEmpty())
                                <span class="ml-2 bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    {{ $pendingLoans->count() }}
                                </span>
                            @endif
                        </h3>

                        @if($pendingLoans->isEmpty())
                            <p class="text-sm text-gray-400 italic">No hay solicitudes pendientes.</p>
                        @else
                            <div class="relative overflow-x-auto border rounded-lg">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3">Folio</th>
                                            <th class="px-6 py-3">Solicitante</th>
                                            <th class="px-6 py-3">Recursos</th>
                                            <th class="px-6 py-3">Fecha Retiro</th>
                                            <th class="px-6 py-3 text-center">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingLoans as $loan)
                                            <tr class="bg-white border-b hover:bg-gray-50">
                                                <td class="px-6 py-4 font-medium text-gray-900">#{{ $loan->id }}</td>
                                                <td class="px-6 py-4">
                                                    <div class="font-medium text-gray-900">{{ $loan->user->name }}</div>
                                                    <div class="text-xs text-gray-400">{{ ucfirst($loan->user->applicant_type) }}</div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($loan->items as $item)
                                                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">
                                                                {{ $item->resource->name }} ({{ $item->quantity }})
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">{{ $loan->pickup_at->format('d/m/Y H:i') }}</td>
                                                <td class="px-6 py-4 text-center">
                                                    <a href="{{ route('guardian.show', $loan) }}"
                                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                                        Revisar
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Préstamos activos --}}
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            🟢 Préstamos Activos
                            @if($activeLoans->isNotEmpty())
                                <span class="ml-2 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    {{ $activeLoans->count() }}
                                </span>
                            @endif
                        </h3>

                        @if($activeLoans->isEmpty())
                            <p class="text-sm text-gray-400 italic">No hay préstamos activos en este momento.</p>
                        @else
                            <div class="relative overflow-x-auto border rounded-lg">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3">Folio</th>
                                            <th class="px-6 py-3">Solicitante</th>
                                            <th class="px-6 py-3">Recursos</th>
                                            <th class="px-6 py-3">Devolución</th>
                                            <th class="px-6 py-3 text-center">Estado</th>
                                            <th class="px-6 py-3 text-center">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($activeLoans as $loan)
                                            <tr class="bg-white border-b hover:bg-gray-50">
                                                <td class="px-6 py-4 font-medium text-gray-900">#{{ $loan->id }}</td>
                                                <td class="px-6 py-4">
                                                    <div class="font-medium text-gray-900">{{ $loan->user->name }}</div>
                                                    <div class="text-xs text-gray-400">{{ ucfirst($loan->user->applicant_type) }}</div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($loan->items as $item)
                                                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">
                                                                {{ $item->resource->name }} ({{ $item->quantity }})
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">{{ $loan->due_at->format('d/m/Y H:i') }}</td>
                                                <td class="px-6 py-4 text-center">
                                                    @if($loan->status === 'aprobado')
                                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Aprobado</span>
                                                    @else
                                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Activo</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <a href="{{ route('guardian.show', $loan) }}"
                                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                                                        Ver
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>