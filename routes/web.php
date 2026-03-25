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
use App\Http\Controllers\AdminGuardianController;
// para que funcione modificar asignaturas.
use App\Http\Controllers\AdminSubjectController;
// para que funcione modificar carrreras
use App\Http\Controllers\AdminCareerController;
// para que funcione modificar el tipo de actividad
use App\Http\Controllers\AdminActivityTypeController;



// Subresguardantes 
use App\Http\Controllers\GuardianController;

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
    //Para que se genere el PDF en solicitud de prestamo externo
    Route::post('/loans/external/permit', [ExternalLoanController::class, 'downloadPermit'])->name('loans.external.permit');
    // Para re-generar el PDF para prestamo externo en "mis solicitudes"
    Route::get('/loans/{loan}/permit', [ExternalLoanController::class, 'downloadPermitFromLoan'])->name('loans.permit.download')->middleware('auth');

    
    // 2. MÓDULO DE SOLICITUDES (Internas)
    Route::resource('loans', LoanRequestController::class)
        ->only(['index', 'create', 'store', 'show']);

    // 3. MÓDULO DE REGLAMENTOS
    Route::get('/reglamentos', [RegulationController::class, 'index'])->name('regulations.index');
    Route::get('/reglamentos/descargar/{filename}', [RegulationController::class, 'download'])->name('regulations.download');

    // 4. CATÁLOGO DE RECURSOS (Solicitantes)  
    // Esta ruta apunta al método index del CatalogController
    Route::get('/catalog', [App\Http\Controllers\CatalogController::class, 'index'])->name('catalog.index');


    // Subresguardante (perfil solicitante con rol docente)

    Route::prefix('guardian')->middleware('auth')->group(function () {
    Route::get('/', [GuardianController::class, 'index'])->name('guardian.index');
    Route::get('/{loan}', [GuardianController::class, 'show'])->name('guardian.show');
    Route::patch('/{loan}/approve', [GuardianController::class, 'approve'])->name('guardian.approve');
    Route::patch('/{loan}/reject', [GuardianController::class, 'reject'])->name('guardian.reject');
    Route::patch('/{loan}/deliver', [GuardianController::class, 'deliver'])->name('guardian.deliver');

    Route::prefix('guardian')->middleware('auth')->group(function () {
        Route::get('/', [GuardianController::class, 'index'])->name('guardian.index');
        Route::get('/reports', [GuardianController::class, 'reports'])->name('guardian.reports');
        Route::get('/reports/download', [GuardianController::class, 'downloadReport'])->name('guardian.reports.download');
        Route::get('/{loan}', [GuardianController::class, 'show'])->name('guardian.show');
        Route::patch('/{loan}/approve', [GuardianController::class, 'approve'])->name('guardian.approve');
        Route::patch('/{loan}/reject', [GuardianController::class, 'reject'])->name('guardian.reject');
        Route::patch('/{loan}/deliver', [GuardianController::class, 'deliver'])->name('guardian.deliver');
    });

});









    // --- SECCIÓN DE RUTAS DE ADMINISTRACIÓN ---

    // Panel de Administración
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Gestión de Usuarios
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users.index');
    Route::patch('/admin/users/{user}/approve', [AdminController::class, 'approve'])->name('admin.users.approve');
    Route::delete('/admin/users/{user}', [AdminController::class, 'reject'])->name('admin.users.reject');

    // Gestión de Inventario

    // Para generar pdf de inventarios
    Route::get('admin/resources/download-inventory', [ResourceController::class, 'downloadInventory'])
     ->name('admin.resources.download-inventory');
    
    // Para poder descargar una lista de los recursos dados de baja.
    Route::get('admin/resources/download-bajas', [ResourceController::class, 'downloadBajas'])
     ->name('admin.resources.download-bajas');

    
    Route::resource('resources', ResourceController::class);

    // Rotas para el manejo de los recursos (altas, bajas y recuperar)
    Route::resource('/admin/resources', ResourceController::class)->names('admin.resources');
    Route::patch('/admin/resources/{resource}/disable', [ResourceController::class, 'disable'])->name('admin.resources.disable')->middleware('auth');
    Route::patch('/admin/resources/{resource}/recover', [ResourceController::class, 'recover'])->name('admin.resources.recover')->middleware('auth');



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

    //notificaciones
    Route::get('/notifications', function () {
        $notifications = auth()->user()->notifications()->paginate(10);
        auth()->user()->unreadNotifications->markAsRead();
        return view('notifications.index', compact('notifications'));
    })->middleware('auth')->name('notifications.index');
    
    Route::delete('/notifications', function () {
    auth()->user()->notifications()->delete();
    return back()->with('status', 'Notificaciones eliminadas.');
    })->middleware('auth')->name('notifications.clear');

    // Carreras
    Route::prefix('admin')->middleware('auth')->group(function () {
        Route::get('/careers', [AdminCareerController::class, 'index'])->name('admin.careers.index');
        Route::get('/careers/create', [AdminCareerController::class, 'create'])->name('admin.careers.create');
        Route::post('/careers', [AdminCareerController::class, 'store'])->name('admin.careers.store');
        Route::get('/careers/{career}/edit', [AdminCareerController::class, 'edit'])->name('admin.careers.edit');
        Route::patch('/careers/{career}', [AdminCareerController::class, 'update'])->name('admin.careers.update');
        Route::delete('/careers/{career}', [AdminCareerController::class, 'destroy'])->name('admin.careers.destroy');
    });

    // Asignaturas
    Route::prefix('admin')->middleware('auth')->group(function () {
        Route::get('/subjects', [AdminSubjectController::class, 'index'])->name('admin.subjects.index');
        Route::get('/subjects/create', [AdminSubjectController::class, 'create'])->name('admin.subjects.create');
        Route::post('/subjects', [AdminSubjectController::class, 'store'])->name('admin.subjects.store');
        Route::get('/subjects/{subject}/edit', [AdminSubjectController::class, 'edit'])->name('admin.subjects.edit');
        Route::patch('/subjects/{subject}', [AdminSubjectController::class, 'update'])->name('admin.subjects.update');
        Route::delete('/subjects/{subject}', [AdminSubjectController::class, 'destroy'])->name('admin.subjects.destroy');
    });

    // Tipos de actividad
    Route::prefix('admin')->middleware('auth')->group(function () {
        Route::get('/activity-types', [AdminActivityTypeController::class, 'index'])->name('admin.activity_types.index');
        Route::get('/activity-types/create', [AdminActivityTypeController::class, 'create'])->name('admin.activity_types.create');
        Route::post('/activity-types', [AdminActivityTypeController::class, 'store'])->name('admin.activity_types.store');
        Route::get('/activity-types/{activityType}/edit', [AdminActivityTypeController::class, 'edit'])->name('admin.activity_types.edit');
        Route::patch('/activity-types/{activityType}', [AdminActivityTypeController::class, 'update'])->name('admin.activity_types.update');
        Route::delete('/activity-types/{activityType}', [AdminActivityTypeController::class, 'destroy'])->name('admin.activity_types.destroy');
    });

    // Subresguardantes (Admin)
    Route::prefix('admin')->middleware('auth')->group(function () {
        Route::get('/guardians', [AdminGuardianController::class, 'index'])->name('admin.guardians.index');
        Route::get('/guardians/{user}/edit', [AdminGuardianController::class, 'edit'])->name('admin.guardians.edit');
        Route::patch('/guardians/{user}', [AdminGuardianController::class, 'update'])->name('admin.guardians.update');
    });






});

require __DIR__.'/auth.php';