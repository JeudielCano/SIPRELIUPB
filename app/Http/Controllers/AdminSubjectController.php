<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class AdminSubjectController extends Controller
{
    private function checkAdmin()
    {
        if (auth()->user()->role !== 'administrador') abort(403);
    }

    public function index()
    {
        $this->checkAdmin();
        $subjects = Subject::withCount('loanRequests')->orderBy('name')->get();
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $this->checkAdmin();
        return view('admin.subjects.create');
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name',
        ]);

        Subject::create(['name' => $request->name]);

        return redirect()->route('admin.subjects.index')
            ->with('status', 'Asignatura creada correctamente.');
    }

    public function edit(Subject $subject)
    {
        $this->checkAdmin();
        return view('admin.subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $this->checkAdmin();

        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->id,
        ]);

        $subject->update(['name' => $request->name]);

        return redirect()->route('admin.subjects.index')
            ->with('status', 'Asignatura actualizada correctamente.');
    }

    public function destroy(Subject $subject)
    {
        $this->checkAdmin();

        if ($subject->loanRequests()->count() > 0) {
            return back()->withErrors([
                'status' => "No se puede eliminar '{$subject->name}' porque tiene {$subject->loanRequests()->count()} solicitud(es) asociada(s)."
            ]);
        }

        $subject->delete();

        return redirect()->route('admin.subjects.index')
            ->with('status', 'Asignatura eliminada correctamente.');
    }
}