<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\MasterDiesetController;
use App\Http\Controllers\MasterPartController;
use App\Http\Controllers\MasterInspectionController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\DiesetStatusController;
use App\Http\Controllers\PartsStockController; // ANDREW FIX: Controller baru untuk Parts Stock
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    
    // =========================================================
    // DASHBOARD & PROFILE ROUTES
    // =========================================================
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // =========================================================
    // DIESET STATUS ROUTES (Sesuai PDF Hal 9-11)
    // Akses terbuka untuk semua role yang sudah login
    // =========================================================
    Route::get('/dieset-status', [DiesetStatusController::class, 'index'])->name('dieset-status.index');
    Route::get('/dieset-status/export',[DiesetStatusController::class, 'export'])->name('dieset-status.export');
    Route::get('/dieset-status/{id}', [DiesetStatusController::class, 'show'])->name('dieset-status.show');

    // =========================================================
    // ADMINISTRATOR ROUTES (Master Data & Logs)
    // =========================================================
    Route::middleware([RoleMiddleware::class . ':Admin'])->group(function () {
        Route::resource('master-diesets', MasterDiesetController::class);
        Route::resource('master-parts', MasterPartController::class); 
        Route::resource('master-inspections', MasterInspectionController::class); 
        Route::resource('audit-logs', AuditLogController::class)->only(['index', 'show']);
    });

    // =========================================================
    // SUPERVISOR & ADMIN ROUTES (Monitoring, Export, Parts Stock)
    // =========================================================
    Route::middleware([RoleMiddleware::class . ':Admin,Supervisor'])->group(function () {
        // PDF Hal 12-14: Monitoring & Export
        Route::get('monitoring', [InspectionController::class, 'monitoring'])->name('monitoring');
        Route::get('monitoring/{id}', [InspectionController::class, 'showMonitoring'])->name('monitoring.show');
        Route::get('export', [InspectionController::class, 'export'])->name('export');
        
        // PDF Hal 15-17: Parts Stock & Mail to SPV
        Route::get('/parts-stock', [PartsStockController::class, 'index'])->name('parts-stock.index');
        Route::post('/parts-stock/mail-spv',[PartsStockController::class, 'mailToSpv'])->name('parts-stock.mail');
    });

    // =========================================================
    // MAINTENANCE ROUTES (CRUD Inspeksi - Update/Delete)
    // =========================================================
    Route::middleware([RoleMiddleware::class . ':Admin,Maintenance'])->group(function () {
        Route::resource('inspections', InspectionController::class)->except(['index', 'show', 'create', 'store']);
    });

    // =========================================================
    // OPERATOR ROUTES (Hanya Input Inspeksi)
    // =========================================================
    Route::middleware([RoleMiddleware::class . ':Admin,Operator,Maintenance'])->group(function () {
        Route::resource('inspections', InspectionController::class)->only(['create', 'store']);
    });

    // =========================================================
    // COMMON ROUTES (View Inspeksi - Bisa diakses semua role yang berhak)
    // =========================================================
    Route::get('inspections', [InspectionController::class, 'index'])->name('inspections.index');
    Route::get('inspections/{inspection}', [InspectionController::class, 'show'])->name('inspections.show');
});

require __DIR__.'/auth.php';