<?php

namespace App\Http\Controllers;

use App\Models\ResourceGuardian;
use App\Models\LoanRequest;
use App\Models\LoanItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class GuardianController extends Controller
{
    // Verifica que el usuario sea docente con recursos asignados
    private function checkAccess()
    {
        $user = auth()->user();
        if ($user->applicant_type !== 'docente' || $user->role !== 'solicitante') {
            abort(403);
        }
    }
    
    private function checkLoanAccess(LoanRequest $loan)
    {
        $assignedResourceIds = ResourceGuardian::where('user_id', auth()->id())
            ->pluck('resource_id')
            ->toArray();

        $hasAccess = $loan->items->pluck('resource_id')
            ->intersect($assignedResourceIds)
            ->isNotEmpty();

        if (!$hasAccess) abort(403);
    }

    // Panel principal del subresguardante
    public function index()
    {
        $this->checkAccess();
        $user = auth()->user();

        // Recursos asignados a este docente
        $assignedResources = ResourceGuardian::where('user_id', $user->id)
            ->with('resource')
            ->get();

        // Solicitudes pendientes que involucren SUS recursos
        $assignedResourceIds = $assignedResources->pluck('resource_id')->toArray();

        $pendingLoans = collect();
        $activeLoans  = collect();

        if (!empty($assignedResourceIds)) {
            $pendingLoans = LoanRequest::whereHas('items', function ($q) use ($assignedResourceIds) {
                    $q->whereIn('resource_id', $assignedResourceIds);
                })
                ->where('status', 'pendiente')
                ->with(['user', 'items.resource'])
                ->orderBy('created_at', 'desc')
                ->get();

            $activeLoans = LoanRequest::whereHas('items', function ($q) use ($assignedResourceIds) {
                    $q->whereIn('resource_id', $assignedResourceIds);
                })
                ->whereIn('status', ['aprobado', 'activo'])
                ->with(['user', 'items.resource'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('guardian.index', compact(
            'assignedResources',
            'pendingLoans',
            'activeLoans'
        ));
    }

    // Detalle de una solicitud
    public function show(LoanRequest $loan)
    {
        $this->checkAccess();
        $user = auth()->user();

        // Verificar que la solicitud involucre al menos uno de sus recursos
        $assignedResourceIds = ResourceGuardian::where('user_id', $user->id)
            ->pluck('resource_id')
            ->toArray();

        $hasAccess = $loan->items->pluck('resource_id')
            ->intersect($assignedResourceIds)
            ->isNotEmpty();

        if (!$hasAccess) abort(403);

        $loan->load(['user', 'items.resource']);

        return view('guardian.show', compact('loan'));
    }

    public function approve(Request $request, LoanRequest $loan)
    {
        $this->checkAccess();
        $this->checkLoanAccess($loan);

        if ($loan->status !== 'pendiente') {
            return back()->withErrors(['status' => 'Solo se pueden aprobar solicitudes pendientes.']);
        }

        // Validación de stock
        foreach ($loan->items as $item) {
            $resource = $item->resource;

            $reservedCount = LoanItem::where('resource_id', $resource->id)
                ->whereHas('loanRequest', function ($q) use ($loan) {
                    $q->whereIn('status', ['aprobado', 'activo'])
                    ->where('id', '!=', $loan->id);
                })
                ->sum('quantity');

            $available = $resource->total_stock - $reservedCount;

            if ($available < $item->quantity) {
                return back()->withErrors([
                    'status' => "No hay stock suficiente para '{$resource->name}'. Disponibles: {$available}, Solicitados: {$item->quantity}"
                ]);
            }
        }

        $code = strtoupper(\Illuminate\Support\Str::random(6));

        $loan->update([
            'status'         => 'aprobado',
            'approved_by_id' => auth()->id(),
            'pickup_code'    => $code,
        ]);

        $loan->user->notify(new \App\Notifications\LoanApproved($loan));

        return back()->with('status', 'Solicitud aprobada. Código de retiro: ' . $code);
    }

    public function reject(Request $request, LoanRequest $loan)
    {
        $this->checkAccess();
        $this->checkLoanAccess($loan);

        $request->validate([
            'reason' => 'nullable|string|max:300',
        ]);

        if ($loan->status !== 'pendiente') {
            return back()->withErrors(['status' => 'Solo se pueden rechazar solicitudes pendientes.']);
        }

        $loan->update([
            'status'         => 'rechazado',
            'approved_by_id' => auth()->id(),
        ]);

        $reason = $request->reason ?? 'No se especificó un motivo.';
        $loan->user->notify(new \App\Notifications\LoanRejected($loan, $reason));

        return back()->with('status', 'Solicitud rechazada.');
    }

    public function deliver(Request $request, LoanRequest $loan)
    {
        $this->checkAccess();
        $this->checkLoanAccess($loan);

        if ($loan->status !== 'aprobado') {
            return back()->withErrors(['status' => 'Solo se pueden entregar solicitudes aprobadas.']);
        }

        $loan->update(['status' => 'activo']);

        return back()->with('status', 'Material entregado. Préstamo activo.');
    }

    // Vista de reportes
    public function reports()
    {
        $this->checkAccess();
        $user = auth()->user();

        $assignedResourceIds = ResourceGuardian::where('user_id', $user->id)
            ->pluck('resource_id')
            ->toArray();

        // Historial completo de préstamos de sus recursos
        $loans = LoanRequest::whereHas('items', function ($q) use ($assignedResourceIds) {
                $q->whereIn('resource_id', $assignedResourceIds);
            })
            ->whereIn('status', ['finalizado', 'rechazado', 'activo', 'aprobado'])
            ->with(['user', 'items.resource'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Estadísticas rápidas
        $stats = [
            'total'      => $loans->count(),
            'finalizados'=> $loans->where('status', 'finalizado')->count(),
            'activos'    => $loans->whereIn('status', ['activo', 'aprobado'])->count(),
            'rechazados' => $loans->where('status', 'rechazado')->count(),
        ];

        return view('guardian.reports', compact('loans', 'stats'));
    }

    // Descarga el PDF
    public function downloadReport()
    {
        $this->checkAccess();
        $user = auth()->user();

        $assignedResourceIds = ResourceGuardian::where('user_id', $user->id)
            ->pluck('resource_id')
            ->toArray();

        $loans = LoanRequest::whereHas('items', function ($q) use ($assignedResourceIds) {
                $q->whereIn('resource_id', $assignedResourceIds);
            })
            ->whereIn('status', ['finalizado', 'rechazado', 'activo', 'aprobado'])
            ->with(['user', 'items.resource'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $stats = [
            'total'      => $loans->count(),
            'finalizados'=> $loans->where('status', 'finalizado')->count(),
            'activos'    => $loans->whereIn('status', ['activo', 'aprobado'])->count(),
            'rechazados' => $loans->where('status', 'rechazado')->count(),
        ];

        $guardianName = $user->name;
        $fecha = now()->format('d/m/Y H:i');

        // --- 1. PARA EL LOGOTIPO ---
        $logoPath = public_path('images/logo2-upb.png');
        $logoBase64 = null;

        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoBase64 = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
        }
        // --- PARA EL LOGOTIPO ---

        // --- 3. GENERACIÓN DEL PDF (Añadimos logoBase64 al compact) ---
        $pdf = Pdf::loadView('guardian.report_pdf', compact(
                'loans', 
                'stats', 
                'guardianName', 
                'fecha', 
                'logoBase64' // <-- Importante pasar esta variable para generar el logo
            ))
            ->setPaper('a4', 'landscape');

        return $pdf->download('reporte-subresguardo-' . now()->format('Y-m-d') . '.pdf');
    }


}