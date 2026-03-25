<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            🛡️ {{ __('Gestión de Subresguardantes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-auth-session-status :status="session('status')" class="mb-4" />

            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6">
                <form action="{{ route('admin.guardians.index') }}" method="GET" class="flex items-center gap-2">
                    <div class="relative w-full md:w-96">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" 
                               placeholder="Buscar docente por nombre o correo...">
                    </div>
                    <button type="submit" class="px-4 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition-colors shadow-sm active:scale-95">
                        Buscar
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.guardians.index') }}" class="text-xs text-red-600 hover:underline ml-2">Limpiar</a>
                    @endif
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="p-0">
                    @if($docentes->isEmpty())
                        <div class="text-center py-12 text-gray-400">
                            <div class="mb-3">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 italic">No se encontraron docentes</h3>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                                    <tr>
                                        <th class="px-6 py-4">Docente</th>
                                        <th class="px-6 py-4">Email</th>
                                        <th class="px-6 py-4">Recursos Asignados</th>
                                        <th class="px-6 py-4 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($docentes as $docente)
                                        <tr class="bg-white hover:bg-blue-50/30 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-gray-900">{{ $docente->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-xs">{{ $docente->email }}</td>
                                            <td class="px-6 py-4">
                                                @if($docente->guardianResources->isEmpty())
                                                    <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider italic">Sin asignaciones</span>
                                                @else
                                                    <div class="flex flex-wrap gap-1.5">
                                                        @foreach($docente->guardianResources as $assignment)
                                                            <span class="bg-blue-50 text-blue-700 border border-blue-100 text-[10px] font-bold px-2 py-0.5 rounded-md shadow-sm">
                                                                {{ $resourceName = $assignment->resource->name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="{{ route('admin.guardians.edit', $docente) }}"
                                                   class="inline-flex items-center justify-center text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 font-bold rounded-lg text-xs px-4 py-2 transition-all shadow-sm active:scale-95 group">
                                                    <svg class="w-4 h-4 mr-2 text-blue-500 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                    Gestionar
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                            {{ $docentes->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>