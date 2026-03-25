<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🛡️ Detalle de Solicitud #{{ $loan->id }}
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
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Mensajes --}}
            <x-auth-session-status :status="session('status')" />
            @if($errors->any())
                <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Datos del solicitante y préstamo --}}
            <div class="bg-white p-6 rounded-lg shadow border border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Datos del Solicitante</h3>
                    <p class="text-sm text-gray-600">Nombre: <span class="font-semibold text-gray-900">{{ $loan->user->name }}</span></p>
                    <p class="text-sm text-gray-600">Email: <span class="font-semibold text-gray-900">{{ $loan->user->email }}</span></p>
                    <p class="text-sm text-gray-600">Tipo: <span class="font-semibold text-gray-900">{{ ucfirst($loan->user->applicant_type) }}</span></p>
                    @if($loan->user->student_id)
                        <p class="text-sm text-gray-600">Matrícula: <span class="font-semibold text-gray-900">{{ $loan->user->student_id }}</span></p>
                    @endif
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Datos del Préstamo</h3>
                    <div class="p-3 bg-gray-50 rounded-lg space-y-1">
                        <p class="text-sm">📅 Retiro: <strong>{{ $loan->pickup_at->format('d/m/Y h:i A') }}</strong></p>
                        <p class="text-sm">📅 Devolución: <strong>{{ $loan->due_at->format('d/m/Y h:i A') }}</strong></p>
                    </div>
                    @if($loan->observations)
                        <div class="mt-3 p-3 bg-yellow-50 text-yellow-800 rounded-lg text-sm">
                            <strong>Observaciones:</strong> {{ $loan->observations }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Recursos solicitados --}}
            <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Recursos Solicitados</h3>
                <div class="relative overflow-x-auto border rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">Recurso</th>
                                <th class="px-6 py-3">Tipo</th>
                                <th class="px-6 py-3">Cantidad</th>
                                <th class="px-6 py-3">No. Inventario</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loan->items as $item)
                                <tr class="bg-white border-b">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $item->resource->name }}</td>
                                    <td class="px-6 py-4">{{ ucfirst($item->resource->type) }}</td>
                                    <td class="px-6 py-4 font-bold">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4">{{ $item->resource->inventory_number ?? '---' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Acciones: PENDIENTE --}}
            @if($loan->status === 'pendiente')
                <div class="flex items-center justify-end gap-4 bg-white p-6 rounded-lg shadow border border-gray-200">
                    <span class="text-sm text-gray-500">¿Qué deseas hacer con esta solicitud?</span>

                    {{-- Botón rechazar (abre modal) --}}
                    <button onclick="document.getElementById('modal-rechazo').classList.remove('hidden')"
                        class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                        Rechazar
                    </button>

                    {{-- Aprobar --}}
                    <form action="{{ route('guardian.approve', $loan) }}" method="POST"
                        onsubmit="return confirm('¿Aprobar esta solicitud?');">
                        @csrf @method('PATCH')
                        <button type="submit"
                            class="text-white bg-green-700 hover:bg-green-800 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                            ✓ Aprobar
                        </button>
                    </form>
                </div>
            @endif

            {{-- Acciones: APROBADO --}}
            @if($loan->status === 'aprobado')
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 flex flex-col md:flex-row items-center justify-between shadow-sm">
                    <div>
                        <h4 class="text-lg font-bold text-blue-900">✅ Solicitud Aprobada</h4>
                        <p class="text-sm text-blue-700 mt-1">Verifica el código de retiro del solicitante:</p>
                        <div class="mt-3 inline-block bg-white px-4 py-2 rounded-lg border-2 border-blue-300 text-2xl font-mono font-black text-gray-800 tracking-widest">
                            {{ $loan->pickup_code }}
                        </div>
                    </div>
                    <form action="{{ route('guardian.deliver', $loan) }}" method="POST"
                        onsubmit="return confirm('¿Confirmas la entrega del material?');">
                        @csrf @method('PATCH')
                        <button type="submit"
                            class="mt-4 md:mt-0 text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-3 shadow-md transition-all hover:scale-105">
                            Confirmar Entrega
                        </button>
                    </form>
                </div>
            @endif

            {{-- Préstamo activo --}}
            @if($loan->status === 'activo')
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 shadow-sm">
                    <h4 class="text-lg font-bold text-green-900">🟢 Préstamo en Curso</h4>
                    <p class="text-sm text-green-700 mt-1">El material está en posesión del solicitante.</p>
                </div>
            @endif

            {{-- Botón volver --}}
            <div>
                <a href="{{ route('guardian.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    ← Volver a Mis Subresguardos
                </a>
            </div>

        </div>
    </div>

    {{-- Modal de Rechazo --}}
    <div id="modal-rechazo" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Rechazar Solicitud #{{ $loan->id }}</h3>
            <form method="POST" action="{{ route('guardian.reject', $loan) }}">
                @csrf @method('PATCH')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Motivo del rechazo <span class="text-gray-400">(opcional)</span>
                    </label>
                    <textarea name="reason" rows="3" maxlength="300"
                        class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-red-300 resize-none"
                        placeholder="Ej: Stock insuficiente, fechas no disponibles..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button"
                        onclick="document.getElementById('modal-rechazo').classList.add('hidden')"
                        class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700">
                        Confirmar Rechazo
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>