<?php

namespace App\Http\Controllers;

use App\Models\Career;
use Illuminate\Http\Request;

class AdminCareerController extends Controller
{
    private function checkAdmin()
    {
        if (auth()->user()->role !== 'administrador') abort(403);
    }

    public function index()
    {
        $this->checkAdmin();
        $careers = Career::withCount('resources')->orderBy('name')->get();
        return view('admin.careers.index', compact('careers'));
    }

    public function create()
    {
        $this->checkAdmin();
        return view('admin.careers.create');
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'name'      => 'required|string|max:50|unique:careers,name',
            'full_name' => 'nullable|string|max:255',
            'active'    => 'boolean',
        ]);

        Career::create([
            'name'      => strtoupper($request->name),
            'full_name' => $request->full_name,
            'active'    => $request->has('active'),
        ]);

        return redirect()->route('admin.careers.index')
            ->with('status', 'Carrera creada correctamente.');
    }

    public function edit(Career $career)
    {
        $this->checkAdmin();
        return view('admin.careers.edit', compact('career'));
    }

    public function update(Request $request, Career $career)
    {
        $this->checkAdmin();

        $request->validate([
            'name'      => 'required|string|max:50|unique:careers,name,' . $career->id,
            'full_name' => 'nullable|string|max:255',
            'active'    => 'boolean',
        ]);

        $career->update([
            'name'      => strtoupper($request->name),
            'full_name' => $request->full_name,
            'active'    => $request->has('active'),
        ]);

        return redirect()->route('admin.careers.index')
            ->with('status', 'Carrera actualizada correctamente.');
    }

    public function destroy(Career $career)
    {
        $this->checkAdmin();

        // No permitir eliminar si tiene recursos asignados
        if ($career->resources()->count() > 0) {
            return back()->withErrors([
                'status' => "No se puede eliminar '{$career->name}' porque tiene {$career->resources()->count()} recurso(s) asignado(s)."
            ]);
        }

        $career->delete();

        return redirect()->route('admin.careers.index')
            ->with('status', 'Carrera eliminada correctamente.');
    }
}