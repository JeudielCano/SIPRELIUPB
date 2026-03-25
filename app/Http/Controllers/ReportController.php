<?php

namespace App\Http\Controllers;

use App\Models\LoanRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Importamos la librería de PDF

class ReportController extends Controller
{
    /**
     * Muestra la lista de todos los préstamos FINALIZADOS (Historial).
     */
    public function index()
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        $finishedLoans = LoanRequest::with(['user', 'activityType', 'subject'])
                            ->where('status', 'finalizado')
                            ->orderBy('return_at', 'desc') // Los más recientes primero
                            ->get();

        return view('admin.reports.index', compact('finishedLoans'));
    }

    /**
     * Muestra el reporte detallado en pantalla (Vista Web).
     */
    public function show(LoanRequest $loan)
    {
        if (auth()->user()->role !== 'administrador') abort(403);
        
        // Validar que esté finalizado
        if ($loan->status !== 'finalizado') {
            return back()->withErrors(['status' => 'Este préstamo aún no ha finalizado.']);
        }

        $loan->load(['user', 'activityType', 'subject', 'items.resource', 'approver']);

        return view('admin.reports.show', compact('loan'));
    }

    /**
     * Genera y descarga el PDF del reporte.
     */
    public function downloadPdf(LoanRequest $loan)
    {
        if (auth()->user()->role !== 'administrador') abort(403);

        if ($loan->status !== 'finalizado') {
            return back()->withErrors(['status' => 'Solo se pueden generar reportes de préstamos finalizados.']);
        }

        $loan->load(['user', 'activityType', 'subject', 'items.resource', 'approver']);


        // --- 1. PARA EL LOGOTIPO ---
        $logoPath = public_path('images/logo2-upb.png');
        $logoBase64 = null;

        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoBase64 = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
        }
        // --- PARA EL LOGOTIPO ---

        // Cargamos la vista especial para PDF
        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'loan',
            'logoBase64' // <-- Importante pasar esta variable para generar el logo
            ));

        // Descargamos el archivo con un nombre descriptivo
        return $pdf->download('Reporte_Prestamo_UPB_' . $loan->id . '.pdf');
    }
}