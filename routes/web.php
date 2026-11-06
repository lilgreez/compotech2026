<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\MasterDiesetController;
use App\Http\Controllers\MasterPartController;
use App\Http\Controllers\MasterInspectionController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\DiesetStatusController;
use App\Http\Controllers\PartsStockController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\EmailReportController;
use App\Http\Controllers\RbacController;
use App\Http\Controllers\MoldRequestController;
use App\Http\Controllers\RepairTicketController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    
    // =========================================================
    // MODUL 1 & 2: DASHBOARD & PROFILE ROUTES (Semua Role)
    // =========================================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // =========================================================
    // MODUL 3: MOLD STATUS (MONITORING STOK) (Semua Role)
    // =========================================================
    Route::get('/mold-status', [DiesetStatusController::class, 'index'])->name('dieset-status.index');
    Route::get('/mold-status/export', [DiesetStatusController::class, 'export'])->name('dieset-status.export');
    Route::get('/mold-status/{id}', [DiesetStatusController::class, 'show'])->name('dieset-status.show');

    // Redirect lama dieset-status -> mold-status
    Route::get('/dieset-status', fn() => redirect()->route('dieset-status.index'));
    Route::get('/dieset-status/export', fn() => redirect()->route('dieset-status.export'));
    Route::get('/dieset-status/{id}', fn($id) => redirect()->route('dieset-status.show', $id));

    // =========================================================
    // MODUL 4: INSPECTION MONITOR (ADM, SPV, MNT, OPR)
    // =========================================================
    Route::middleware([RoleMiddleware::class . ':Admin,Supervisor,Maintenance,Operator'])->group(function () {
        Route::get('monitoring', [InspectionController::class, 'monitoring'])->name('monitoring');
        Route::get('monitoring/{id}', [InspectionController::class, 'showMonitoring'])->name('monitoring.show');
        Route::get('export', [InspectionController::class, 'export'])->name('export');
        Route::resource('inspections', InspectionController::class);
    });

    // =========================================================
    // MODUL 5: PARTS STOCK & EMAIL NOTIFIKASI (ADM, SPV)
    // =========================================================
    Route::middleware([RoleMiddleware::class . ':Admin,Supervisor'])->group(function () {
        Route::get('/parts-stock', [PartsStockController::class, 'index'])->name('parts-stock.index');
        Route::get('/parts-stock/export', [PartsStockController::class, 'exportExcel'])->name('parts-stock.export');
        Route::post('/parts-stock/mail-spv', [PartsStockController::class, 'mailToSpv'])->name('parts-stock.mail');
        Route::post('/parts-stock/{id}/adjust', [PartsStockController::class, 'adjustStock'])->name('parts-stock.adjust');
    });

    // =========================================================
    // MODUL 9: PERMINTAAN MOLD BARU (SPK & PROGRESS TRACKING)
    // Akses: ADM, SPV, MNT, PPC
    // =========================================================
    Route::middleware([RoleMiddleware::class . ':Admin,Supervisor,Maintenance,PPIC'])->group(function () {
        Route::get('mold-requests', [MoldRequestController::class, 'index'])->name('mold-requests.index');
        Route::get('mold-requests/{id}', [MoldRequestController::class, 'show'])->name('mold-requests.show');
    });

    Route::middleware([RoleMiddleware::class . ':Admin,Supervisor'])->group(function () {
        Route::post('mold-requests', [MoldRequestController::class, 'store'])->name('mold-requests.store');
        Route::delete('mold-requests/{id}', [MoldRequestController::class, 'destroy'])->name('mold-requests.destroy');
    });

    Route::middleware([RoleMiddleware::class . ':Admin,Supervisor,Maintenance'])->group(function () {
        Route::put('mold-requests/{id}/stages/{stageId}', [MoldRequestController::class, 'updateStage'])->name('mold-requests.stages.update');
        Route::post('mold-requests/{id}/materials', [MoldRequestController::class, 'storeMaterial'])->name('mold-requests.materials.store');
        Route::delete('mold-requests/{id}/materials/{materialId}', [MoldRequestController::class, 'destroyMaterial'])->name('mold-requests.materials.destroy');
    });

    // =========================================================
    // MODUL 10: TIKET PERBAIKAN MOLD (REPAIR TICKETING)
    // Akses: ADM, SPV, MNT, OPR
    // =========================================================
    Route::middleware([RoleMiddleware::class . ':Admin,Supervisor,Maintenance,Operator'])->group(function () {
        Route::get('repair-tickets', [RepairTicketController::class, 'index'])->name('repair-tickets.index');
        Route::post('repair-tickets', [RepairTicketController::class, 'store'])->name('repair-tickets.store');
        Route::get('repair-tickets/{id}', [RepairTicketController::class, 'show'])->name('repair-tickets.show');
    });

    Route::middleware([RoleMiddleware::class . ':Admin,Supervisor,Maintenance'])->group(function () {
        Route::post('repair-tickets/{id}/assign', [RepairTicketController::class, 'assignTechnician'])->name('repair-tickets.assign');
        Route::post('repair-tickets/{id}/progress', [RepairTicketController::class, 'updateProgress'])->name('repair-tickets.update-progress');
        Route::delete('repair-tickets/{id}', [RepairTicketController::class, 'destroy'])->name('repair-tickets.destroy');
    });

    // =========================================================
    // MODUL 6, 7, 8: ADMINISTRATOR & MASTER DATA (Khusus Admin)
    // =========================================================
    Route::middleware([RoleMiddleware::class . ':Admin'])->group(function () {
        
        // --- Master Mold ---
        Route::resource('master-molds', MasterDiesetController::class, ['names' => 'master-diesets']);
        Route::resource('master-diesets', MasterDiesetController::class);
        Route::post('/master-diesets/{id}/generate-parts', [MasterDiesetController::class, 'storeGeneratedParts'])->name('master-diesets.generate-parts');
        Route::put('/master-diesets/{dieset_id}/parts/{part_id}', [MasterDiesetController::class, 'updatePart'])->name('master-diesets.update-part');
        Route::delete('/master-diesets/{dieset_id}/parts/{part_id}', [MasterDiesetController::class, 'destroyPart'])->name('master-diesets.destroy-part');

        // --- Master Parts ---
        Route::post('/master-parts/sync-wings', [MasterPartController::class, 'syncWings'])->name('master-parts.sync');
        Route::post('/master-parts/import-excel', [MasterPartController::class, 'importExcel'])->name('master-parts.import-excel');
        Route::resource('master-parts', MasterPartController::class); 

        // --- Master Inspections & Logs ---
        Route::resource('master-inspections', MasterInspectionController::class); 
        Route::resource('audit-logs', AuditLogController::class)->only(['index', 'show']);

        // --- Administrator Settings ---
        Route::resource('operators', OperatorController::class)->except(['create', 'show', 'edit']);
        Route::resource('email-reports', EmailReportController::class)->except(['create', 'show', 'edit']);

        // --- RBAC (Role-Based Access Control) ---
        Route::get('rbac', [RbacController::class, 'index'])->name('rbac.index');
        Route::post('rbac/roles', [RbacController::class, 'storeRole'])->name('rbac.roles.store');
        Route::put('rbac/roles/{id}', [RbacController::class, 'updateRole'])->name('rbac.roles.update');
        Route::delete('rbac/roles/{id}', [RbacController::class, 'destroyRole'])->name('rbac.roles.destroy');
        Route::put('rbac/users/{id}/roles', [RbacController::class, 'updateUserRole'])->name('rbac.users.update-role');
        Route::post('rbac/matrix', [RbacController::class, 'syncMatrix'])->name('rbac.matrix.sync');
    });

});

require __DIR__.'/auth.php';