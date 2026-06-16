# Konvensi Kode Proyek

## Penamaan File & Class (Naming Convention)

### Controller
- Nama: PascalCase + `Controller` suffix
- Contoh: `RabController`, `LaporanController`, `AsetController`
- Lokasi: `app/Http/Controllers/{Role}/`
- Resource method standar: `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`

### Model
- Nama: PascalCase singular
- Contoh: `RabProposal`, `RabDetail`, `VerificationLog`, `Notification`, `Asset`
- Lokasi: `app/Models/`
- Kolom database & relationship method: snake_case (e.g. `rabProposal()`, `verificationLogs()`)

### Migration
- Nama: snake_case
- Contoh: `2026_06_16_000001_create_rab_proposals_table`
- Lokasi: `database/migrations/`

### Service
- Nama: PascalCase + `Service` suffix
- Contoh: `RabWorkflowService`, `AssetSyncService`, `NotificationService`
- Lokasi: `app/Services/`

### Event & Listener
- Event: PascalCase, kata kerja lampau. Contoh: `RabProposalApproved`
- Listener: PascalCase, format `Action` + `On` + `Event`. Contoh: `SyncAssetsOnApproval`

### Request (Validation)
- Nama: PascalCase + `Request` suffix
- Contoh: `StoreRabRequest`, `VerifyRabRequest`
- Lokasi: `app/Http/Requests/`

---

## Standar Kode (Coding Standards)

- **Return Type Hints**: Wajib dideklarasikan pada semua method Controller, Service, Event, Listener, dan Model.
  ```php
  public function verify(VerifyRabRequest $request, int $id): RedirectResponse
  ```
- **Docblocks**: Wajib ditulis untuk method publik yang kompleks. Terutama untuk Service layer guna menjelaskan parameter input/output.
  ```php
  /**
   * Melakukan verifikasi RAB dari pihak Kaprodi.
   * 
   * @param RabProposal $rab
   * @param string $action ('approve' atau 'revisi')
   * @param string|null $catatan
   * @return void
   */
  ```
- **Variabel**: Wajib menggunakan `camelCase` (e.g. `$rabProposal`, `$verificationLog`).
- **Strict Types**: Letakkan `declare(strict_types=1);` di bagian atas file PHP untuk Service classes.

---

## Format Commit Git (Git Commit Format)

Mengikuti pola Conventional Commits:
`type(scope): deskripsi`

### Tipe yang Diperbolehkan:
- `feat`: Fitur baru (misal: tambah tanda tangan digital)
- `fix`: Perbaikan bug (misal: perbaikan query early warning pagu)
- `chore`: Maintenance/setup (misal: install dompdf, update package)
- `docs`: Dokumentasi (misal: edit conventions.md)
- `refactor`: Perubahan kode tanpa merubah fungsi (misal: restrukturisasi controller)

### Contoh Commit:
```bash
git commit -m "feat(auth): add Breeze login integration and CheckRole middleware"
git commit -m "fix(rab): fix early warning threshold logic in RabWorkflowService"
git commit -m "docs(arch): update folder structure in architecture.md"
```

---

## Blade Naming & Folder Structure

- Nama file Blade: lowercase-hyphen.
- Contoh: `index.blade.php`, `create.blade.php`, `show.blade.php`, `export-excel.blade.php`
- Letakkan di folder sesuai fitur dan sub-folder role:
  - `resources/views/pengusul/rab/index.blade.php`
  - `resources/views/dekan/rab/show.blade.php`
  - `resources/views/tu/laporan/index.blade.php`

---

## Route Naming (Pola Route)

- Format nama route: `{role}.{resource}.{action}`
- Selalu gunakan `route()` helper di Blade/Controller.
- Daftar route names:
  - `pengusul.dashboard`
  - `pengusul.rab.index`
  - `pengusul.rab.create`
  - `pengusul.rab.store`
  - `pengusul.rab.show`
  - `kaprodi.dashboard`
  - `kaprodi.rab.index`
  - `kaprodi.rab.show`
  - `kaprodi.rab.verify`
  - `kaprodi.rab.revisi`
  - `wd.dashboard`
  - `wd.rab.index`
  - `wd.rab.show`
  - `wd.rab.verify`
  - `wd.rab.tolak`
  - `dekan.dashboard`
  - `dekan.rab.index`
  - `dekan.rab.show`
  - `dekan.rab.setujui`
  - `tu.dashboard`
  - `tu.laporan.index`
  - `tu.laporan.pdf`
  - `tu.laporan.excel`
  - `tu.aset.index`
  - `notifications.index`
  - `notifications.read`
