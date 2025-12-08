<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Préstamos Activos y Aprobados') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if($loans->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            No hay préstamos activos ni aprobados pendientes de retiro en este momento.
                        </div>
                    @else
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Folio</th>
                                        <th scope="col" class="px-6 py-3">Solicitante</th>
                                        <th scope="col" class="px-6 py-3">Fechas Clave</th>
                                        <th scope="col" class="px-6 py-3">Estado</th>
                                        <th scope="col" class="px-6 py-3">Código Retiro</th>
                                        <th scope="col" class="px-6 py-3">Acción</th>
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
                                            <td class="px-6 py-4 text-xs">
                                                <div class="mb-1">Retiro: <strong class="text-gray-700">{{ $loan->pickup_at->format('d/m H:i') }}</strong></div>
                                                <div>Entrega: <strong class="text-gray-700">{{ $loan->due_at->format('d/m H:i') }}</strong></div>
                                                @if($loan->status === 'activo' && now() > $loan->due_at)
                                                    <span class="mt-1 inline-block text-red-600 font-bold bg-red-50 px-1 rounded">¡Vencido!</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($loan->status === 'aprobado')
                                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full border border-blue-400">Por Recoger</span>
                                                @elseif($loan->status === 'activo')
                                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full border border-green-400">En Uso</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 font-mono font-bold text-lg text-gray-700 tracking-wider">
                                                {{ $loan->pickup_code }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <a href="{{ route('admin.loans.show', $loan) }}" class="inline-flex items-center font-medium text-blue-600 hover:underline">
                                                    @if($loan->status === 'aprobado')
                                                        Entregar Material
                                                        <svg class="w-4 h-4 ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                                                    @else
                                                        Registrar Devolución
                                                        <svg class="w-4 h-4 ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path></svg>
                                                    @endif
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