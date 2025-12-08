<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Control - SIPRELI UPB') }}
        </h2>
    </x-slot>

    <!-- Incluimos Chart.js desde CDN para la gráfica -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                
                <!-- 1. GESTIONAR USUARIOS -->
                <a href="{{ route('admin.users.index') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        @if($stats['pending_users'] > 0)
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $stats['pending_users'] }} Pendientes</span>
                        @endif
                    </div>
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">Gestionar Usuarios</h5>
                    <p class="font-normal text-gray-700">Aprobar registros de nuevos alumnos y docentes.</p>
                </a>

                <!-- 2. SOLICITUDES INTERNAS -->
                <a href="{{ route('admin.loans.index') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        @if($stats['pending_internal'] > 0)
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $stats['pending_internal'] }} Nuevas</span>
                        @endif
                    </div>
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">Solicitudes Internas</h5>
                    <p class="font-normal text-gray-700">Préstamos diarios de laboratorio y equipos.</p>
                </a>

                <!-- 3. PRÉSTAMOS ACTIVOS -->
                <a href="{{ route('admin.active-loans') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $stats['active_loans'] }} En Curso</span>
                    </div>
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">Préstamos Activos</h5>
                    <p class="font-normal text-gray-700">Monitorear entregas y registrar devoluciones.</p>
                </a>

                <!-- 4. INVENTARIO -->
                <a href="{{ route('admin.resources.index') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        </div>
                        <span class="text-xs text-gray-500 font-bold">{{ $stats['total_resources'] }} ítems</span>
                    </div>
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">Inventario</h5>
                    <p class="font-normal text-gray-700">Gestionar catálogo de recursos.</p>
                </a>

                <!-- 5. PRÉSTAMO EXTERNO (ACTUALIZADO Y FUNCIONAL) -->
                <a href="{{ route('admin.loans.external') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition transform hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        @if($stats['pending_external'] > 0)
                            <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $stats['pending_external'] }} Pendientes</span>
                        @endif
                    </div>
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">Préstamos Externos</h5>
                    <p class="font-normal text-gray-700">Revisar solicitudes de salida de material.</p>
                </a>

                <!-- 6. GRÁFICA DE ESTADÍSTICAS (NUEVA CARD) -->
                <div class="block p-6 bg-white border border-gray-200 rounded-lg shadow col-span-1 md:col-span-2 lg:col-span-1">
                    <h5 class="mb-4 text-lg font-bold tracking-tight text-gray-900 text-center">Recursos Más Solicitados</h5>
                    
                    <!-- Contenedor del Canvas -->
                    <div class="relative" style="height: 200px;">
                        <canvas id="resourcesChart"></canvas>
                    </div>
                    
                    @if(array_sum($stats['chart']) === 0)
                        <p class="text-xs text-center text-gray-400 mt-4">No hay datos suficientes aún.</p>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <!-- Script para la Gráfica -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('resourcesChart').getContext('2d');
            
            // Datos que vienen del controlador
            const chartData = {
                labels: ['Equipos', 'Laboratorios', 'Insumos'],
                datasets: [{
                    label: 'Solicitudes',
                    data: [
                        {{ $stats['chart']['equipo'] }},
                        {{ $stats['chart']['laboratorio'] }},
                        {{ $stats['chart']['insumo'] }}
                    ],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.6)', // Azul (Equipo)
                        'rgba(16, 185, 129, 0.6)', // Verde (Laboratorio)
                        'rgba(245, 158, 11, 0.6)'  // Naranja (Insumo)
                    ],
                    borderColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(245, 158, 11, 1)'
                    ],
                    borderWidth: 1
                }]
            };

            const config = {
                type: 'doughnut', // Tipo de gráfica: Dona
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 12, font: { size: 10 } }
                        }
                    }
                }
            };

            new Chart(ctx, config);
        });
    </script>
</x-app-layout>