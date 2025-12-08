<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\LoanRequestController;
use App\Http\Controllers\AdminLoanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RegulationController;
use App\Http\Controllers\ExternalLoanController; 

Route::get('/', function () {
    return view('welcome');
});

// Dashboard del Solicitante (usuario normal)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- GRUPO DE RUTAS PROTEGIDAS (requieren login) ---

// --- SECCIÓN DE RUTAS DEl SOLICITANTE  ---

Route::middleware('auth')->group(function () {
    
    // Rutas de Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Colocamos las rutas ESPECÍFICAS (/loans/external) ANTES de las genéricas (/loans/{id})
    // para evitar que "external" sea confundido con un ID de préstamo.

    // 1. MÓDULO DE PRÉSTAMO EXTERNO
    Route::get('/loans/external', [ExternalLoanController::class, 'create'])->name('loans.external.create');
    Route::post('/loans/external', [ExternalLoanController::class, 'store'])->name('loans.external.store');
    Route::get('/loans/external/permit', [ExternalLoanController::class, 'downloadPermit'])->name('loans.external.permit');

    // 2. MÓDULO DE SOLICITUDES (Internas)
    Route::resource('loans', LoanRequestController::class)
        ->only(['index', 'create', 'store', 'show']);

    // 3. MÓDULO DE REGLAMENTOS
    Route::get('/regulations', [RegulationController::class, 'index'])->name('regulations.index');
    Route::get('/regulations/download/{filename}', [RegulationController::class, 'download'])->name('regulations.download');

    // 4. CATÁLOGO DE RECURSOS (Solicitantes)  
    // Esta ruta apunta al método index del CatalogController
    Route::get('/catalog', [App\Http\Controllers\CatalogController::class, 'index'])->name('catalog.index');









    // --- SECCIÓN DE RUTAS DE ADMINISTRACIÓN ---

    // Panel de Administración
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Gestión de Usuarios
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users.index');
    Route::patch('/admin/users/{user}/approve', [AdminController::class, 'approve'])->name('admin.users.approve');
    Route::delete('/admin/users/{user}', [AdminController::class, 'reject'])->name('admin.users.reject');

    // Gestión de Inventario
    Route::resource('/admin/resources', ResourceController::class)->names('admin.resources');

    // Gestión de Préstamos (Admin)
    Route::get('/admin/loans', [AdminLoanController::class, 'index'])->name('admin.loans.index'); // Bandeja Normal
    
    // Bandeja de Préstamos Externos
    Route::get('/admin/external-loans', [AdminLoanController::class, 'external'])->name('admin.loans.external');

    Route::get('/admin/active-loans', [AdminLoanController::class, 'activeLoans'])->name('admin.active-loans');

    // Detalles y Acciones de Préstamos
    Route::get('/admin/loans/{loan}', [AdminLoanController::class, 'show'])->name('admin.loans.show');
    Route::patch('/admin/loans/{loan}/approve', [AdminLoanController::class, 'approve'])->name('admin.loans.approve');
    Route::patch('/admin/loans/{loan}/reject', [AdminLoanController::class, 'reject'])->name('admin.loans.reject');
    Route::patch('/admin/loans/{loan}/deliver', [AdminLoanController::class, 'deliver'])->name('admin.loans.deliver');

    // Devolución
    Route::get('/admin/loans/{loan}/return', [AdminLoanController::class, 'returnForm'])->name('admin.loans.return');
    Route::post('/admin/loans/{loan}/return', [AdminLoanController::class, 'processReturn'])->name('admin.loans.process_return');

    // Reportes
    Route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/admin/reports/{loan}', [ReportController::class, 'show'])->name('admin.reports.show');
    Route::get('/admin/reports/{loan}/pdf', [ReportController::class, 'downloadPdf'])->name('admin.reports.pdf');

});

require __DIR__.'/auth.php';