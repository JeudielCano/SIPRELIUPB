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
use App\Models\User;
use App\Notifications\NewLoanRequest;


class ExternalLoanController extends Controller
{
    /**
     * Muestra el formulario de solicitud externa.
     */
public function create()
    {
        $activityTypes = ActivityType::all();
        $subjects = Subject::all();

        // Consulta base de recursos disponibles
        $query = Resource::where('status', 'disponible');

        // Regla: Alumnos no ven laboratorios (igual que en préstamo normal)
        if (auth()->user()->applicant_type === 'alumno') {
            $query->where('type', '!=', 'laboratorio');
        }

        $resources = $query->get();

        // Filtro de stock real
        $resources = $resources->map(function ($resource) {
            $reservedCount = LoanItem::where('resource_id', $resource->id)
                ->whereHas('loanRequest', function ($q) {
                    $q->whereIn('status', ['aprobado', 'activo']);
                })
                ->sum('quantity');
            
            // Calculamos el stock real
            $resource->total_stock = max(0, $resource->total_stock - $reservedCount);
            
            return $resource;
        }); 
        // CORRECCIÓN AQUÍ: 
        // Eliminé "->filter(fn($r) => $r->total_stock > 0)"
        // Ahora pasamos TODOS los recursos, incluso si el stock es 0.
        // Solo agregamos ->values() para reordenar los índices del array si fuera necesario.
        
        $resources = $resources->values(); 

        return view('loans.external', compact('activityTypes', 'subjects', 'resources'));
    }

    /**
     * Guarda la solicitud externa con validación de 4 días.
     */
    public function store(Request $request)
    {
        $items = json_decode($request->selected_items, true);
        $request->merge(['items_array' => $items]);

        $messages = [
            // ... (mismos mensajes que LoanRequestController) ...
            'items_array.required' => 'Debes agregar al menos un recurso.',
        ];

        $validated = $request->validate([
            'activity_type_id' => 'required|exists:activity_types,id',
            'subject_id'       => 'required|exists:subjects,id',
            'pickup_at'        => 'required|date|after:now',
            
            // VALIDACIÓN CLAVE: Fecha de entrega
            'due_at' => [
                'required',
                'date',
                'after:pickup_at',
                function ($attribute, $value, $fail) use ($request) {
                    $pickup = Carbon::parse($request->pickup_at);
                    $due = Carbon::parse($value);
                    
                    // Calculamos la diferencia en días
                    if ($pickup->diffInDays($due) > 4) {
                        $fail('El préstamo externo no puede exceder los 4 días de duración.');
                    }
                },
            ],
            
            'observations'     => 'nullable|string|max:500',
            'items_array'      => 'required|array|min:1',
            'items_array.*.id' => 'required|exists:resources,id',
            'items_array.*.quantity' => 'required|integer|min:1',
        ], $messages);

        // Regla de seguridad para alumnos (Laboratorios)
        if (auth()->user()->applicant_type === 'alumno') {
            foreach ($items as $itemData) {
                $resource = Resource::find($itemData['id']);
                if ($resource && $resource->type === 'laboratorio') {
                    return back()->withErrors(['items_array' => 'No puedes solicitar laboratorios.'])->withInput();
                }
                
                // Validación de stock al momento de guardar
                // ... (puedes copiar la lógica de LoanRequestController aquí si deseas ser estricto)
            }
        }

        $userId = auth()->id();

        DB::transaction(function () use ($validated, $items) {
            // Creamos la solicitud con un indicador especial o simplemente por el contexto
            // Nota: Podrías agregar un campo 'type' a loan_requests si necesitas distinguir interno/externo en BD,
            // pero por ahora usaremos la estructura estándar.
            
            $loan = LoanRequest::create([
                'user_id'          => auth()->id(),
                'activity_type_id' => $validated['activity_type_id'],
                'subject_id'       => $validated['subject_id'],
                'status'           => 'pendiente',
                'pickup_at'        => $validated['pickup_at'],
                'due_at'           => $validated['due_at'],
                'observations' => ($validated['observations'] ?? '') . ' (SOLICITUD EXTERNA)', // Marcamos en observaciones
            ]);

            foreach ($items as $itemData) {
                LoanItem::create([
                    'loan_request_id' => $loan->id,
                    'resource_id'     => $itemData['id'],
                    'quantity'        => $itemData['quantity'],
                ]);
            }

            // ← LÍNEA NUEVA: notificar a todos los administradores
            $admins = User::where('role', 'administrador')->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewLoanRequest($loan));
            }
        });

        return redirect()->route('loans.index')->with('status', 'Solicitud externa enviada. No olvides entregar tu permiso firmado.');
    }

    /**
     * Descarga el formato de permiso.
     */
    public function downloadPermit(Request $request)
    {
        $user = auth()->user();
        $fecha = now()->format('d/m/Y');

        // Datos del formulario
        $items      = json_decode($request->selected_items ?? '[]', true);
        $pickup_at  = $request->pickup_at  ? \Carbon\Carbon::parse($request->pickup_at)->format('d/m/Y H:i') : null;
        $due_at     = $request->due_at     ? \Carbon\Carbon::parse($request->due_at)->format('d/m/Y H:i') : null;
        $observations = $request->observations;

        // Resolvemos nombres de actividad y asignatura
        $activityType = $request->activity_type_id
            ? \App\Models\ActivityType::find($request->activity_type_id)?->name
            : null;

        $subject = $request->subject_id
            ? \App\Models\Subject::find($request->subject_id)?->name
            : null;

        // Resolvemos nombres de recursos
        $resources = [];
        foreach ($items as $item) {
            $resource = \App\Models\Resource::find($item['id']);
            if ($resource) {
                $resources[] = $resource->name . ' (x' . $item['quantity'] . ')';
            }
        }

        // --- 1. PARA EL LOGOTIPO ---
        $logoPath = public_path('images/logo2-upb.png');
        $logoBase64 = null;

        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoBase64 = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
        }
        // --- PARA EL LOGOTIPO ---

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('loans.permit_pdf', compact(
            'user', 'fecha', 'resources', 'pickup_at', 'due_at',
            'activityType', 'subject', 'observations',
            'logoBase64' // <-- Importante pasar esta variable para generar el logo
        ))->setPaper('letter', 'portrait');

        return $pdf->download('permiso-prestamo-externo-' . now()->format('Y-m-d') . '.pdf');
    }

    public function downloadPermitFromLoan(\App\Models\LoanRequest $loan)
    {
        // Solo el dueño del préstamo puede descargarlo
        if ($loan->user_id !== auth()->id()) abort(403);

        $user   = $loan->user;
        $fecha  = now()->format('d/m/Y');

        $resources = $loan->items->map(function ($item) {
            return $item->resource->name . ' (x' . $item->quantity . ')';
        })->toArray();

        $pickup_at    = $loan->pickup_at->format('d/m/Y H:i');
        $due_at       = $loan->due_at->format('d/m/Y H:i');
        $activityType = $loan->activityType->name;
        $subject      = $loan->subject->name;
        $observations = $loan->observations
            ? str_replace('(SOLICITUD EXTERNA)', '', $loan->observations)
            : null;

        // --- 1. PARA EL LOGOTIPO ---
        $logoPath = public_path('images/logo2-upb.png');
        $logoBase64 = null;

        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoBase64 = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
        }
        // --- PARA EL LOGOTIPO ---

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('loans.permit_pdf', compact(
            'user', 'fecha', 'resources', 'pickup_at', 'due_at',
            'activityType', 'subject', 'observations',
            'logoBase64' // <-- Importante pasar esta variable para generar el logo
        ))->setPaper('letter', 'portrait');

        return $pdf->download('permiso-prestamo-' . $loan->id . '.pdf');
    }
}