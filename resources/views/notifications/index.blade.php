<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mis Notificaciones
            </h2>
            @if($notifications->isNotEmpty())
                <form method="POST" action="{{ route('notifications.clear') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="text-sm text-red-600 hover:text-red-800 font-medium">
                        Limpiar todas
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg divide-y divide-gray-100">
                @forelse($notifications as $notification)
                    <div class="p-5 flex items-start gap-4 {{ $notification->read_at ? 'opacity-60' : 'bg-blue-50' }}">
                        
                        @if($notification->data['type'] === 'aprobado')
                            <span class="text-2xl">✅</span>
                        @elseif($notification->data['type'] === 'rechazado')
                            <span class="text-2xl">❌</span>
                        @else
                            <span class="text-2xl">📋</span>
                        @endif

                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $notification->data['message'] }}
                            </p>

                            {{-- Motivo de rechazo si existe --}}
                            @if(isset($notification->data['reason']))
                                <p class="text-sm text-red-600 mt-1">
                                    <span class="font-semibold">Motivo:</span> {{ $notification->data['reason'] }}
                                </p>
                            @endif

                            <p class="text-xs text-gray-400 mt-1">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>

                        @if(!$notification->read_at)
                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">Nueva</span>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-400">
                        No tienes notificaciones.
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</x-app-layout>