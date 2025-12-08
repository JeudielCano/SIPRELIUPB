<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reportes e Historial de Préstamos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if($finishedLoans->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            No hay préstamos finalizados en el historial.
                        </div>
                    @else
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Folio</th>
                                        <th scope="col" class="px-6 py-3">Solicitante</th>
                                        <th scope="col" class="px-6 py-3">Fecha Devolución</th>
                                        <th scope="col" class="px-6 py-3">Actividad</th>
                                        <th scope="col" class="px-6 py-3 text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($finishedLoans as $loan)
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="px-6 py-4 font-medium text-gray-900">#{{ $loan->id }}</td>
                                            <td class="px-6 py-4">
                                                <div class="font-bold">{{ $loan->user->name }}</div>
                                                <div class="text-xs">{{ ucfirst($loan->user->applicant_type) }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $loan->return_at ? $loan->return_at->format('d/m/Y H:i') : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4">{{ $loan->activityType->name }}</td>
                                            <td class="px-6 py-4 text-center">
                                                <a href="{{ route('admin.reports.show', $loan) }}" class="text-blue-600 hover:underline mr-3">Ver Detalles</a>
                                                <a href="{{ route('admin.reports.pdf', $loan) }}" class="text-red-600 hover:underline font-bold flex items-center justify-center inline-flex">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    PDF
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
        </div>
    </div>
</x-app-layout>