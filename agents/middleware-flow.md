# Middleware Flow: Sistem Informasi RAB Jurusan

---

## 1. Tabel: Role → Route Prefix → Middleware

| Role | Route Prefix | Middleware Stack | Akses Utama |
|------|-------------|-----------------|-------------|
| `pengusul` | `/pengusul` | `auth`, `role:pengusul` | Buat/edit/submit RAB, upload TOR |
| `kaprodi` | `/kaprodi` | `auth`, `role:kaprodi` | Approve/revisi/tolak RAB (step 1) |
| `wd_keuangan` | `/wd` | `auth`, `role:wd_keuangan` | Approve/revisi/tolak RAB (step 2) |
| `dekan` | `/dekan` | `auth`, `role:dekan` | Approve final + e-signature (step 3) |
| `tata_usaha` | `/tata-usaha` | `auth`, `role:tata_usaha` | Lihat aset, export PDF/Excel, laporan |

> Route tanpa prefix (seperti `/dashboard`) menggunakan middleware `auth` saja — semua role bisa akses.

---

## 2. ASCII Diagram Pipeline Request per Role

### Semua Request Authenticated
```
Browser Request
       │
       ▼
 ┌───────────┐
 │ web group │  (session, cookies, CSRF, etc.)
 └─────┬─────┘
       │
       ▼
 ┌───────────┐
 │   auth    │  Cek apakah user sudah login?
 └─────┬─────┘
       │ TIDAK LOGIN
       ├──────────────→ redirect('/login')
       │
       │ SUDAH LOGIN
       ▼
 ┌───────────────┐
 │  CheckRole    │  Cek role user vs parameter middleware
 └──────┬────────┘
        │ ROLE TIDAK COCOK
        ├──────────────→ redirect('/') + flash error
        │
        │ ROLE COCOK
        ▼
 ┌───────────────┐
 │  Controller   │
 └───────────────┘
```

### Alur per Role

#### Pengusul → POST /pengusul/rab
```
POST /pengusul/rab
  ├─ [web]          ← session, CSRF token valid?
  ├─ [auth]         ← user login? → NO: /login
  ├─ [role:pengusul]← user->role === 'pengusul'? → NO: / + error flash
  └─ RabProposalController@store
       └─ StoreRabProposalRequest (validasi)
            └─ RabService::createProposal()
                 ├─ simpan ke rab_proposals
                 ├─ upload TOR ke storage/app/public/tor/
                 └─ NotificationService::notify(kaprodi, "RAB baru masuk")
```

#### Kaprodi → POST /kaprodi/approval/{id}/approve
```
POST /kaprodi/approval/{id}/approve
  ├─ [web]
  ├─ [auth]
  ├─ [role:kaprodi]
  └─ ApprovalController@approve
       └─ ApprovalRequest (validasi catatan)
            └─ ApprovalService::approve()
                 ├─ cek status RAB === 'pending_kaprodi'
                 ├─ ubah status → 'pending_wd'
                 ├─ catat VerificationLog
                 └─ NotificationService::notify(wd_keuangan, "RAB menunggu review")
```

#### WD Keuangan → POST /wd/approval/{id}/approve
```
POST /wd/approval/{id}/approve
  ├─ [web]
  ├─ [auth]
  ├─ [role:wd_keuangan]
  └─ ApprovalController@approve
       └─ ApprovalService::approve()
            ├─ cek status RAB === 'pending_wd'
            ├─ ubah status → 'pending_dekan'
            └─ NotificationService::notify(dekan, "RAB siap ditandatangani")
```

#### Dekan → POST /dekan/approval/{id}/approve (dengan e-signature)
```
POST /dekan/approval/{id}/approve
  ├─ [web]
  ├─ [auth]
  ├─ [role:dekan]
  └─ ApprovalController@approve
       └─ ApprovalRequest (validasi catatan + signature_data)
            └─ ApprovalService::approveWithSignature()
                 ├─ cek status RAB === 'pending_dekan'
                 ├─ simpan signature PNG ke storage/app/public/signatures/
                 ├─ ubah status → 'disetujui'
                 ├─ catat VerificationLog
                 ├─ event(new RabApproved($rabProposal))
                 │     └─ [Listener] SyncAssetsToTable → salin rab_details ke assets
                 │     └─ [Listener] SendApprovalNotification → notify pengusul
                 └─ redirect dengan success flash
```

#### Tata Usaha → GET /tata-usaha/assets/export
```
GET /tata-usaha/assets/export
  ├─ [web]
  ├─ [auth]
  ├─ [role:tata_usaha]
  └─ ExportController@exportExcel
       └─ ExportService::generateExcel()
            └─ download file Excel
```

---

## 3. CheckRole Middleware — Detail Implementasi

### File: `app/Http/Middleware/CheckRole.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Support multiple roles: middleware('role:kaprodi,wd_keuangan')
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = auth()->user();

        if (!$user || !in_array($user->role, $roles)) {
            return redirect('/')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
```

### Registrasi di `bootstrap/app.php` (Laravel 11)

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
    ]);
})
```

### Cara Penggunaan di `routes/web.php`

```php
// Single role
Route::middleware(['auth', 'role:pengusul'])->prefix('pengusul')->group(function () {
    // routes...
});

// Multiple role (user yang punya SALAH SATU role berikut boleh akses)
Route::middleware(['auth', 'role:kaprodi,wd_keuangan'])->group(function () {
    // routes yang bisa diakses oleh kaprodi ATAU wd_keuangan
});

// Dekan saja
Route::middleware(['auth', 'role:dekan'])->prefix('dekan')->group(function () {
    // routes...
});
```

### Contoh Unit Test CheckRole (referensi)

```php
// tests/Feature/CheckRoleMiddlewareTest.php

// Test: user dengan role salah di-redirect ke /
public function test_wrong_role_redirected_to_home(): void
{
    $user = User::factory()->create(['role' => 'pengusul']);
    $this->actingAs($user)
         ->get('/kaprodi/approval')
         ->assertRedirect('/');
}

// Test: user dengan role benar bisa akses
public function test_correct_role_can_access(): void
{
    $user = User::factory()->create(['role' => 'kaprodi']);
    $this->actingAs($user)
         ->get('/kaprodi/approval')
         ->assertStatus(200);
}

// Test: multiple role — salah satu cocok → akses granted
public function test_multiple_role_grants_access(): void
{
    $user = User::factory()->create(['role' => 'wd_keuangan']);
    // Route dengan middleware role:kaprodi,wd_keuangan
    $this->actingAs($user)
         ->get('/shared-route')
         ->assertStatus(200);
}
```

---

## 4. Middleware Alias Registry

Daftarkan di `bootstrap/app.php`:

```php
use App\Http\Middleware\CheckRole;

->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => CheckRole::class,
    ]);
})
```

Di Laravel 10 ke bawah, daftarkan di `app/Http/Kernel.php`:
```php
protected $routeMiddleware = [
    // ...
    'role' => \App\Http\Middleware\CheckRole::class,
];
```

---

## 5. Ringkasan: Role → Aksi yang Diizinkan

```
┌─────────────┬──────────────────────────────────────────────────────────┐
│    ROLE     │  AKSI YANG DIIZINKAN                                      │
├─────────────┼──────────────────────────────────────────────────────────┤
│ pengusul    │ - Buat RAB baru                                           │
│             │ - Edit RAB (jika status=revisi)                           │
│             │ - Upload TOR (PDF)                                        │
│             │ - Submit RAB ke Kaprodi                                   │
│             │ - Lihat status & notifikasi RAB sendiri                   │
├─────────────┼──────────────────────────────────────────────────────────┤
│ kaprodi     │ - Lihat daftar RAB pending_kaprodi                       │
│             │ - Approve / Revisi / Tolak RAB (step 1)                  │
│             │ - Beri catatan verifikasi                                 │
├─────────────┼──────────────────────────────────────────────────────────┤
│ wd_keuangan │ - Lihat daftar RAB pending_wd                            │
│             │ - Approve / Revisi / Tolak RAB (step 2)                  │
│             │ - Cek pagu anggaran (early warning)                      │
├─────────────┼──────────────────────────────────────────────────────────┤
│ dekan       │ - Lihat daftar RAB pending_dekan                         │
│             │ - Approve dengan e-signature (step 3 / final)            │
│             │ - Tolak RAB                                               │
├─────────────┼──────────────────────────────────────────────────────────┤
│ tata_usaha  │ - Lihat semua RAB (read-only)                            │
│             │ - Lihat & kelola tabel assets                             │
│             │ - Export PDF / Excel laporan                              │
│             │ - Lihat early warning pagu anggaran                      │
└─────────────┴──────────────────────────────────────────────────────────┘
```
