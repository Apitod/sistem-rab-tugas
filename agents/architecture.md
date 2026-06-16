# Arsitektur Sistem Informasi RAB Jurusan

## Overview

Sistem Informasi RAB Jurusan adalah aplikasi web berbasis Laravel 13 yang mengelola proses pengajuan, verifikasi bertahap, dan persetujuan Rencana Anggaran Biaya (RAB) pada tingkat jurusan. Sistem mendukung lima peran pengguna (pengusul, kaprodi, wd_keuangan, dekan, tata_usaha) dengan alur approval multi-level, unggah dokumen TOR (PDF), tanda tangan elektronik berbasis kanvas, ekspor laporan PDF/Excel, peringatan dini pagu anggaran, dan sinkronisasi otomatis data aset saat RAB disetujui Dekan — semuanya menggunakan notifikasi in-app tanpa SMTP maupun WhatsApp.

---

## Alur Data (ASCII Diagram)

```
HTTP Request
    |
    v
+-------------------+
|    Middleware      |  auth (Breeze session)
|    Pipeline        |  CheckRole (validasi users.role)
+-------------------+
    |
    v
+-------------------+
|    Controller      |  app/Http/Controllers/<Fitur>/
|                   |  Terima input, delegasi ke Service
+-------------------+
    |
    v
+-------------------+
|    Service Layer   |  app/Services/
|                   |  RabWorkflowService
|                   |  AssetSyncService
|                   |  NotificationService
+-------------------+
    |
    v
+-------------------+
|    Model / ORM     |  app/Models/
|                   |  Eloquent + Relationship
+-------------------+
    |
    v
+-------------------+
|    Database        |  MySQL (localhost)
|                   |  users, rab_proposals, rab_details,
|                   |  verification_logs, notifications, assets
+-------------------+
    |
    v
HTTP Response (Blade View / JSON / File Download)
```

---

## Struktur Folder Wajib

Semua agent WAJIB mengikuti struktur berikut. Jangan buat file di luar pola ini tanpa diskusi.

```
tugas-apsi-web/
├── agents/                         # Dokumentasi arsitektur & konvensi (agent artifacts)
│   ├── architecture.md
│   ├── conventions.md
│   └── middleware-flow.md
│
├── app/
│   ├── Events/
│   │   └── RabProposalApproved.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/               # Di-generate Breeze
│   │   │   ├── Pengusul/
│   │   │   │   └── RabController.php
│   │   │   ├── Kaprodi/
│   │   │   │   └── RabController.php
│   │   │   ├── WdKeuangan/
│   │   │   │   └── RabController.php
│   │   │   ├── Dekan/
│   │   │   │   └── RabController.php
│   │   │   └── TataUsaha/
│   │   │       ├── LaporanController.php
│   │   │       └── AsetController.php
│   │   ├── Middleware/
│   │   │   └── CheckRole.php
│   │   └── Requests/
│   │       ├── StoreRabRequest.php
│   │       └── VerifyRabRequest.php
│   ├── Listeners/
│   │   └── SyncAssetsOnApproval.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── RabProposal.php
│   │   ├── RabDetail.php
│   │   ├── VerificationLog.php
│   │   ├── Notification.php
│   │   └── Asset.php
│   ├── Providers/
│   │   └── AppServiceProvider.php  # Daftarkan Event/Listener di sini
│   └── Services/
│       ├── RabWorkflowService.php
│       ├── AssetSyncService.php
│       └── NotificationService.php
│
├── bootstrap/
│   └── app.php                     # Alias middleware CheckRole didaftarkan di sini
│
├── database/
│   ├── migrations/
│   │   ├── xxxx_create_users_table.php
│   │   ├── xxxx_create_rab_proposals_table.php
│   │   ├── xxxx_create_rab_details_table.php
│   │   ├── xxxx_create_verification_logs_table.php
│   │   ├── xxxx_create_notifications_table.php
│   │   └── xxxx_create_assets_table.php
│   └── seeders/
│       └── UserSeeder.php          # Seed 5 role user
│
├── public/
│   └── signature/                  # Hasil simpan canvas e-sign (PNG)
│
├── resources/
│   ├── js/
│   │   └── signature_pad.js        # Library e-signature
│   └── views/
│       ├── auth/                   # Di-generate Breeze
│       ├── pengusul/
│       │   ├── dashboard.blade.php
│       │   └── rab/
│       │       ├── index.blade.php
│       │       ├── create.blade.php
│       │       └── show.blade.php
│       ├── kaprodi/
│       │   ├── dashboard.blade.php
│       │   └── rab/
│       │       ├── index.blade.php
│       │       └── show.blade.php
│       ├── wd/
│       │   ├── dashboard.blade.php
│       │   └── rab/
│       │       ├── index.blade.php
│       │       └── show.blade.php
│       ├── dekan/
│       │   ├── dashboard.blade.php
│       │   └── rab/
│       │       ├── index.blade.php
│       │       └── show.blade.php
│       └── tu/
│           ├── dashboard.blade.php
│           ├── laporan/
│           │   └── index.blade.php
│           └── aset/
│               └── index.blade.php
│
├── routes/
│   ├── web.php                     # Routing utama (5 group role)
│   └── console.php
│
└── storage/
    └── app/
        └── tor/                    # Upload TOR PDF disimpan di sini
```

---

## Service Classes

### RabWorkflowService (`app/Services/RabWorkflowService.php`)
Mengelola seluruh alur status RAB:
- `submit(User $user, array $data): RabProposal` — pengusul buat RAB baru (status: pending_kaprodi)
- `verifyByKaprodi(RabProposal $rab, string $action, ?string $catatan): void` — approve/revisi
- `verifyByWd(RabProposal $rab, string $action, ?string $catatan): void` — approve/tolak
- `verifyByDekan(RabProposal $rab, string $signatureData): void` — setujui + simpan e-sign → fire RabProposalApproved

### AssetSyncService (`app/Services/AssetSyncService.php`)
Dipanggil oleh Listener saat RAB disetujui:
- `syncFromRab(RabProposal $rab): void` — salin semua RabDetail → tabel assets

### NotificationService (`app/Services/NotificationService.php`)
Notifikasi in-app (tanpa SMTP/WA):
- `send(User $recipient, string $message, ?string $link): Notification`
- `markRead(int $notificationId): void`
- `getUnread(User $user): Collection`

---

## Events & Listeners

| Event                  | Listener                  | Trigger                          |
|------------------------|---------------------------|----------------------------------|
| RabProposalApproved    | SyncAssetsOnApproval      | Dekan approve RAB (e-sign done)  |

Daftarkan di `AppServiceProvider::boot()`:
```php
Event::listen(
    RabProposalApproved::class,
    SyncAssetsOnApproval::class,
);
```

---

## Package Dependencies

| Package                    | Versi    | Kegunaan                        |
|----------------------------|----------|---------------------------------|
| laravel/breeze             | ^2.x     | Autentikasi (login/register)    |
| barryvdh/laravel-dompdf    | ^3.x     | Export PDF laporan RAB          |
| maatwebsite/excel          | ^3.x     | Export Excel laporan RAB        |

Install:
```bash
composer require barryvdh/laravel-dompdf maatwebsite/excel
composer require laravel/breeze --dev
php artisan breeze:install blade
```

---

## Strategi Autentikasi

- Menggunakan **Laravel Breeze** dengan stack Blade (email + password).
- Kolom `users.role` bertipe `ENUM('pengusul','kaprodi','wd_keuangan','dekan','tata_usaha')`.
- Setelah login, middleware `auth` memvalidasi sesi, lalu `CheckRole` memvalidasi `users.role` sesuai route group yang diakses.
- Redirect pasca-login diarahkan ke `/dashboard` yang secara dinamis forward ke `{role}.dashboard` berdasarkan nilai `auth()->user()->role`.
- Tidak ada OAuth, API token, atau JWT — murni session-based Breeze.

---

## Alur Status RAB

```
[Pengusul buat RAB]
        |
        v
  pending_kaprodi
        |
   Kaprodi review
   /           \
approve        revisi
   |              \
pending_wd      [kembali ke pengusul]
   |
  WD Keuangan review
  /          \
approve      tolak
   |            \
pending_dekan  [ditolak]
   |
  Dekan review + e-sign
        |
     disetujui
        |
   [Event: RabProposalApproved]
        |
   [Listener: SyncAssetsOnApproval]
        |
   rab_details → assets
```
