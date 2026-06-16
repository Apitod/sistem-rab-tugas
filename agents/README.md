# Agents — Sistem Informasi RAB Jurusan

Folder ini berisi definisi subagent untuk proyek prototipe Laravel RAB Jurusan (task.md).

## Daftar Agent

| File | Agent | Model | Prioritas |
|------|-------|-------|-----------|
| `04-architecture-expert.md` | Architecture Expert | **claude-sonnet-4** | **1 — PERTAMA** |
| `05-database-expert.md` | Database Expert | gemini-3.5-flash-medium | 2 |
| `03-backend-specialist.md` | Backend Specialist | **claude-sonnet-4** | 3 |
| `06-laravel-expert.md` | Laravel Expert | gemini-3.5-flash-medium | 4 |
| `02-uiux-designer.md` | UI/UX Designer | **claude-sonnet-4** | 5 (paralel dengan 4) |
| `01-frontend-designer.md` | Frontend Designer | **claude-sonnet-4** | 6 — TERAKHIR |

## Model yang Tersedia

| Model | Kapan Dipakai |
|-------|---------------|
| `claude-sonnet-4` | Reasoning berat: arsitektur, backend logic, UI/UX, frontend Blade |
| `gemini-3.5-flash-medium` | Well-defined tasks: DB migration/model, Laravel config/routing |
| `deepseek-v4-flash-free` | Boilerplate, scaffolding, draft awal murah |
| `deepseek-3.2` | Alternatif untuk tugas ringan / query generation |

## Urutan Eksekusi

```
[04] Architecture Expert   ← wajib selesai PERTAMA
         |
    ┌────┴────┐
[05] DB Expert   [02] UI/UX Designer   ← paralel
    └────┬────┘
         |
[03] Backend Specialist
         |
[06] Laravel Expert   ← integrasi semua komponen
         |
[01] Frontend Designer   ← selesai TERAKHIR
```

## Cara Memanggil Agent

Dari Hermes sebagai orchestrator, gunakan `delegate_task`:

```python
delegate_task(
    goal="Baca file agents/04-architecture-expert.md dan jalankan semua instruksi di dalamnya.",
    context="Proyek path: /home/dzul/Documents/tugas-apsi-web/. Baca task.md untuk spec.",
    toolsets=["terminal", "file"]
)
```

## Referensi Proyek

- Spec: `/home/dzul/Documents/tugas-apsi-web/task.md`
- DFD: `/home/dzul/Documents/tugas-apsi-web/DFD Kelompok 3.pdf`
- Framework: Laravel + MySQL (localhost)
- 5 role: pengusul, kaprodi, wd_keuangan, dekan, tata_usaha
