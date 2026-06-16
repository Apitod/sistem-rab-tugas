# Spesifikasi Implementasi Prototipe: Sistem Informasi RAB Jurusan

> [!IMPORTANT]
> **PETUNJUK UNTUK AI PENGEMBANG (DEVELOPER AI):**
> Sebelum memulai implementasi kode, Anda **WAJIB** membaca dan memahami berkas DFD (Data Flow Diagram) resmi yang terletak di:
> `[DFD Kelompok 3.pdf](file:///home/dzul/Documents/tugas-apsi-web/DFD Kelompok 3.pdf)`
> Berkas tersebut berisi alur data, entitas, proses fungsional, dan data store lengkap yang menjadi acuan utama pengembangan sistem ini.

---

## 1. Ringkasan Proyek & Pemahaman (Understanding Summary)
Dokumen ini mendefinisikan spesifikasi teknis untuk membangun prototipe fungsional **Sistem Informasi Rencana Anggaran Biaya (RAB) Jurusan** (Studi Kasus: Jurusan Sistem Informasi UIN Alauddin Makassar). 

* **Tujuan Utama:** Mendigitalisasi proses pengajuan, verifikasi, persetujuan, monitoring, dan pelaporan anggaran belanja jurusan yang sebelumnya manual menggunakan Excel dan berkas fisik.
* **Target Pengguna (5 Peran):**
  1. **Pengusul (Prodi/Lab/HMJ):** Input formulir pengajuan, unggah TOR, susun item anggaran, serta pantau status pengajuan.
  2. **Kaprodi:** Melakukan verifikasi awal usulan dari unitnya, pantau realisasi anggaran secara real-time, dan menerima peringatan dini (*early warning*).
  3. **Wakil Dekan Bidang Keuangan (WD II):** Melakukan verifikasi lanjutan terkait pagu anggaran fakultas dan kebijakan keuangan.
  4. **Dekan:** Melakukan review akhir, memberikan persetujuan dengan *e-signature*, dan menetapkan nomor RAB resmi.
  5. **Tata Usaha (TU) Jurusan:** Menerima kompilasi laporan bulanan, mencetak arsip digital, dan mengelola sinkronisasi modul aset.

---

## 2. Asumsi & Batasan Prototipe (NFRs & Constraints)
* **Lingkungan Jalur Cepat:** Aplikasi dikembangkan menggunakan framework **Laravel** dan database **MySQL** yang berjalan di localhost.
* **Sistem Notifikasi:** Seluruh notifikasi (revisi, status persetujuan, early warning pagu) disimulasikan melalui panel dashboard pemberitahuan di dalam aplikasi (tidak menggunakan API eksternal seperti SMTP nyata atau WA Gateway).
* **E-Signature:** Menggunakan pustaka JavaScript Canvas (seperti `signature_pad`) di sisi klien. Hasil coretan tangan Dekan disimpan sebagai gambar Base64 atau berkas `.png` di server dan ditampilkan pada dokumen RAB final.
* **Sinkronisasi Aset:** Proses sinkronisasi belanja pengadaan ke modul aset disederhanakan dengan menyalin item pengadaan langsung ke tabel `assets` di dalam database yang sama.

---

## 3. Log Keputusan (Decision Log)

| Keputusan Desain | Alternatif yang Dipertimbangkan | Alasan Memilih Opsi Ini |
| :--- | :--- | :--- |
| **Framework: Laravel** | PHP Native, Flask (Python) | Laravel menyediakan sistem autentikasi bawaan (*Auth*), penanganan routing yang rapi, migrasi database, dan struktur MVC yang sangat terorganisir untuk mahasiswa. |
| **Tanda Tangan Elektronik: Canvas Pad** | Stamp tombol teks biasa, tanda tangan kriptografis/QR | Canvas pad memberikan visualisasi tanda tangan digital yang lebih interaktif dan representatif untuk kebutuhan sidang/presentasi prototipe. |
| **Notifikasi: Dashboard Alerts** | WhatsApp API (Fonnte), Mailtrap SMTP | Mengurangi kompleksitas konfigurasi API eksternal dan ketergantungan koneksi internet saat demonstrasi aplikasi dijalankan lokal. |
| **Sinkronisasi Aset: Tabel Tunggal (Unified DB)** | REST API Mocking | Mengurangi overhead pembuatan API endpoint terpisah dengan langsung memasukkan data ke tabel aset lokal. |

---

## 4. Perancangan Database (Database Schema)

Berikut adalah struktur tabel MySQL yang direkomendasikan untuk diimplementasikan melalui Laravel Migrations:

### a. Tabel `users`
Menyimpan data akun pengguna dan perannya.
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('pengusul', 'kaprodi', 'wd_keuangan', 'dekan', 'tata_usaha') NOT NULL,
    unit VARCHAR(100) NULL, -- Contoh: 'Prodi SI', 'HMJ SI', 'Lab Komputer'
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### b. Tabel `rab_proposals`
Menyimpan data induk pengajuan RAB (merepresentasikan data store D1, D3).
```sql
CREATE TABLE rab_proposals (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL, -- Nama kegiatan
    proposed_date DATE NOT NULL,
    total_budget DECIMAL(15, 2) DEFAULT 0.00,
    status ENUM('pending_kaprodi', 'pending_wd', 'pending_dekan', 'disetujui', 'revisi', 'ditolak') DEFAULT 'pending_kaprodi',
    tor_file_path VARCHAR(255) NOT NULL, -- Path file PDF dokumen TOR
    rab_number VARCHAR(100) NULL, -- Nomor RAB Resmi (diberikan saat persetujuan Dekan)
    signature_path VARCHAR(255) NULL, -- Path gambar tanda tangan Dekan
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### c. Tabel `rab_details`
Menyimpan rincian item anggaran dari setiap pengajuan RAB (D1.3).
```sql
CREATE TABLE rab_details (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    rab_proposal_id BIGINT UNSIGNED NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    unit VARCHAR(50) NOT NULL, -- Contoh: 'Paket', 'Unit', 'Rim'
    unit_price DECIMAL(15, 2) NOT NULL,
    total_price DECIMAL(15, 2) GENERATED ALWAYS AS (quantity * unit_price) STORED,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (rab_proposal_id) REFERENCES rab_proposals(id) ON DELETE CASCADE
);
```

### d. Tabel `verification_logs`
Menyimpan riwayat verifikasi dan catatan revisi (D2).
```sql
CREATE TABLE verification_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    rab_proposal_id BIGINT UNSIGNED NOT NULL,
    verifier_id BIGINT UNSIGNED NOT NULL,
    status_checked ENUM('verifikasi_ok', 'revisi', 'ditolak') NOT NULL,
    notes TEXT NULL, -- Berisi detail catatan revisi
    created_at TIMESTAMP NULL,
    FOREIGN KEY (rab_proposal_id) REFERENCES rab_proposals(id) ON DELETE CASCADE,
    FOREIGN KEY (verifier_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### e. Tabel `notifications`
Menyimpan data notifikasi alur kerja (D2.3).
```sql
CREATE TABLE notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL, -- Penerima notifikasi
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### f. Tabel `assets`
Menyimpan data aset hasil sinkronisasi otomatis belanja pengadaan (D5).
```sql
CREATE TABLE assets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    rab_proposal_id BIGINT UNSIGNED NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    unit VARCHAR(50) NOT NULL,
    price DECIMAL(15, 2) NOT NULL,
    synced_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rab_proposal_id) REFERENCES rab_proposals(id) ON DELETE CASCADE
);
```

---

## 6. Fitur Utama & Panduan Alur Kerja

### 1. Pengajuan RAB (Peran: Pengusul)
* Form input rincian kegiatan dan pengunggahan berkas TOR.
* Antarmuka dinamis menggunakan JavaScript untuk menambah baris item anggaran (Nama item, Volume, Satuan, Harga Satuan) secara real-time sebelum dikirim.
* Setelah diajukan, status awal diset ke `'pending_kaprodi'`.

### 2. Verifikasi Bertingkat (Peran: Kaprodi & Wakil Dekan II)
* **Dashboard Kaprodi:** Melihat daftar pengajuan baru dari unitnya. Kaprodi dapat memilih opsi `Verifikasi` (diteruskan ke WD II dengan status `'pending_wd'`) atau opsi `Minta Revisi` (mengisi form catatan revisi, mengubah status ke `'revisi'`).
* **Dashboard WD II:** Melihat daftar pengajuan yang sudah diverifikasi Kaprodi. WD II dapat melakukan verifikasi (diteruskan ke Dekan dengan status `'pending_dekan'`) atau menolak/meminta revisi jika melebihi pagu fakultas.

### 3. Persetujuan Akhir & E-Signature (Peran: Dekan)
* **Dashboard Dekan:** Melihat daftar pengajuan berstatus `'pending_dekan'`.
* Pada halaman detail pengajuan, Dekan memiliki form persetujuan yang terintegrasi dengan **HTML5 Canvas Drawing Pad**.
* Dekan menandatangani menggunakan mouse/touchpad pada canvas, memasukkan Nomor RAB Resmi, lalu menekan tombol `Setujui`.
* Sistem menyimpan tanda tangan sebagai berkas `.png` dan mengubah status pengajuan menjadi `'disetujui'`.

### 4. Pelaporan, Sinkronisasi Aset & Early Warning (Peran: Tata Usaha & Kaprodi)
* **Sinkronisasi Otomatis:** Ketika Dekan menyetujui pengajuan, aplikasi harus memicu *Database Event* atau logika Controller untuk menyalin seluruh detail item dari `rab_details` ke tabel `assets`.
* **Ekspor Laporan (TU):** TU dapat memfilter pengajuan berdasarkan rentang tanggal/bulan dan mengunduhnya dalam format cetak (PDF) atau spreadsheet (Excel).
* **Early Warning System (EWS):** Pada Dashboard Kaprodi, terdapat visualisasi batas pagu tahunan. Jika akumulasi total realisasi RAB yang disetujui mencapai 80% atau melebihi 100% dari pagu, tampilkan peringatan visual berupa bar berwarna merah menyala dan banner peringatan di atas dashboard.
