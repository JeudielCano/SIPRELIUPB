<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Administración') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <x-auth-session-status :status="session('status')" />
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-xl font-semibold mb-4">Gestión de Usuarios</h3>
                    
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nombre</th>
                                    <th scope="col" class="px-6 py-3">Email</th>
                                    
                                    <th scope="col" class="px-6 py-3">Tipo Solicitante</th>
                                    <th scope="col" class="px-6 py-3">Matrícula</th>
                                    <th scope="col" class="px-6 py-3">Fecha de Registro</th>
                                    
                                    <th scope="col" class="px-6 py-3">Estado</th>
                                    <th scope="col" class="px-6 py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $user->name }}
                                    </th>
                                    <td class="px-6 py-4">{{ $user->email }}</td>
                                    
                                    <td class="px-6 py-4">{{ ucfirst($user->applicant_type) }}</td> 
                                    
                                    <td class="px-6 py-4">{{ $user->student_id ?? '---' }}</td> 
                                    
                                    <td class="px-6 py-4">{{ $user->created_at->format('d/m/Y') }}</td> 

                                    <td class="px-6 py-4">
                                        @if ($user->approved_at)
                                            <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">Aprobado</span>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">Pendiente</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 flex items-center space-x-2">
                                        @if (!$user->approved_at && $user->role !== 'administrador')
                                            <form action="{{ route('admin.users.approve', $user) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="font-medium text-blue-600 hover:underline">Aprobar</button>
                                            </form>
                                        @endif

                                        @if ($user->id !== Auth::id())
                                            <form action="{{ route('admin.users.reject', $user) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres ELIMINAR a este usuario? Esto no se puede deshacer.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 hover:underline">
                                                    {{ $user->approved_at ? 'Eliminar' : 'Rechazar' }}
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No hay usuarios registrados.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>