<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Sistem Informasi RAB Jurusan
|--------------------------------------------------------------------------
|
| Routing dibagi per role menggunakan middleware CheckRole.
|
| Registrasi middleware alias di bootstrap/app.php:
|   $middleware->alias(['role' => \App\Http\Middleware\CheckRole::class]);
|
| Status flow RAB:
|   pending_kaprodi → pending_wd → pending_dekan → disetujui
|   (setiap step bisa: revisi / ditolak)
|
*/

// ─────────────────────────────────────────────────────────────────────────────
// PUBLIC ROUTES (unauthenticated)
// ─────────────────────────────────────────────────────────────────────────────

Route::get('/', function () {
    return redirect()->route('login');
});

// ─────────────────────────────────────────────────────────────────────────────
// AUTHENTICATED — semua role (dashboard bersama)
// ─────────────────────────────────────────────────────────────────────────────

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Notifikasi — semua role authenticated bisa akses
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', function () {
            return 'TODO: NotificationController@index';
        })->name('index');

        Route::patch('/{id}/read', function ($id) {
            return 'TODO: NotificationController@markRead';
        })->name('read');

        Route::patch('/read-all', function () {
            return 'TODO: NotificationController@markAllRead';
        })->name('read-all');
    });

});

// ─────────────────────────────────────────────────────────────────────────────
// PENGUSUL ROUTES
// Prefix: /pengusul
// Middleware: auth + role:pengusul
// ─────────────────────────────────────────────────────────────────────────────

Route::middleware(['auth', 'role:pengusul'])
    ->prefix('pengusul')
    ->name('pengusul.')
    ->group(function () {

        // Dashboard pengusul — lihat semua RAB milik sendiri
        Route::get('/dashboard', function () {
            return 'TODO: DashboardController@pengusul';
        })->name('dashboard');

        // RAB Proposal CRUD
        Route::prefix('rab')->name('rab.')->group(function () {
            // GET /pengusul/rab — daftar RAB milik pengusul
            Route::get('/', function () {
                return 'TODO: RabProposalController@index';
            })->name('index');

            // GET /pengusul/rab/create — form buat RAB baru
            Route::get('/create', function () {
                return 'TODO: RabProposalController@create';
            })->name('create');

            // POST /pengusul/rab — simpan RAB baru + upload TOR
            Route::post('/', function () {
                return 'TODO: RabProposalController@store';
            })->name('store');

            // GET /pengusul/rab/{rab} — detail RAB
            Route::get('/{rab}', function ($rab) {
                return 'TODO: RabProposalController@show id=' . $rab;
            })->name('show');

            // GET /pengusul/rab/{rab}/edit — form edit RAB (hanya jika status=revisi)
            Route::get('/{rab}/edit', function ($rab) {
                return 'TODO: RabProposalController@edit id=' . $rab;
            })->name('edit');

            // PUT /pengusul/rab/{rab} — update RAB
            Route::put('/{rab}', function ($rab) {
                return 'TODO: RabProposalController@update id=' . $rab;
            })->name('update');

            // DELETE /pengusul/rab/{rab} — hapus RAB (hanya jika status=revisi/draft)
            Route::delete('/{rab}', function ($rab) {
                return 'TODO: RabProposalController@destroy id=' . $rab;
            })->name('destroy');

            // POST /pengusul/rab/{rab}/submit — submit ke Kaprodi
            Route::post('/{rab}/submit', function ($rab) {
                return 'TODO: RabProposalController@submit id=' . $rab;
            })->name('submit');
        });

        // RAB Detail (baris anggaran dalam proposal)
        Route::prefix('rab/{rab}/details')->name('rab.details.')->group(function () {
            Route::post('/', function ($rab) {
                return 'TODO: RabDetailController@store';
            })->name('store');

            Route::put('/{detail}', function ($rab, $detail) {
                return 'TODO: RabDetailController@update';
            })->name('update');

            Route::delete('/{detail}', function ($rab, $detail) {
                return 'TODO: RabDetailController@destroy';
            })->name('destroy');
        });

    });

// ─────────────────────────────────────────────────────────────────────────────
// KAPRODI ROUTES
// Prefix: /kaprodi
// Middleware: auth + role:kaprodi
// Approval Step 1: pending_kaprodi → pending_wd | revisi | ditolak
// ─────────────────────────────────────────────────────────────────────────────

Route::middleware(['auth', 'role:kaprodi'])
    ->prefix('kaprodi')
    ->name('kaprodi.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return 'TODO: DashboardController@kaprodi';
        })->name('dashboard');

        Route::prefix('approval')->name('approval.')->group(function () {
            // GET /kaprodi/approval — antrian RAB status pending_kaprodi
            Route::get('/', function () {
                return 'TODO: ApprovalController@index (kaprodi)';
            })->name('index');

            // GET /kaprodi/approval/{rab} — detail RAB untuk review
            Route::get('/{rab}', function ($rab) {
                return 'TODO: ApprovalController@show id=' . $rab;
            })->name('show');

            // POST /kaprodi/approval/{rab}/approve — setujui, lanjut ke WD
            Route::post('/{rab}/approve', function ($rab) {
                return 'TODO: ApprovalController@approve (kaprodi) id=' . $rab;
            })->name('approve');

            // POST /kaprodi/approval/{rab}/revise — kembalikan ke pengusul
            Route::post('/{rab}/revise', function ($rab) {
                return 'TODO: ApprovalController@revise (kaprodi) id=' . $rab;
            })->name('revise');

            // POST /kaprodi/approval/{rab}/reject — tolak permanen
            Route::post('/{rab}/reject', function ($rab) {
                return 'TODO: ApprovalController@reject (kaprodi) id=' . $rab;
            })->name('reject');
        });

        // Riwayat semua verifikasi yang dilakukan Kaprodi
        Route::get('/history', function () {
            return 'TODO: ApprovalController@history (kaprodi)';
        })->name('history');

    });

// ─────────────────────────────────────────────────────────────────────────────
// WD KEUANGAN ROUTES
// Prefix: /wd
// Middleware: auth + role:wd_keuangan
// Approval Step 2: pending_wd → pending_dekan | revisi | ditolak
// ─────────────────────────────────────────────────────────────────────────────

Route::middleware(['auth', 'role:wd_keuangan'])
    ->prefix('wd')
    ->name('wd.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return 'TODO: DashboardController@wdKeuangan';
        })->name('dashboard');

        Route::prefix('approval')->name('approval.')->group(function () {
            // GET /wd/approval — antrian RAB status pending_wd
            Route::get('/', function () {
                return 'TODO: ApprovalController@index (wd_keuangan)';
            })->name('index');

            // GET /wd/approval/{rab} — detail RAB + cek pagu anggaran
            Route::get('/{rab}', function ($rab) {
                return 'TODO: ApprovalController@show id=' . $rab;
            })->name('show');

            // POST /wd/approval/{rab}/approve
            Route::post('/{rab}/approve', function ($rab) {
                return 'TODO: ApprovalController@approve (wd_keuangan) id=' . $rab;
            })->name('approve');

            // POST /wd/approval/{rab}/revise
            Route::post('/{rab}/revise', function ($rab) {
                return 'TODO: ApprovalController@revise (wd_keuangan) id=' . $rab;
            })->name('revise');

            // POST /wd/approval/{rab}/reject
            Route::post('/{rab}/reject', function ($rab) {
                return 'TODO: ApprovalController@reject (wd_keuangan) id=' . $rab;
            })->name('reject');
        });

        // Early warning pagu anggaran
        Route::get('/budget-warning', function () {
            return 'TODO: BudgetController@earlyWarning';
        })->name('budget-warning');

        Route::get('/history', function () {
            return 'TODO: ApprovalController@history (wd_keuangan)';
        })->name('history');

    });

// ─────────────────────────────────────────────────────────────────────────────
// DEKAN ROUTES
// Prefix: /dekan
// Middleware: auth + role:dekan
// Approval Step 3 (Final): pending_dekan → disetujui | ditolak
// + e-signature (signature_pad.js → PNG)
// + trigger Event RabApproved → SyncAssetsToTable
// ─────────────────────────────────────────────────────────────────────────────

Route::middleware(['auth', 'role:dekan'])
    ->prefix('dekan')
    ->name('dekan.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return 'TODO: DashboardController@dekan';
        })->name('dashboard');

        Route::prefix('approval')->name('approval.')->group(function () {
            // GET /dekan/approval — antrian RAB status pending_dekan
            Route::get('/', function () {
                return 'TODO: ApprovalController@index (dekan)';
            })->name('index');

            // GET /dekan/approval/{rab} — detail + form e-signature
            Route::get('/{rab}', function ($rab) {
                return 'TODO: ApprovalController@show id=' . $rab;
            })->name('show');

            // POST /dekan/approval/{rab}/approve — approve final + simpan signature PNG
            // Trigger: event(new RabApproved($rab)) → SyncAssetsToTable
            Route::post('/{rab}/approve', function ($rab) {
                return 'TODO: ApprovalController@approveWithSignature (dekan) id=' . $rab;
            })->name('approve');

            // POST /dekan/approval/{rab}/reject
            Route::post('/{rab}/reject', function ($rab) {
                return 'TODO: ApprovalController@reject (dekan) id=' . $rab;
            })->name('reject');
        });

        Route::get('/history', function () {
            return 'TODO: ApprovalController@history (dekan)';
        })->name('history');

    });

// ─────────────────────────────────────────────────────────────────────────────
// TATA USAHA ROUTES
// Prefix: /tata-usaha
// Middleware: auth + role:tata_usaha
// Read-only + export PDF/Excel
// ─────────────────────────────────────────────────────────────────────────────

Route::middleware(['auth', 'role:tata_usaha'])
    ->prefix('tata-usaha')
    ->name('tata-usaha.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return 'TODO: DashboardController@tataUsaha';
        })->name('dashboard');

        // Lihat semua RAB (semua status, read-only)
        Route::prefix('rab')->name('rab.')->group(function () {
            Route::get('/', function () {
                return 'TODO: RabProposalController@indexAll (tata_usaha)';
            })->name('index');

            Route::get('/{rab}', function ($rab) {
                return 'TODO: RabProposalController@show id=' . $rab;
            })->name('show');
        });

        // Asset management (hasil sync dari RAB disetujui)
        Route::prefix('assets')->name('assets.')->group(function () {
            Route::get('/', function () {
                return 'TODO: AssetController@index';
            })->name('index');

            Route::get('/{asset}', function ($asset) {
                return 'TODO: AssetController@show id=' . $asset;
            })->name('show');
        });

        // Export
        Route::prefix('export')->name('export.')->group(function () {
            // GET /tata-usaha/export/rab/{rab}/pdf
            Route::get('/rab/{rab}/pdf', function ($rab) {
                return 'TODO: ExportController@exportRabPdf id=' . $rab;
            })->name('rab.pdf');

            // GET /tata-usaha/export/rab/{rab}/excel
            Route::get('/rab/{rab}/excel', function ($rab) {
                return 'TODO: ExportController@exportRabExcel id=' . $rab;
            })->name('rab.excel');

            // GET /tata-usaha/export/assets/excel
            Route::get('/assets/excel', function () {
                return 'TODO: ExportController@exportAssetsExcel';
            })->name('assets.excel');

            // GET /tata-usaha/export/assets/pdf
            Route::get('/assets/pdf', function () {
                return 'TODO: ExportController@exportAssetsPdf';
            })->name('assets.pdf');
        });

        // Early warning pagu anggaran (read-only untuk tata usaha)
        Route::get('/budget-warning', function () {
            return 'TODO: BudgetController@earlyWarning (tata_usaha)';
        })->name('budget-warning');

    });

// ─────────────────────────────────────────────────────────────────────────────
// AUTH ROUTES (Laravel Breeze)
// Diinclude otomatis oleh Breeze setelah install:
//   php artisan breeze:install blade
// File: routes/auth.php (di-require oleh Breeze setup)
// ─────────────────────────────────────────────────────────────────────────────

// require __DIR__.'/auth.php'; // Uncomment setelah install Breeze
