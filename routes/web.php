<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\RabProposalController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes (Breeze akan generate ini, tapi sediakan placeholder)
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        return redirect()->route($role . '.dashboard');
    })->name('dashboard');

    // === PENGUSUL ===
    Route::middleware([CheckRole::class . ':pengusul'])
        ->prefix('pengusul')->name('pengusul.')->group(function () {
            Route::get('/dashboard', fn() => view('dashboard.pengusul'))->name('dashboard');
            Route::get('/rab', [RabProposalController::class, 'index'])->name('rab.index');
            Route::get('/rab/create', [RabProposalController::class, 'create'])->name('rab.create');
            Route::post('/rab', [RabProposalController::class, 'store'])->name('rab.store');
            Route::get('/rab/{id}', [RabProposalController::class, 'show'])->name('rab.show');
        });

    // === KAPRODI ===
    Route::middleware([CheckRole::class . ':kaprodi'])
        ->prefix('kaprodi')->name('kaprodi.')->group(function () {
            Route::get('/dashboard', [VerificationController::class, 'indexKaprodi'])->name('dashboard');
            Route::get('/rab', [VerificationController::class, 'indexKaprodi'])->name('rab.index');
            Route::get('/rab/{id}', [RabProposalController::class, 'show'])->name('rab.show');
            Route::post('/rab/{id}/verify', [VerificationController::class, 'verify'])->name('rab.verify');
            Route::post('/rab/{id}/revisi', [VerificationController::class, 'verify'])->name('rab.revisi');
        });

    // === WD KEUANGAN ===
    Route::middleware([CheckRole::class . ':wd_keuangan'])
        ->prefix('wd')->name('wd.')->group(function () {
            Route::get('/dashboard', [VerificationController::class, 'indexWd'])->name('dashboard');
            Route::get('/rab', [VerificationController::class, 'indexWd'])->name('rab.index');
            Route::get('/rab/{id}', [RabProposalController::class, 'show'])->name('rab.show');
            Route::post('/rab/{id}/verify', [VerificationController::class, 'verify'])->name('rab.verify');
            Route::post('/rab/{id}/tolak', [VerificationController::class, 'verify'])->name('rab.tolak');
        });

    // === DEKAN ===
    Route::middleware([CheckRole::class . ':dekan'])
        ->prefix('dekan')->name('dekan.')->group(function () {
            Route::get('/dashboard', [VerificationController::class, 'indexDekan'])->name('dashboard');
            Route::get('/rab', [VerificationController::class, 'indexDekan'])->name('rab.index');
            Route::get('/rab/{id}', [RabProposalController::class, 'show'])->name('rab.show');
            Route::post('/rab/{id}/setujui', [VerificationController::class, 'verify'])->name('rab.setujui');
        });

    // === TATA USAHA ===
    Route::middleware([CheckRole::class . ':tata_usaha'])
        ->prefix('tu')->name('tu.')->group(function () {
            Route::get('/dashboard', fn() => view('dashboard.tu'))->name('dashboard');
            Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
            Route::get('/laporan/export-pdf', [ReportController::class, 'exportPdf'])->name('laporan.pdf');
            Route::get('/laporan/export-excel', [ReportController::class, 'exportExcel'])->name('laporan.excel');
            Route::get('/aset', fn() => view('aset.index'))->name('aset.index');
        });

    // === SHARED (semua role) ===
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
});
