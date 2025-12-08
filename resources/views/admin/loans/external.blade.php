<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
            <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            {{ __('Solicitudes de Préstamo Externo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Aviso Informativo -->
            <div class="bg-purple-50 border-l-4 border-purple-500 p-4 mb-6 shadow-sm rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-purple-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-purple-700">
                            Estas solicitudes requieren una revisión especial. Verifica que el usuario haya entregado el <strong>permiso firmado</strong> antes de aprobar.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    
                    @if($loans->isEmpty())
                        <div class="text-center py-12">
                            <div class="text-gray-300 mb-4">
                                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <p class="text-lg text-gray-500">No hay solicitudes de préstamo externo pendientes.</p>
                        </div>
                    @else
                        <div class="relative overflow-x-auto shadow-sm sm:rounded-lg border border-gray-100">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Folio</th>
                                        <th scope="col" class="px-6 py-3">Solicitante</th>
                                        <th scope="col" class="px-6 py-3">Periodo del Préstamo</th>
                                        <th scope="col" class="px-6 py-3">Etiqueta</th>
                                        <th scope="col" class="px-6 py-3 text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loans as $loan)
                                        <tr class="bg-white border-b hover:bg-gray-50 transition-colors">
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                                #{{ $loan->id }}
                                            </th>
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-gray-900">{{ $loan->user->name }}</div>
                                                <div class="text-xs text-gray-500">{{ ucfirst($loan->user->applicant_type) }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-xs">
                                                    <span class="font-bold text-gray-700">Del:</span> {{ $loan->pickup_at->format('d/m/Y H:i') }}<br>
                                                    <span class="font-bold text-gray-700">Al:</span> {{ $loan->due_at->format('d/m/Y H:i') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full border border-purple-300">
                                                    Externa
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <a href="{{ route('admin.loans.show', $loan) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                                    Revisar y Aprobar
                                                    <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                                    </svg>
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