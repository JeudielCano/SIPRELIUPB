<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reporte Final #') . $loan->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <a href="{{ route('admin.reports.pdf', $loan) }}" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Descargar PDF
                </a>
            </div>

            <!-- Aquí reutilizamos el diseño del detalle, pero solo modo lectura -->
            <div class="bg-white p-8 rounded-lg shadow border border-gray-200">
                <div class="text-center border-b pb-4 mb-4">
                    <h1 class="text-2xl font-bold text-gray-900">REPORTE DE ENTREGA</h1>
                    <p class="text-sm text-gray-500">Folio: #{{ $loan->id }} | Finalizado el: {{ $loan->return_at->format('d/m/Y h:i A') }}</p>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-8 mb-8">
                    <div>
                        <h3 class="font-bold text-gray-700 border-b mb-2">Solicitante</h3>
                        <p>{{ $loan->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $loan->user->email }}</p>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-700 border-b mb-2">Detalles</h3>
                        <p>Actividad: {{ $loan->activityType->name }}</p>
                        <p>Asignatura: {{ $loan->subject->name }}</p>
                    </div>
                </div>

                <!-- Tabla -->
                <h3 class="font-bold text-gray-700 mb-2">Recursos Devueltos</h3>
                <table class="w-full text-sm text-left text-gray-500 mb-8">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">Recurso</th>
                            <th class="px-4 py-2">Cant.</th>
                            <th class="px-4 py-2">Estado</th>
                            <th class="px-4 py-2">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loan->items as $item)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $item->resource->name }}</td>
                                <td class="px-4 py-2">{{ $item->quantity }}</td>
                                <td class="px-4 py-2 uppercase font-bold text-xs">{{ $item->return_status }}</td>
                                <td class="px-4 py-2">{{ $item->return_observations }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>