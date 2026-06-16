# UX Copy (Teks Antarmuka) — Sistem Informasi RAB Jurusan

Dokumen ini berisi seluruh teks UX dalam bahasa Indonesia yang digunakan di seluruh sistem.

---

## 1. Label Tombol

### Tombol Umum
| Tombol                | Teks                       | Varian Design |
|-----------------------|----------------------------|---------------|
| Simpan               | Simpan                     | Primary       |
| Batal                | Batal                      | Ghost         |
| Kembali              | Kembali                    | Outline       |
| Hapus                | Hapus                      | Danger        |
| Ubah                 | Ubah                       | Secondary     |
| Lihat Detail        | Lihat Detail               | Ghost         |
| Tutup                | Tutup                      | Ghost         |

### Tombol Aksi pada Form Pengajuan RAB
| Tombol                | Teks                       | Varian Design |
|-----------------------|----------------------------|---------------|
| Ajukan RAB           | Ajukan RAB                 | Primary       |
| Ajukan Kembali       | Ajukan Kembali             | Primary       |
| Simpan Draf          | Simpan Draf                | Outline       |
| Tambah Item          | + Tambah Item              | Secondary     |
| Hapus Item           | Hapus                      | Danger (sm)   |

### Tombol Aksi Verifikasi & Persetujuan
| Tombol                | Teks                       | Varian Design |
|-----------------------|----------------------------|---------------|
| Verifikasi           | Verifikasi                 | Success       |
| Minta Revisi        | Minta Revisi               | Warning       |
| Setujui & Tandatangani | Setujui & Tandatangani   | Success       |
| Tolak                | Tolak                      | Danger        |
| Detail & Verifikasi  | Detail & Verifikasi        | Secondary     |

### Tombol Ekspor & Pelaporan
| Tombol                | Teks                       | Varian Design |
|-----------------------|----------------------------|---------------|
| Export PDF           | Export PDF                 | Outline       |
| Cetak Laporan        | Cetak Laporan              | Primary       |
| Download Arsip       | Download Arsip             | Outline       |

---

## 2. Placeholder Form

### Input Teks
| Field                      | Placeholder                                       |
|----------------------------|---------------------------------------------------|
| Judul Kegiatan             | Masukkan judul kegiatan                           |
| Nama Item Anggaran         | Masukkan nama item anggaran                       |
| Volume                     | 0                                                 |
| Harga Satuan               | Rp                                                |
| Deskripsi                  | Masukkan deskripsi kegiatan                       |
| Keterangan Revisi          | Tuliskan keterangan revisi                        |
| Catatan (pemeriksa)        | Tulis catatan untuk pengusul...                   |
| Upload TOR                 | Unggah file TOR (PDF, DOCX, maks. 5 MB)           |

### Pencarian
| Konteks                    | Placeholder                                       |
|----------------------------|---------------------------------------------------|
| Cari RAB                  | Cari berdasarkan judul kegiatan atau pengusul...  |
| Cari Item                  | Cari item...                                      |
| Filter Status              | Pilih status...                                   |

### Dropdown / Select
| Dropdown                   | Placeholder                                       |
|----------------------------|---------------------------------------------------|
| Satuan                     | Pilih satuan                                      |
| Fakultas                   | Pilih fakultas                                    |
| Jurusan                    | Pilih jurusan                                     |
| Program Studi              | Pilih program studi                               |
| Tahun Anggaran             | Pilih tahun anggaran                              |

### Input Tanggal & File
| Field                      | Teks                                                    |
|----------------------------|---------------------------------------------------------|
| Tanggal Kegiatan           | Pilih tanggal                                           |
| Upload TOR                 | Seret file ke sini atau klik untuk unggah               |
| Upload Lampiran            | Unggah file pendukung (maks. 5 MB, format: PDF/DOCX)    |
| Tanda Tangan (E-Sign)      | Klik untuk menandatangani                               |

---

## 3. Error Messages

### 3.1 Validasi Form (Client-side)

| Kondisi                           | Teks Error                                              |
|-----------------------------------|---------------------------------------------------------|
| Judul kegiatan kosong             | Judul kegiatan wajib diisi                              |
| Judul kegiatan terlalu panjang    | Judul kegiatan maksimal 255 karakter                    |
| Tanggal tidak diisi               | Tanggal kegiatan wajib dipilih                          |
| File TOR tidak diunggah           | File TOR wajib diunggah                                 |
| File TOR melebihi 5 MB            | Ukuran file tidak boleh melebihi 5 MB                   |
| File TOR bukan PDF/DOCX           | Format file harus PDF atau DOCX                         |
| Nama item kosong                  | Nama item anggaran wajib diisi                          |
| Volume bukan angka positif        | Volume harus berupa angka positif                       |
| Harga satuan bukan angka positif  | Harga satuan harus berupa angka positif                 |
| Tidak ada item anggaran           | Minimal satu item anggaran harus ditambahkan            |
| Total anggaran melebihi pagu      | Total anggaran melebihi sisa pagu yang tersedia         |
| Email tidak valid                 | Format email tidak valid                                |
| Password terlalu pendek           | Password minimal 8 karakter                             |

### 3.2 Error Akses & Otorisasi

| Kondisi                           | Teks Error                                              |
|-----------------------------------|---------------------------------------------------------|
| Login gagal (email/password salah)| Email atau password salah. Silakan coba kembali.        |
| Sesi habis                        | Sesi Anda telah habis. Silakan login kembali.            |
| Tidak punya akses                 | Anda tidak memiliki akses ke halaman ini.                |
| Tidak bisa mengedit RAB orang lain| Anda hanya dapat mengedit pengajuan Anda sendiri.        |
| RAB sudah diverifikasi            | RAB ini sudah diproses dan tidak dapat diubah.           |
| RAB sudah ditolak                 | RAB ini sudah ditolak dan tidak dapat diedit.            |
| Tidak bisa verifikasi sendiri     | Anda tidak dapat memverifikasi pengajuan sendiri.        |

### 3.3 Error File / Upload

| Kondisi                           | Teks Error                                              |
|-----------------------------------|---------------------------------------------------------|
| Upload gagal                      | Gagal mengunggah file. Silakan coba lagi.               |
| Tipe file tidak diizinkan         | Tipe file tidak diizinkan. Unggah file PDF atau DOCX.   |
| Ukuran file melebihi batas        | Ukuran file terlalu besar. Maksimal 5 MB.               |
| File tidak ditemukan saat unduh   | File tidak ditemukan. Mungkin telah dihapus.            |
| Gagal generate PDF                | Gagal membuat PDF. Silakan coba beberapa saat lagi.     |

### 3.4 Error Sistem / Jaringan

| Kondisi                           | Teks Error                                              |
|-----------------------------------|---------------------------------------------------------|
| Koneksi terputus                  | Koneksi internet terputus. Periksa koneksi Anda.        |
| Server error (500)                | Terjadi kesalahan pada server. Silakan coba lagi nanti. |
| Timeout                           | Waktu permintaan habis. Silakan coba lagi.              |
| Database error                    | Terjadi kesalahan sistem. Hubungi administrator.        |

---

## 4. Success Messages / Toast

### 4.1 Toast (Notifikasi Pop-up)

| Aksi                                    | Teks Toast                                                    |
|-----------------------------------------|---------------------------------------------------------------|
| RAB berhasil diajukan                   | RAB berhasil diajukan. Menunggu verifikasi Kaprodi.          |
| RAB berhasil diajukan kembali           | RAB berhasil diajukan kembali setelah revisi.                |
| Draf RAB berhasil disimpan              | Draf berhasil disimpan.                                       |
| Verifikasi berhasil                     | RAB berhasil diverifikasi. Dokumen diteruskan ke WD II.      |
| Verifikasi keuangan berhasil            | RAB berhasil diverifikasi. Dokumen diteruskan ke Dekan.      |
| RAB berhasil disetujui & ditandatangani | RAB berhasil disetujui dan ditandatangani secara elektronik. |
| Permintaan revisi terkirim              | Permintaan revisi berhasil dikirim ke pengusul.               |
| RAB ditolak                             | RAB berhasil ditolak.                                         |
| RAB berhasil diupdate                   | Data RAB berhasil diperbarui.                                 |
| Item anggaran berhasil ditambahkan      | Item anggaran berhasil ditambahkan.                           |
| Item anggaran berhasil dihapus          | Item anggaran berhasil dihapus.                               |
| File berhasil diunggah                  | File berhasil diunggah.                                       |
| Export PDF berhasil                     | PDF berhasil diexport. Silakan unduh file.                    |
| Data berhasil disimpan                  | Data berhasil disimpan.                                       |

### 4.2 Banner Notifikasi

| Aksi                                    | Teks Banner                                                   |
|-----------------------------------------|---------------------------------------------------------------|
| RAB baru masuk untuk verifikasi         | Ada pengajuan RAB baru yang menunggu verifikasi Anda.         |
| Revisi RAB diperlukan                   | RAB "#Judul" memerlukan revisi. Silakan cek detail.           |
| RAB telah disetujui                     | RAB "#Judul" telah disetujui oleh Dekan.                      |
| RAB ditolak                             | RAB "#Judul" telah ditolak.                                   |
| Pagu anggaran mendekati batas           | Peringatan: Pagu anggaran jurusan telah mencapai 80%!         |

---

## 5. Empty State (Teks saat tidak ada data)

| Halaman / Section                    | Teks Empty State                                              | Ikon Ilustrasi |
|--------------------------------------|---------------------------------------------------------------|----------------|
| Daftar RAB (Pengusul)                | Belum ada pengajuan RAB. Klik "Ajukan RAB" untuk memulai.     | Document +     |
| Daftar RAB (Kaprodi)                 | Tidak ada pengajuan RAB yang perlu diverifikasi saat ini.     | Checkmark      |
| Daftar RAB (WD II)                   | Belum ada pengajuan RAB yang memerlukan verifikasi keuangan.  | Wallet         |
| Daftar RAB (Dekan)                   | Tidak ada pengajuan RAB yang memerlukan persetujuan saat ini. | Stamp          |
| Daftar RAB (TU)                      | Belum ada RAB yang disetujui untuk dicetak.                   | Printer        |
| Hasil pencarian                      | Tidak ditemukan RAB dengan kata kunci "{keyword}".            | Search (X)     |
| Item anggaran (form kosong)          | Belum ada item anggaran. Klik "+ Tambah Item" untuk mulai.    | Plus           |
| Notifikasi                           | Belum ada notifikasi baru.                                    | Bell           |

---

## 6. Konfirmasi Modal (Dialog)

### 6.1 Modal Aksi Verifikasi / Persetujuan

** Judul Modal: Verifikasi RAB
**Isi:** Apakah Anda yakin ingin memverifikasi RAB "{judul kegiatan}"? Dokumen akan diteruskan ke tahap berikutnya.

**Tombol:** [Batal] [Verifikasi]

---

** Judul Modal: Setujui & Tandatangani
**Isi:** Dengan menekan "Setujui & Tandatangani", Anda menyetujui RAB "{judul kegiatan}" secara hukum dan sah secara elektronik. Tanda tangan digital akan dibubuhkan pada dokumen.

**Tombol:** [Batal] [Setujui & Tandatangani]

---

** Judul Modal: Minta Revisi
**Isi:** Apakah Anda yakin ingin meminta revisi untuk RAB "{judul kegiatan}"? Pengusul akan diminta untuk melakukan perbaikan.

**Tombol:** [Batal] [Ya, Minta Revisi]

---

** Judul Modal: Tolak RAB
**Isi:** Apakah Anda yakin ingin MENOLAK RAB "{judul kegiatan}"? Keputusan ini bersifat final dan tidak dapat dibatalkan. Pengusul harus membuat pengajuan baru.

**Tombol:** [Batal] [Tolak RAB]

---

** Judul Modal: Hapus Item Anggaran
**Isi:** Apakah Anda yakin ingin menghapus item "{nama item}"? Tindakan ini tidak dapat dibatalkan.

**Tombol:** [Batal] [Hapus]

---

** Judul Modal: Hapus Draf RAB
**Isi:** Apakah Anda yakin ingin menghapus draf RAB "{judul kegiatan}"? Seluruh data akan hilang secara permanen.

**Tombol:** [Batal] [Hapus Draf]

---

### 6.2 Modal Informasi

** Judul Modal: Berhasil Diajukan
**Isi:** RAB "{judul kegiatan}" berhasil diajukan. Silakan pantau status pengajuan pada menu "RAB Saya".

**Tombol:** [OK]

---

** Judul Modal: Berhasil Disetujui
**Isi:** Selamat! RAB "{judul kegiatan}" telah disetujui dan ditandatangani secara elektronik oleh Dekan. Dokumen siap dicetak oleh Tata Usaha.

**Tombol:** [OK]
