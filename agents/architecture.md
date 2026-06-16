# Arsitektur Proyek: Sistem Informasi RAB Jurusan

## 1. Gambaran Umum

Proyek ini adalah aplikasi web berbasis Laravel untuk manajemen Rencana Anggaran Biaya (RAB) jurusan dengan alur persetujuan multi-step (multi-role approval workflow).

### Stack Teknologi
- **Backend**: Laravel 11 (PHP 8.2+)
- **Database**: MySQL 8.x (localhost)
- **Auth**: Laravel Breeze (session-based)
- **Frontend**: Blade + Tailwind CSS + Alpine.js
- **Export**: barryvdh/laravel-dompdf + maatwebsite/excel
- **E-signature**: signature_pad.js → simpan PNG ke storage

---

## 2. Diagram Alur Data (ASCII)

```
┌─────────────┐     HTTP Request
│   Browser   │──────────────────────────────────────────────────┐
└─────────────┘                                                   │
                                                                  ▼
                                                      ┌─────────────────────┐
                                                      │   routes/web.php    │
                                                      │  (Route Groups)     │
                                                      └────────┬────────────┘
                                                               │
                                              ┌────────────────▼────────────────┐
                                              │         Middleware Stack         │
                                              │  1. web (session, csrf, etc.)   │
                                              │  2. auth (Laravel built-in)     │
                                              │  3. CheckRole (custom)          │
                                              └────────────────┬────────────────┘
                                                               │
                                              ┌────────────────▼────────────────┐
                                              │           Controller            │
                                              │  - Validasi input (FormRequest) │
                                              │  - Panggil Service              │
                                              │  - Return response/view         │
                                              └────────────────┬────────────────┘
                                                               │
                                              ┌────────────────▼────────────────┐
                                              │          Service Layer          │
                                              │  - Business logic               │
                                              │  - Fire Events                  │
                                              │  - Orchestrate Models           │
                                              └────┬───────────────────┬────────┘
                                                   │                   │
                                    ┌──────────────▼──┐    ┌──────────▼──────────┐
                                    │     Models       │    │   Event/Listener    │
                                    │  (Eloquent ORM)  │    │  (Asset Sync, dll.) │
                                    └──────────────┬──┘    └─────────────────────┘
                                                   │
                                    ┌──────────────▼──┐
                                    │    Database      │
                                    │    (MySQL)       │
                                    └─────────────────┘
```

### Alur Status RAB
```
[Pengusul] → SUBMIT
    │
    ▼
pending_kaprodi
    │ Kaprodi APPROVE
    ▼
pending_wd
    │ WD Keuangan APPROVE
    ▼
pending_dekan
    │ Dekan APPROVE ──────────────────────→ [Event: RabApproved]
    ▼                                              │
  disetujui                                        ▼
                                         [Listener: SyncAssetsToTable]
                                                   │
                                                   ▼
                                         salin rab_details → assets

Setiap step bisa: REVISI (kembalikan ke pengusul) atau TOLAK
```

---

## 3. Struktur Folder Wajib

Semua agent WAJIB mengikuti struktur berikut:

```
/home/dzul/Documents/tugas-apsi-web/
├── app/
│   ├── Events/
│   │   └── RabApproved.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/                    # (Breeze — jangan diubah)
│   │   │   ├── DashboardController.php
│   │   │   ├── RabProposalController.php
│   │   │   ├── RabDetailController.php
│   │   │   ├── ApprovalController.php
│   │   │   ├── NotificationController.php
│   │   │   ├── AssetController.php
│   │   │   └── ExportController.php
│   │   ├── Middleware/
│   │   │   └── CheckRole.php           # ← dibuat di task ini
│   │   └── Requests/
│   │       ├── StoreRabProposalRequest.php
│   │       ├── UpdateRabProposalRequest.php
│   │       ├── StoreRabDetailRequest.php
│   │       └── ApprovalRequest.php
│   ├── Listeners/
│   │   └── SyncAssetsToTable.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── RabProposal.php
│   │   ├── RabDetail.php
│   │   ├── VerificationLog.php
│   │   ├── Notification.php
│   │   └── Asset.php
│   └── Services/
│       ├── RabService.php
│       ├── ApprovalService.php
│       ├── NotificationService.php
│       ├── AssetService.php
│       └── ExportService.php
├── database/
│   ├── migrations/
│   │   ├── xxxx_create_users_table.php         # (default Laravel, dimodifikasi)
│   │   ├── xxxx_create_rab_proposals_table.php
│   │   ├── xxxx_create_rab_details_table.php
│   │   ├── xxxx_create_verification_logs_table.php
│   │   ├── xxxx_create_notifications_table.php
│   │   └── xxxx_create_assets_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── UserSeeder.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php
│       │   └── guest.blade.php
│       ├── components/
│       │   ├── nav-link.blade.php
│       │   └── alert.blade.php
│       ├── dashboard/
│       │   └── index.blade.php
│       ├── rab/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   ├── show.blade.php
│       │   └── partials/
│       │       └── detail-row.blade.php
│       ├── approval/
│       │   ├── index.blade.php
│       │   └── show.blade.php
│       ├── notifications/
│       │   └── index.blade.php
│       └── assets/
│           └── index.blade.php
├── routes/
│   └── web.php                          # ← scaffold di task ini
├── storage/
│   └── app/public/
│       ├── tor/                         # upload PDF TOR
│       └── signatures/                  # simpan PNG e-signature
└── agents/
    ├── architecture.md                  # ← file ini
    ├── conventions.md
    ├── middleware-flow.md
    └── *.md                             # brief per agent
```

---

## 4. Alur Data End-to-End

```
HTTP Request
    │
    ├─ routes/web.php ──→ middleware(['auth', 'role:pengusul'])
    │                                    │
    │                         ┌──────────▼──────────┐
    │                         │    CheckRole.php     │
    │                         │  auth()->user()->role│
    │                         │  === param?          │
    │                         │  NO → redirect('/')  │
    │                         └──────────┬──────────┘
    │                                    │ YES
    │                         ┌──────────▼──────────┐
    │                         │   FormRequest        │
    │                         │  (validate input)    │
    │                         └──────────┬──────────┘
    │                                    │ valid
    │                         ┌──────────▼──────────┐
    │                         │    Controller        │
    │                         │  $service->method()  │
    │                         └──────────┬──────────┘
    │                                    │
    │                         ┌──────────▼──────────┐
    │                         │   Service Layer      │
    │                         │  business logic      │
    │                         │  event()->fire()     │
    │                         └──────────┬──────────┘
    │                                    │
    │              ┌─────────────────────┼──────────────────┐
    │              │                     │                  │
    │    ┌─────────▼──────┐   ┌──────────▼──────┐  ┌───────▼───────┐
    │    │  Eloquent Model │   │  Event Dispatch  │  │  Notification │
    │    │  (DB Query)     │   │  RabApproved     │  │  Service      │
    │    └─────────┬──────┘   └──────────┬──────┘  └───────────────┘
    │              │                     │
    │    ┌─────────▼──────┐   ┌──────────▼──────┐
    │    │    MySQL DB     │   │  Listener:       │
    │    └────────────────┘   │  SyncAssetsTo... │
    │                         └─────────────────┘
    │
    └─→ view()->with($data) / redirect()->with('success', ...)
```

---

## 5. Daftar Service Class

| Service | File | Tanggung Jawab |
|---------|------|----------------|
| RabService | `app/Services/RabService.php` | CRUD RAB proposal, upload TOR PDF, kalkulasi total |
| ApprovalService | `app/Services/ApprovalService.php` | Transisi status, catat VerificationLog, fire event |
| NotificationService | `app/Services/NotificationService.php` | Buat notifikasi in-app, tandai read/unread |
| AssetService | `app/Services/AssetService.php` | Salin rab_details ke assets, cek pagu anggaran |
| ExportService | `app/Services/ExportService.php` | Generate PDF (dompdf), generate Excel (maatwebsite) |

---

## 6. Daftar Event & Listener

| Event | File | Trigger |
|-------|------|---------|
| RabApproved | `app/Events/RabApproved.php` | ApprovalService saat Dekan approve |

| Listener | File | Pada Event |
|----------|------|-----------|
| SyncAssetsToTable | `app/Listeners/SyncAssetsToTable.php` | RabApproved |
| SendApprovalNotification | `app/Listeners/SendApprovalNotification.php` | RabApproved (dan setiap approval step) |

Registrasi di `app/Providers/EventServiceProvider.php`:
```php
protected $listen = [
    \App\Events\RabApproved::class => [
        \App\Listeners\SyncAssetsToTable::class,
        \App\Listeners\SendApprovalNotification::class,
    ],
];
```

---

## 7. Package Dependencies (Composer)

```json
{
    "require": {
        "php": "^8.2",
        "laravel/framework": "^11.0",
        "laravel/breeze": "^2.0",
        "barryvdh/laravel-dompdf": "^2.0",
        "maatwebsite/excel": "^3.1"
    },
    "require-dev": {
        "fakerphp/faker": "^1.23",
        "laravel/pint": "^1.0",
        "phpunit/phpunit": "^11.0"
    }
}
```

Install commands:
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
composer require barryvdh/laravel-dompdf
composer require maatwebsite/excel
npm install signature_pad
npm run build
```

---

## 8. Strategi Autentikasi

- **Driver**: Laravel session-based auth (bawaan)
- **Package**: Laravel Breeze (blade stack) — minimal, mudah dikustomisasi
- **Kolom tambahan di tabel `users`**:
  - `role` ENUM('pengusul','kaprodi','wd_keuangan','dekan','tata_usaha')
  - `jurusan` VARCHAR(100)
- **Guard**: default `web`
- **Middleware auth**: `auth` (bawaan) → redirect ke `/login` jika belum login
- **Middleware role**: `CheckRole` custom → redirect ke `/` dengan flash error jika role tidak sesuai
- **Password reset**: gunakan Breeze built-in (email driver = log untuk development)

---

## 9. Skema Database (Ringkasan)

| Tabel | Kolom Utama |
|-------|-------------|
| users | id, name, email, password, role, jurusan |
| rab_proposals | id, user_id, judul, total_pagu, tor_path, status, signature_path, submitted_at |
| rab_details | id, rab_proposal_id, nama_item, volume, satuan, harga_satuan, subtotal |
| verification_logs | id, rab_proposal_id, user_id, action, catatan, created_at |
| notifications | id, user_id, title, body, is_read, related_id, related_type |
| assets | id, rab_proposal_id, rab_detail_id, nama_item, volume, satuan, harga_satuan, approved_at |

---

## 10. Catatan Penting untuk Semua Agent

1. **Jangan** taruh business logic di Controller — semua logic ke Service.
2. **Selalu** gunakan FormRequest untuk validasi, bukan `$request->validate()` di Controller.
3. Route naming wajib konsisten: `{prefix}.{resource}.{action}` (lihat conventions.md).
4. **Semua** redirect dengan pesan menggunakan `->with('success', ...)` atau `->with('error', ...)`.
5. Storage untuk file upload: `storage/app/public/tor/` dan `storage/app/public/signatures/`.
6. Jalankan `php artisan storage:link` sekali setelah setup.
