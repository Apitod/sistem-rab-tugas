# Konvensi Proyek: Sistem Informasi RAB Jurusan

Semua agent WAJIB mengikuti konvensi ini secara konsisten.

---

## 1. Konvensi Penamaan

### Controller
- Format: `{Resource}Controller.php`
- Singular, PascalCase
- Contoh: `RabProposalController`, `ApprovalController`, `ExportController`
- Lokasi: `app/Http/Controllers/`
- Extend: `App\Http\Controllers\Controller`

### Model
- Format: `{Resource}.php`
- Singular, PascalCase
- Contoh: `RabProposal`, `RabDetail`, `VerificationLog`, `Asset`
- Lokasi: `app/Models/`
- Properti wajib: `$fillable`, `$casts`, relasi method

### Migration
- Format: `{timestamp}_create_{table}_table.php`
- Plural snake_case untuk nama tabel
- Contoh:
  - `2024_01_01_000001_create_rab_proposals_table.php`
  - `2024_01_01_000002_create_rab_details_table.php`
  - `2024_01_01_000003_create_verification_logs_table.php`

### Service
- Format: `{Domain}Service.php`
- PascalCase, suffix `Service`
- Contoh: `RabService`, `ApprovalService`, `NotificationService`
- Lokasi: `app/Services/`
- Tidak extend class apapun (plain PHP class)

### Event
- Format: `{NamaKejadian}.php`
- PascalCase, verb+noun atau noun+verb
- Contoh: `RabApproved`, `RabRevised`, `RabRejected`
- Lokasi: `app/Events/`

### Listener
- Format: `{AksiYangDilakukan}.php`
- PascalCase, verb-phrase
- Contoh: `SyncAssetsToTable`, `SendApprovalNotification`
- Lokasi: `app/Listeners/`

### FormRequest
- Format: `{Action}{Resource}Request.php`
- PascalCase
- Contoh: `StoreRabProposalRequest`, `UpdateRabDetailRequest`, `ApprovalRequest`
- Lokasi: `app/Http/Requests/`

### Middleware
- Format: `{NamaFitur}.php`
- PascalCase
- Contoh: `CheckRole`
- Lokasi: `app/Http/Middleware/`

---

## 2. Coding Standards

### Return Types
Semua method Service dan Controller WAJIB deklarasi return type:
```php
// Controller
public function index(): \Illuminate\View\View
public function store(StoreRabProposalRequest $request): \Illuminate\Http\RedirectResponse
public function show(RabProposal $rab): \Illuminate\View\View

// Service
public function createProposal(array $data, User $user): RabProposal
public function approveRab(RabProposal $rab, User $approver, string $catatan): bool

// Middleware
public function handle(Request $request, Closure $next, string ...$roles): mixed
```

### Docblock
Wajib untuk semua method public di Service dan Event:
```php
/**
 * Buat RAB proposal baru dan upload file TOR.
 *
 * @param array $data   Data tervalidasi dari FormRequest
 * @param User  $user   User pengusul
 * @return RabProposal
 * @throws \Illuminate\Validation\ValidationException
 */
public function createProposal(array $data, User $user): RabProposal
```

### Variable Naming
- **Local variables**: camelCase → `$rabProposal`, `$totalPagu`, `$isApproved`
- **Boolean**: prefix `is`, `has`, `can` → `$isApproved`, `$hasSignature`
- **Collections**: plural → `$proposals`, `$details`, `$logs`
- **Konstanta**: UPPER_SNAKE_CASE → `STATUS_PENDING = 'pending_kaprodi'`
- **Array keys di config/data**: snake_case → `['total_pagu' => 5000000]`

### Status RAB (gunakan konstanta di Model)
```php
// app/Models/RabProposal.php
const STATUS_PENDING_KAPRODI = 'pending_kaprodi';
const STATUS_PENDING_WD      = 'pending_wd';
const STATUS_PENDING_DEKAN   = 'pending_dekan';
const STATUS_DISETUJUI       = 'disetujui';
const STATUS_REVISI          = 'revisi';
const STATUS_DITOLAK         = 'ditolak';
```

### Role (gunakan konstanta di Model User)
```php
// app/Models/User.php
const ROLE_PENGUSUL    = 'pengusul';
const ROLE_KAPRODI     = 'kaprodi';
const ROLE_WD_KEUANGAN = 'wd_keuangan';
const ROLE_DEKAN       = 'dekan';
const ROLE_TATA_USAHA  = 'tata_usaha';
```

---

## 3. Git Commit Message Format

Format: `{type}({scope}): {deskripsi singkat}`

### Types
| Type | Digunakan untuk |
|------|----------------|
| `feat` | fitur baru |
| `fix` | perbaikan bug |
| `chore` | maintenance, config, setup |
| `refactor` | refactoring tanpa ubah fitur |
| `style` | CSS/UI changes |
| `docs` | dokumentasi |
| `test` | tambah/ubah test |
| `db` | migration, seeder |

### Scopes (gunakan salah satu)
`auth`, `rab`, `approval`, `notification`, `asset`, `export`, `middleware`, `arch`, `ui`, `db`

### Contoh
```
feat(rab): add RAB proposal creation with TOR upload
feat(approval): implement multi-step approval workflow
fix(middleware): fix CheckRole redirect loop on guest
chore(arch): define project architecture and conventions
db(migration): add role column to users table
style(ui): apply Tailwind responsive layout to dashboard
docs(agents): add backend specialist brief
```

---

## 4. Blade Naming Convention

### File Naming
- Lowercase, kebab-case
- Format: `{resource}/{action}.blade.php`

### Struktur Views
```
resources/views/
├── layouts/
│   ├── app.blade.php          # layout utama (authenticated)
│   └── guest.blade.php        # layout login/register
├── components/
│   ├── alert.blade.php        # komponen alert success/error
│   ├── nav-link.blade.php     # link navigasi aktif
│   ├── status-badge.blade.php # badge status RAB
│   └── signature-canvas.blade.php # canvas e-signature
├── dashboard/
│   └── index.blade.php
├── rab/
│   ├── index.blade.php        # daftar RAB
│   ├── create.blade.php       # form buat RAB baru
│   ├── edit.blade.php         # form edit RAB
│   ├── show.blade.php         # detail RAB
│   └── partials/
│       ├── detail-row.blade.php     # baris detail anggaran
│       └── verification-log.blade.php
├── approval/
│   ├── index.blade.php        # antrian approval per role
│   └── show.blade.php         # detail untuk approve/revisi/tolak
├── notifications/
│   └── index.blade.php
└── assets/
    └── index.blade.php
```

### Pewarisan Layout
```blade
{{-- Semua view authenticated WAJIB extend layout ini --}}
@extends('layouts.app')

@section('title', 'Judul Halaman')

@section('content')
    {{-- konten --}}
@endsection
```

---

## 5. Route Naming Convention

Format: `{role_prefix}.{resource}.{action}`

### Action Names (RESTful)
| Action | HTTP Method | Keterangan |
|--------|-------------|------------|
| `index` | GET | daftar resource |
| `create` | GET | form buat baru |
| `store` | POST | simpan baru |
| `show` | GET | detail |
| `edit` | GET | form edit |
| `update` | PUT/PATCH | simpan edit |
| `destroy` | DELETE | hapus |

### Contoh Route Names per Role
```php
// Pengusul
Route::name('pengusul.')->group(function () {
    Route::resource('rab', RabProposalController::class)
        ->names([
            'index'   => 'pengusul.rab.index',
            'create'  => 'pengusul.rab.create',
            'store'   => 'pengusul.rab.store',
            'show'    => 'pengusul.rab.show',
            'edit'    => 'pengusul.rab.edit',
            'update'  => 'pengusul.rab.update',
            'destroy' => 'pengusul.rab.destroy',
        ]);
});

// Kaprodi
'kaprodi.approval.index'
'kaprodi.approval.show'
'kaprodi.approval.approve'
'kaprodi.approval.revise'
'kaprodi.approval.reject'
```

### Penggunaan di Blade
```blade
{{-- Selalu gunakan route() helper, BUKAN hardcode URL --}}
<a href="{{ route('pengusul.rab.create') }}">Buat RAB Baru</a>
<form action="{{ route('pengusul.rab.store') }}" method="POST">
```

---

## 6. Konvensi Tambahan

### Flash Messages
Selalu gunakan key berikut untuk konsistensi:
```php
return redirect()->route('pengusul.rab.index')->with('success', 'RAB berhasil disubmit.');
return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
return redirect()->back()->with('warning', 'Pagu anggaran mendekati batas.');
```

Di blade (via komponen `<x-alert />`):
```blade
@if(session('success'))
    <x-alert type="success" :message="session('success')" />
@endif
```

### API Response (jika diperlukan)
```php
return response()->json([
    'status'  => 'success',
    'message' => 'Data berhasil disimpan.',
    'data'    => $resource,
], 200);
```

### Eager Loading
Selalu eager load relasi untuk hindari N+1:
```php
// BENAR
$proposals = RabProposal::with(['user', 'details', 'verificationLogs'])->get();

// SALAH
$proposals = RabProposal::all(); // lalu akses $proposal->user di loop
```
