# Alur Pengguna (User Flow) — Sistem Informasi RAB Jurusan

Dokumen ini menjelaskan alur kerja (workflow) pengajuan, verifikasi, persetujuan, revisi, hingga pencetakan laporan Rencana Anggaran Biaya (RAB) Jurusan.

---

## 1. Peran Pengguna (Roles)

1. **Pengusul (Dosen/Sekretaris Jurusan)**: Membuat, mengedit, dan mengajukan draf RAB. Menindaklanjuti revisi.
2. **Ketua Program Studi (Kaprodi)**: Memeriksa kesesuaian kegiatan dengan kurikulum/target akademik, melakukan verifikasi tingkat pertama, meminta revisi, atau menolak.
3. **Wakil Dekan II (WD II - Bidang Keuangan)**: Memeriksa ketersediaan dan alokasi pagu anggaran fakultas/jurusan, melakukan verifikasi keuangan, meminta revisi, atau menolak.
4. **Dekan**: Memberikan persetujuan akhir (approval) dan menandatangani RAB secara elektronik (e-sign).
5. **Tata Usaha (TU)**: Memantau RAB disetujui, mencetak laporan resmi, dan mengekspor ke format PDF.

---

## 2. Diagram Alur Utama (ASCII)

```text
 [ Pengusul ]            [ Kaprodi ]           [ WD II ]            [ Dekan ]            [ TU ]
      │                       │                    │                    │                  │
 ┌────┴────┐                  │                    │                    │                  │
 │Buat RAB │                  │                    │                    │                  │
 └────┬────┘                  │                    │                    │                  │
      │                       │                    │                    │                  │
      ▼                       │                    │                    │                  │
 ┌─────────┐                  │                    │                    │                  │
 │ Kirim   ├─────────────────►│                    │                    │                  │
 └─────────┘                  │                    │                    │                  │
                              │                    │                    │                  │
                        ┌─────┴─────┐              │                    │                  │
                        │Verifikasi │              │                    │                  │
                        └─────┬─────┘              │                    │                  │
                              ├───────────┐        │                    │                  │
                       Revisi │           │ Setuju │                    │                  │
                              ▼           ▼        │                    │                  │
                        ┌───────────┐   ┌──────────┴┐                   │                  │
                        │Minta      │   │ Verifikasi├──────────────────┐│                  │
                        │Revisi     │   └─────┬─────┘                  ││                  │
                        └─────┬─────┘         ├───────────┐            ││                  │
                              │        Revisi │           │ Setuju     ││                  │
                              │               ▼           ▼            ││                  │
                              │         ┌───────────┐   ┌──────────────┴┴┐                 │
                              │         │Minta      │   │Persetujuan Akhir│                 │
                              │         │Revisi     │   └──────┬─────────┘                 │
                              │         └─────┬─────┘          ├───────────┐               │
                              │               │         Tolak  │           │ Setuju+eSign  │
                              │               │                ▼           ▼               │
                              │               │         ┌───────────┐  ┌──────────────────┐│
                              │               │         │  Ditolak  │  │   Disetujui      ├┼──┐
                              │               │         └───────────┘  └─────────┬────────┘│  │
                              │               │                                  │         │  │
                              ▼               ▼                                  │         │  │
                        ┌───────────────────────────┐                            │         │  │
                        │   Draf Kembali/Revisi     │◄───────────────────────────┘         │  │
                        └─────────────┬─────────────┘                                      │  │
                                      │                                                    │  │
                                      ▼                                                    │  │
                                  [ Perbaiki ]                                             │  │
                                                                                           ▼  ▼
                                                                                   ┌──────────┐
                                                                                   │Cetak &   │
                                                                                   │Ekspor PDF│
                                                                                   └──────────┘
```

---

## 3. Detail Langkah per Langkah

### Langkah 1: Pengajuan RAB oleh Pengusul
- **Aksi**: Pengusul mengisi Form Pengajuan RAB di sistem.
  - Form berisi data umum: Judul Kegiatan, Tanggal, File TOR (Term of Reference).
  - Pengusul menambahkan baris anggaran dinamis: Nama Item, Volume, Satuan, Harga Satuan.
  - Total budget dihitung secara real-time.
- **Validasi**:
  - Semua field wajib diisi.
  - Jumlah anggaran tidak boleh melebihi pagu internal jurusan.
- **Status Akhir**: RAB berstatus **"Pending Kaprodi"** (Menunggu Verifikasi Kaprodi). Notifikasi dikirim ke Kaprodi.

### Langkah 2: Verifikasi oleh Kaprodi
- **Aksi**: Kaprodi membuka daftar pengajuan RAB pending pada dashboard.
  - Kaprodi memeriksa detail isi pengajuan dan kelayakan program kegiatan.
  - Kaprodi memilih opsi aksi:
    - **Verifikasi**: Melanjutkan dokumen ke tingkat fakultas.
    - **Minta Revisi**: Mengembalikan ke pengusul disertai catatan perbaikan.
    - **Tolak**: Menolak pengajuan dan menutup dokumen (tidak bisa diajukan kembali).
- **Status Akhir**:
  - Jika diverifikasi: status berubah menjadi **"Pending WD II"**. Notifikasi dikirim ke WD II.
  - Jika diminta revisi: status menjadi **"Revisi Kaprodi"**. Kembali ke pengusul.
  - Jika ditolak: status menjadi **"Ditolak Kaprodi"**.

### Langkah 3: Verifikasi Keuangan oleh WD II
- **Aksi**: WD II memeriksa kelayakan keuangan dan ketersediaan pagu fakultas.
  - Pada dashboard WD II terdapat *Early Warning Bar* pagu total. Jika pengajuan ini melebihi ambang batas (contoh: 80% dari pagu tersisa), bar akan berwarna merah menyala.
  - WD II memilih opsi aksi:
    - **Verifikasi**: Melanjutkan dokumen ke Dekan.
    - **Minta Revisi**: Mengembalikan dokumen ke pengusul dengan catatan anggaran.
    - **Tolak**: Menolak pengajuan.
- **Status Akhir**:
  - Jika diverifikasi: status berubah menjadi **"Pending Dekan"**. Notifikasi dikirim ke Dekan.
  - Jika diminta revisi: status menjadi **"Revisi WD II"**. Kembali ke pengusul.
  - Jika ditolak: status menjadi **"Ditolak WD II"**.

### Langkah 4: Persetujuan & Tanda Tangan Elektronik oleh Dekan
- **Aksi**: Dekan meninjau pengajuan akhir yang telah diverifikasi Kaprodi dan WD II.
  - Dekan menyetujui pengajuan dengan menekan tombol **"Setujui & Tandatangani"**.
  - Sistem menghasilkan tanda tangan elektronik (QR Code / Hash e-sign) yang akan disematkan ke dalam berkas PDF.
  - Dekan juga memiliki opsi untuk **Minta Revisi** atau **Tolak** jika mendeteksi ketidaksesuaian kebijakan tingkat universitas.
- **Status Akhir**:
  - Jika disetujui: status berubah menjadi **"Disetujui"** (Status Final). Notifikasi dikirim ke Pengusul dan Tata Usaha.
  - Jika diminta revisi: status menjadi **"Revisi Dekan"**.
  - Jika ditolak: status menjadi **"Ditolak"**.

### Langkah 5: Pencetakan Laporan oleh Tata Usaha (TU)
- **Aksi**: Tata Usaha melihat daftar RAB yang berstatus **"Disetujui"**.
  - TU mengklik tombol **"Export PDF"** untuk mengunduh dokumen RAB resmi yang sudah dibubuhi Tanda Tangan Elektronik (TTE) Dekan.
  - Dokumen dicetak untuk arsip fisik atau lampiran pengajuan pencairan dana ke bagian keuangan universitas.

---

## 4. Penanganan Alur Khusus

### 4.1 Alur Revisi
1. Dokumen yang ditandai **"Revisi"** akan kembali ke menu "RAB Saya" milik Pengusul dengan indikasi warna oranye.
2. Pengusul wajib membuka detail dokumen, membaca "Catatan Revisi" dari pemeriksa (Kaprodi / WD II / Dekan).
3. Pengusul mengedit bagian yang salah (misal: mengurangi harga satuan item, mengubah volume, atau merevisi detail judul).
4. Pengusul menekan tombol **"Ajukan Kembali"**.
5. Proses verifikasi diulang dari tingkat pertama (Kaprodi), kecuali sistem dikonfigurasi untuk langsung ke pihak yang meminta revisi (opsional, default: ulang dari Kaprodi untuk validasi konsistensi).

### 4.2 Alur Penolakan (Tolak)
1. Dokumen yang berstatus **"Ditolak"** tidak dapat diedit kembali oleh Pengusul.
2. Dokumen tetap diarsipkan di sistem untuk riwayat audit dengan status warna merah.
3. Pengusul harus membuat draf pengajuan baru dari awal jika ingin mengajukan kegiatan alternatif.
