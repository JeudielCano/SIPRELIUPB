<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Devolución - Solicitud #') . $loan->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Tarjeta de Información del Solicitante -->
            <div class="bg-white p-6 rounded-lg shadow mb-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Solicitante: {{ $loan->user->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $loan->user->email }}</p>
                        @if($loan->user->student_id)
                            <p class="text-sm text-gray-500">Matrícula: {{ $loan->user->student_id }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Fecha límite de entrega:</p>
                        <p class="font-bold text-gray-900">{{ $loan->due_at->format('d/m/Y h:i A') }}</p>
                        @if(now() > $loan->due_at)
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Entrega Tardía</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Formulario de Inspección -->
            <form action="{{ route('admin.loans.process_return', $loan) }}" method="POST">
                @csrf
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Inspección de Recursos</h3>
                        <p class="text-sm text-gray-500 mb-4 bg-yellow-50 p-3 rounded border border-yellow-200 flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Por favor, verifica el estado físico de cada recurso <strong>antes</strong> de finalizar. Si hay daños o pérdidas, selecciónalo en la lista para que quede registrado.</span>
                        </p>

                        <div class="relative overflow-x-auto border rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                    <tr>
                                        <th class="px-6 py-3">Recurso</th>
                                        <th class="px-6 py-3 text-center">Cantidad</th>
                                        <th class="px-6 py-3">Estado de Devolución</th>
                                        <th class="px-6 py-3">Observaciones (Daños, etc.)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loan->items as $item)
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                {{ $item->resource->name }}
                                                <br>
                                                <span class="text-xs text-gray-400">Inv: {{ $item->resource->inventory_number ?? 'N/A' }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-center font-bold text-lg">
                                                {{ $item->quantity }}
                                            </td>
                                            
                                            <!-- Selector de Estado -->
                                            <td class="px-6 py-4">
                                                <!-- Usamos items[id][campo] para enviar un array estructurado al controlador -->
                                                <select name="items[{{ $item->id }}][return_status]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                                    <option value="bueno" selected>🟢 Buen Estado</option>
                                                    <option value="dañado">🔴 Dañado</option>
                                                    <option value="perdido">⚫ Perdido / No entregado</option>
                                                </select>
                                            </td>

                                            <!-- Observaciones -->
                                            <td class="px-6 py-4">
                                                <input type="text" name="items[{{ $item->id }}][return_observations]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Ej. Pantalla rayada, falta cable...">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex justify-end mt-6 space-x-3">
                    <a href="{{ route('admin.loans.show', $loan) }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                        Cancelar
                    </a>
                    <button type="submit" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 shadow-lg hover:shadow-xl transition-all" onclick="return confirm('¿Estás seguro de finalizar este préstamo? Esta acción cerrará la solicitud permanentemente y registrará la fecha de devolución.');">
                        Finalizar Préstamo y Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>