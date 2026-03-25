<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Resource;
use App\Models\ResourceGuardian;
use Illuminate\Http\Request;

class AdminGuardianController extends Controller
{
    // Lista de docentes y sus recursos asignados
    public function index(Request $request) // <-- Agregamos el objeto Request
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        // 1. Iniciamos la consulta con tus filtros base
        $query = User::where('applicant_type', 'docente')
                    ->where('role', 'solicitante')
                    ->with('guardianResources.resource')
                    ->orderBy('name');

        // 2. Si el usuario escribió algo en el buscador, filtramos por nombre o email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }

        // 3. Cambiamos get() por paginate(10) y mantenemos los filtros en la URL
        $docentes = $query->paginate(10)->withQueryString();

        return view('admin.guardians.index', compact('docentes'));
    }

    // Muestra el formulario de asignación para un docente específico
    public function edit(User $user, Request $request)
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        // 1. Buscador de recursos para la asignación
        $query = Resource::where('status', '!=', 'dado_de_baja');

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // 2. Paginamos de 10 en 10
        $resources = $query->paginate(10)->withQueryString();

        // 3. Obtenemos los IDs que ya tiene el docente (para marcar los checks)
        $assignedIds = $user->guardianResources->pluck('resource_id')->toArray();

        return view('admin.guardians.edit', compact('user', 'resources', 'assignedIds'));
    }

    // Guarda la asignación de recursos
    public function update(Request $request, User $user)
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        $request->validate([
            'resources'   => 'nullable|array',
            'resources.*' => 'exists:resources,id',
        ]);

        // Sincronizamos: elimina los que ya no están y agrega los nuevos
        $selectedIds = $request->resources ?? [];

        // Borra todas las asignaciones actuales del docente
        ResourceGuardian::where('user_id', $user->id)->delete();

        // Crea las nuevas
        foreach ($selectedIds as $resourceId) {
            ResourceGuardian::create([
                'user_id'     => $user->id,
                'resource_id' => $resourceId,
            ]);
        }

        return redirect()->route('admin.guardians.index')
            ->with('status', 'Recursos asignados correctamente a ' . $user->name . '.');
    }
}