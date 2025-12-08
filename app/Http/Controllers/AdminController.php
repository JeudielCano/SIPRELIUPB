<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LoanRequest;
use App\Models\LoanItem; // <-- IMPORTANTE: Necesario para la gráfica
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Muestra el Dashboard Principal con tarjetas y estadísticas.
     */
    public function index(): View
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        // 1. Contadores Generales
        $pending_users = User::whereNull('approved_at')->where('role', '!=', 'administrador')->count();
        
        // Solicitudes Internas Pendientes
        $pending_internal = LoanRequest::where('status', 'pendiente')
            ->where(function ($query) {
                $query->whereNull('observations')
                      ->orWhere('observations', 'NOT LIKE', '%(SOLICITUD EXTERNA)%');
            })->count();

        // Solicitudes Externas Pendientes
        $pending_external = LoanRequest::where('status', 'pendiente')
            ->where('observations', 'LIKE', '%(SOLICITUD EXTERNA)%')
            ->count();

        // Préstamos Activos (Aprobados para retirar o En uso)
        $active_loans = LoanRequest::whereIn('status', ['aprobado', 'activo'])->count();
        
        $total_resources = Resource::count();

        // 2. Datos para la Gráfica (Recursos más solicitados por Tipo)
        // Hacemos un join con la tabla de recursos para agrupar por 'type'
        $chartData = LoanItem::join('resources', 'loan_items.resource_id', '=', 'resources.id')
            ->selectRaw('resources.type, count(*) as count')
            ->groupBy('resources.type')
            ->pluck('count', 'type'); // Devuelve un array tipo ['equipo' => 10, 'laboratorio' => 5]

        // Empaquetamos todo en un array ordenado
        $stats = [
            'pending_users'    => $pending_users,
            'pending_internal' => $pending_internal,
            'pending_external' => $pending_external,
            'active_loans'     => $active_loans,
            'total_resources'  => $total_resources,
            'chart' => [
                'equipo'      => $chartData['equipo'] ?? 0,
                'laboratorio' => $chartData['laboratorio'] ?? 0,
                'insumo'      => $chartData['insumo'] ?? 0,
            ]
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Muestra la lista de Usuarios para gestionar aprobaciones.
     */
    public function users(): View
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Métodos de acción (approve/reject) se mantienen igual...
    public function approve(User $user)
    {
        if (auth()->user()->role !== 'administrador') abort(403);
        $user->update(['approved_at' => now()]);
        return back()->with('status', 'Usuario aprobado exitosamente.');
    }

    public function reject(User $user)
    {
        if (auth()->user()->role !== 'administrador') abort(403);
        if ($user->id === auth()->id()) return back()->withErrors(['email' => 'No puedes eliminarte a ti mismo.']);
        $user->delete();
        return back()->with('status', 'Usuario eliminado.');
    }
}