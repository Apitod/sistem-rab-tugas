<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;

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
            Route::get('/dashboard', fn() => 'TODO: Dashboard Pengusul')->name('dashboard');
            Route::get('/rab', fn() => 'TODO: List RAB')->name('rab.index');
            Route::get('/rab/create', fn() => 'TODO: Form RAB')->name('rab.create');
            Route::post('/rab', fn() => 'TODO: Store RAB')->name('rab.store');
            Route::get('/rab/{id}', fn($id) => 'TODO: Detail RAB')->name('rab.show');
        });

    // === KAPRODI ===
    Route::middleware([CheckRole::class . ':kaprodi'])
        ->prefix('kaprodi')->name('kaprodi.')->group(function () {
            Route::get('/dashboard', fn() => 'TODO: Dashboard Kaprodi')->name('dashboard');
            Route::get('/rab', fn() => 'TODO: List RAB pending')->name('rab.index');
            Route::get('/rab/{id}', fn($id) => 'TODO: Detail RAB')->name('rab.show');
            Route::post('/rab/{id}/verify', fn($id) => 'TODO: Verify')->name('rab.verify');
            Route::post('/rab/{id}/revisi', fn($id) => 'TODO: Minta Revisi')->name('rab.revisi');
        });

    // === WD KEUANGAN ===
    Route::middleware([CheckRole::class . ':wd_keuangan'])
        ->prefix('wd')->name('wd.')->group(function () {
            Route::get('/dashboard', fn() => 'TODO: Dashboard WD')->name('dashboard');
            Route::get('/rab', fn() => 'TODO: List RAB')->name('rab.index');
            Route::get('/rab/{id}', fn($id) => 'TODO: Detail RAB')->name('rab.show');
            Route::post('/rab/{id}/verify', fn($id) => 'TODO: Verify WD')->name('rab.verify');
            Route::post('/rab/{id}/tolak', fn($id) => 'TODO: Tolak')->name('rab.tolak');
        });

    // === DEKAN ===
    Route::middleware([CheckRole::class . ':dekan'])
        ->prefix('dekan')->name('dekan.')->group(function () {
            Route::get('/dashboard', fn() => 'TODO: Dashboard Dekan')->name('dashboard');
            Route::get('/rab', fn() => 'TODO: List RAB')->name('rab.index');
            Route::get('/rab/{id}', fn($id) => 'TODO: Detail + E-Sign')->name('rab.show');
            Route::post('/rab/{id}/setujui', fn($id) => 'TODO: Setujui + E-Sign')->name('rab.setujui');
        });

    // === TATA USAHA ===
    Route::middleware([CheckRole::class . ':tata_usaha'])
        ->prefix('tu')->name('tu.')->group(function () {
            Route::get('/dashboard', fn() => 'TODO: Dashboard TU')->name('dashboard');
            Route::get('/laporan', fn() => 'TODO: Laporan')->name('laporan.index');
            Route::get('/laporan/export-pdf', fn() => 'TODO: Export PDF')->name('laporan.pdf');
            Route::get('/laporan/export-excel', fn() => 'TODO: Export Excel')->name('laporan.excel');
            Route::get('/aset', fn() => 'TODO: Daftar Aset')->name('aset.index');
        });

    // === SHARED (semua role) ===
    Route::get('/notifications', fn() => 'TODO: Notifikasi')->name('notifications.index');
    Route::post('/notifications/{id}/read', fn($id) => 'TODO: Mark Read')->name('notifications.read');
});
