<?php

namespace App\Http\Controllers;

use App\Models\ActivityType;
use Illuminate\Http\Request;

class AdminActivityTypeController extends Controller
{
    private function checkAdmin()
    {
        if (auth()->user()->role !== 'administrador') abort(403);
    }

    public function index()
    {
        $this->checkAdmin();
        $activityTypes = ActivityType::withCount('loanRequests')->orderBy('name')->get();
        return view('admin.activity_types.index', compact('activityTypes'));
    }

    public function create()
    {
        $this->checkAdmin();
        return view('admin.activity_types.create');
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'name' => 'required|string|max:255|unique:activity_types,name',
        ]);

        ActivityType::create(['name' => $request->name]);

        return redirect()->route('admin.activity_types.index')
            ->with('status', 'Tipo de actividad creado correctamente.');
    }

    public function edit(ActivityType $activityType)
    {
        $this->checkAdmin();
        return view('admin.activity_types.edit', compact('activityType'));
    }

    public function update(Request $request, ActivityType $activityType)
    {
        $this->checkAdmin();

        $request->validate([
            'name' => 'required|string|max:255|unique:activity_types,name,' . $activityType->id,
        ]);

        $activityType->update(['name' => $request->name]);

        return redirect()->route('admin.activity_types.index')
            ->with('status', 'Tipo de actividad actualizado correctamente.');
    }

    public function destroy(ActivityType $activityType)
    {
        $this->checkAdmin();

        if ($activityType->loanRequests()->count() > 0) {
            return back()->withErrors([
                'status' => "No se puede eliminar '{$activityType->name}' porque tiene {$activityType->loanRequests()->count()} solicitud(es) asociada(s)."
            ]);
        }

        $activityType->delete();

        return redirect()->route('admin.activity_types.index')
            ->with('status', 'Tipo de actividad eliminado correctamente.');
    }
}