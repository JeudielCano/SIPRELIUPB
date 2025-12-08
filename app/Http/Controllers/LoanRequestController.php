<?php

namespace App\Http\Controllers;

use App\Models\LoanRequest;
use App\Models\LoanItem;
use App\Models\ActivityType;
use App\Models\Subject;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoanRequestController extends Controller
{
    public function index()
    {
        $loans = LoanRequest::where('user_id', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('loans.index', compact('loans'));
    }

    /**
     * Muestra el formulario de creación.
     */
    public function create()
    {
        $activityTypes = ActivityType::all();
        $subjects = Subject::all();

        // 1. Consulta base: recursos activos (no en mantenimiento)
        $query = Resource::where('status', '!=', 'mantenimiento');

        // Regla: Alumnos no ven laboratorios
        if (auth()->user()->applicant_type === 'alumno') {
            $query->where('type', '!=', 'laboratorio');
        }

        $resources = $query->get();

        // 2. Calculamos el stock real disponible
        $resources = $resources->map(function ($resource) {
            // Contamos cuántos están prestados/reservados
            $reservedCount = LoanItem::where('resource_id', $resource->id)
                ->whereHas('loanRequest', function ($q) {
                    $q->whereIn('status', ['aprobado', 'activo']);
                })
                ->sum('quantity');
            
            // Restamos. Si da negativo, lo dejamos en 0.
            // Sobrescribimos 'total_stock' para que la vista maneje este número como el disponible.
            $resource->total_stock = max(0, $resource->total_stock - $reservedCount);
            
            return $resource;
        });
        // [CAMBIO] Eliminamos el ->filter() para que los recursos con stock 0 TAMBIÉN lleguen a la vista.

        return view('loans.create', compact('activityTypes', 'subjects', 'resources'));
    }

    /**
     * Guarda la solicitud.
     */
    public function store(Request $request)
    {
        $items = json_decode($request->selected_items, true);
        $request->merge(['items_array' => $items]);

        $messages = [
            'activity_type_id.required' => 'Selecciona una actividad.',
            'subject_id.required' => 'Selecciona una asignatura.',
            'pickup_at.required' => 'La fecha de retiro es obligatoria.',
            'due_at.required' => 'La hora de devolución es obligatoria.',
            'items_array.required' => 'La lista no puede estar vacía.',
        ];

        $validated = $request->validate([
            'activity_type_id' => 'required|exists:activity_types,id',
            'subject_id' => 'required|exists:subjects,id',
            
            'pickup_at' => [
                'required', 'date',
                function ($attribute, $value, $fail) {
                    try {
                        $pickupDate = Carbon::parse($value, 'America/Cancun');
                        if ($pickupDate->isBefore(Carbon::now('America/Cancun')->subMinute())) {
                            $fail('La fecha de retiro debe ser futura.');
                        }
                    } catch (\Exception $e) { $fail('Fecha inválida.'); }
                },
            ],
            'due_at' => 'required|date|after:pickup_at',
            'observations' => 'nullable|string|max:500',
            'items_array' => 'required|array|min:1',
            'items_array.*.id' => 'required|exists:resources,id',
            'items_array.*.quantity' => 'required|integer|min:1',
        ], $messages);

        // Validaciones de Negocio (Stock y Perfil)
        foreach ($items as $itemData) {
            $resource = Resource::find($itemData['id']);
            if (!$resource) continue;

            // Validar Laboratorio para Alumnos
            if (auth()->user()->applicant_type === 'alumno' && $resource->type === 'laboratorio') {
                return back()->withErrors(['items_array' => 'No tienes permiso para solicitar laboratorios.'])->withInput();
            }

            // Validar Stock en el momento exacto de guardar
            $reservedCount = LoanItem::where('resource_id', $resource->id)
                ->whereHas('loanRequest', function ($q) {
                    $q->whereIn('status', ['aprobado', 'activo']);
                })->sum('quantity');
            
            $available = $resource->total_stock - $reservedCount;

            if ($itemData['quantity'] > $available) {
                return back()->withErrors(['items_array' => "El recurso '{$resource->name}' ya no tiene stock suficiente (Disponibles: {$available})."])->withInput();
            }
        }

        DB::transaction(function () use ($validated, $items) {
            $loan = LoanRequest::create([
                'user_id' => auth()->id(),
                'activity_type_id' => $validated['activity_type_id'],
                'subject_id' => $validated['subject_id'],
                'status' => 'pendiente',
                'pickup_at' => $validated['pickup_at'],
                'due_at' => $validated['due_at'],
                'observations' => $validated['observations'],
            ]);

            foreach ($items as $itemData) {
                LoanItem::create([
                    'loan_request_id' => $loan->id,
                    'resource_id' => $itemData['id'],
                    'quantity' => $itemData['quantity'],
                ]);
            }
        });

        return redirect()->route('loans.index')->with('status', 'Solicitud enviada correctamente.');
    }

    public function show(string $id) {}
}