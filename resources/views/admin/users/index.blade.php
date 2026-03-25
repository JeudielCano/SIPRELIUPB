<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Administración') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('status'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-100 overflow-hidden">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                        <h3 class="text-2xl font-bold text-gray-800">Gestión de Usuarios</h3>
                        
                        <form method="GET" action="{{ route('admin.users.index') }}" class="w-full md:w-1/3 flex">
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" 
                                       placeholder="Buscar por nombre, email o matrícula...">
                            </div>
                            <button type="submit" class="p-2.5 text-sm font-medium text-white bg-blue-600 rounded-r-lg border border-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 transition-colors">
                                Buscar
                            </button>
                        </form>
                    </div>
                    
                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-4 font-bold">Usuario</th>
                                    <th scope="col" class="px-6 py-4 font-bold">Tipo / Matrícula</th>
                                    <th scope="col" class="px-6 py-4 font-bold">Registro</th>
                                    <th scope="col" class="px-6 py-4 font-bold">Estado</th>
                                    <th scope="col" class="px-6 py-4 font-bold text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($users as $user)
                                <tr class="bg-white hover:bg-gray-50 transition-colors">
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-900 text-base">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-800">{{ ucfirst($user->applicant_type) }}</div>
                                        <div class="text-xs text-gray-500 font-mono">{{ $user->student_id ?? 'Sin Matrícula' }}</div>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                        {{ $user->created_at->format('d M, Y') }}
                                    </td> 

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($user->approved_at)
                                            <span class="inline-flex items-center gap-1.5 bg-green-100 text-green-800 text-xs font-bold px-3 py-1.5 rounded-full">
                                                <span class="w-1.5 h-1.5 bg-green-600 rounded-full"></span>
                                                Aprobado
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1.5 rounded-full">
                                                <span class="w-1.5 h-1.5 bg-yellow-600 rounded-full"></span>
                                                Pendiente
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center justify-center space-x-3">
                                            @if (!$user->approved_at && $user->role !== 'administrador')
                                                <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="m-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 px-4 rounded-lg shadow-sm transition-transform active:scale-95">
                                                        Aprobar
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($user->id !== Auth::id())
                                                <form action="{{ route('admin.users.reject', $user) }}" method="POST" class="m-0" onsubmit="return confirm('¿Estás seguro de que quieres ELIMINAR a este usuario? Esto no se puede deshacer.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="border border-red-500 text-red-500 hover:bg-red-500 hover:text-white text-xs font-bold py-2 px-4 rounded-lg transition-all active:scale-95">
                                                        {{ $user->approved_at ? 'Eliminar' : 'Rechazar' }}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            <p>No se encontraron usuarios.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>