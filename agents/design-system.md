# Design System — Sistem Informasi RAB Jurusan

Versi: 1.0.0
Dibuat: Juni 2026
Penulis: Tim UI/UX

---

## 1. Palet Warna

### Warna Utama

| Nama        | Kode Hex  | Penggunaan                                  |
|-------------|-----------|---------------------------------------------|
| Primary     | `#1e3a5f` | Sidebar, header utama, tombol primary       |
| Secondary   | `#2563eb` | Link, tombol aksi sekunder, highlight       |
| Success     | `#16a34a` | Badge disetujui, notifikasi sukses, alert OK|
| Warning     | `#d97706` | Badge revisi, peringatan anggaran mendekati pagu |
| Danger      | `#dc2626` | Badge ditolak, early warning pagu terlampaui, error |
| Neutral     | `#6b7280` | Teks sekunder, border, placeholder          |

### Skala Abu-abu (Neutral Gray)

| Token         | Nilai     | Contoh Penggunaan               |
|---------------|-----------|---------------------------------|
| gray-50       | `#f9fafb` | Background halaman              |
| gray-100      | `#f3f4f6` | Background tabel baris genap    |
| gray-200      | `#e5e7eb` | Border, divider                 |
| gray-300      | `#d1d5db` | Input border default            |
| gray-400      | `#9ca3af` | Placeholder text                |
| gray-500      | `#6b7280` | Teks sekunder                   |
| gray-600      | `#4b5563` | Label form                      |
| gray-700      | `#374151` | Teks badan utama                |
| gray-800      | `#1f2937` | Header tabel, heading           |
| gray-900      | `#111827` | Teks heading utama              |

### Warna Status RAB

| Status          | Latar Belakang | Teks      | Border    |
|-----------------|----------------|-----------|-----------|
| Pending         | `#fef3c7`      | `#92400e` | `#d97706` |
| Disetujui       | `#dcfce7`      | `#14532d` | `#16a34a` |
| Revisi          | `#fff7ed`      | `#7c2d12` | `#ea580c` |
| Ditolak         | `#fee2e2`      | `#7f1d1d` | `#dc2626` |
| Pending Kaprodi | `#dbeafe`      | `#1e3a8a` | `#2563eb` |
| Pending Dekan   | `#ede9fe`      | `#4c1d95` | `#7c3aed` |

---

## 2. Tipografi

### Font Family

```
font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI',
             Roboto, Oxygen, Ubuntu, sans-serif;
```

Font sistem digunakan untuk performa loading optimal dan konsistensi antar OS.

### Skala Ukuran

| Token  | Ukuran | Line Height | Font Weight | Penggunaan                  |
|--------|--------|-------------|-------------|------------------------------|
| h1     | 32px   | 40px        | 700 (Bold)  | Judul halaman utama          |
| h2     | 28px   | 36px        | 700 (Bold)  | Judul seksi                  |
| h3     | 24px   | 32px        | 600 (Semi)  | Sub-judul, kartu             |
| h4     | 20px   | 28px        | 600 (Semi)  | Heading tabel, modal         |
| h5     | 18px   | 26px        | 600 (Semi)  | Label grup form              |
| h6     | 16px   | 24px        | 600 (Semi)  | Sub-label, sidebar nav item  |
| body   | 16px   | 24px        | 400 (Normal)| Teks isi utama               |
| small  | 14px   | 20px        | 400 (Normal)| Teks sekunder, caption tabel |
| xs     | 12px   | 16px        | 400 (Normal)| Badge, label kecil           |

---

## 3. Spacing (Sistem Spasi)

### Unit Dasar: 4px

Semua spasi menggunakan kelipatan 4px.

| Token  | Nilai | Kelas Tailwind | Penggunaan Umum              |
|--------|-------|----------------|-------------------------------|
| xs     | 4px   | `p-1 / m-1`   | Gap antar ikon dan teks       |
| sm     | 8px   | `p-2 / m-2`   | Padding badge, gap elemen     |
| md     | 12px  | `p-3 / m-3`   | Padding input field           |
| lg     | 16px  | `p-4 / m-4`   | Padding card, row tabel       |
| xl     | 24px  | `p-6 / m-6`   | Padding section               |
| 2xl    | 32px  | `p-8 / m-8`   | Padding halaman               |
| 3xl    | 48px  | `p-12 / m-12` | Jarak antar seksi besar       |
| 4xl    | 64px  | `p-16 / m-16` | Padding hero/login card       |

---

## 4. Komponen

### 4.1 Tombol (Button)

#### Varian Tombol

**Primary Button**
```
Background : #1e3a5f
Text       : #ffffff
Hover      : #162d4a (lebih gelap 10%)
Padding    : 10px 20px (py-2.5 px-5)
Border-radius : 6px (rounded-md)
Font-size  : 14px
Font-weight: 600
```

**Secondary Button**
```
Background : #2563eb
Text       : #ffffff
Hover      : #1d4ed8
Padding    : 10px 20px
Border-radius : 6px
```

**Success Button**
```
Background : #16a34a
Text       : #ffffff
Hover      : #15803d
```

**Warning Button (Minta Revisi)**
```
Background : #d97706
Text       : #ffffff
Hover      : #b45309
```

**Danger Button (Tolak)**
```
Background : #dc2626
Text       : #ffffff
Hover      : #b91c1c
```

**Outline Button**
```
Background : transparent
Text       : #1e3a5f
Border     : 1.5px solid #1e3a5f
Hover      : Background #1e3a5f, Text #ffffff
```

**Ghost Button**
```
Background : transparent
Text       : #6b7280
Hover      : Background #f3f4f6
```

**Disabled Button**
```
Background : #e5e7eb
Text       : #9ca3af
Cursor     : not-allowed
Opacity    : 0.6
```

#### Ukuran Tombol

| Ukuran | Padding       | Font Size | Penggunaan                |
|--------|---------------|-----------|---------------------------|
| sm     | 6px 12px      | 12px      | Aksi dalam baris tabel    |
| md     | 10px 20px     | 14px      | Tombol umum dalam form    |
| lg     | 12px 28px     | 16px      | CTA utama halaman         |

---

### 4.2 Badge Status RAB

Badge digunakan di kolom Status pada semua tabel RAB.

```
Border-radius : 9999px (pill shape)
Padding       : 4px 12px (py-1 px-3)
Font-size     : 12px
Font-weight   : 600
Display       : inline-flex
```

| Status          | Class Background | Class Text       |
|-----------------|------------------|------------------|
| Pending         | bg-yellow-100    | text-yellow-800  |
| Disetujui       | bg-green-100     | text-green-800   |
| Revisi          | bg-orange-100    | text-orange-800  |
| Ditolak         | bg-red-100       | text-red-800     |
| Pending Kaprodi | bg-blue-100      | text-blue-800    |
| Pending Dekan   | bg-purple-100    | text-purple-800  |

---

### 4.3 Form Input

```
Border        : 1px solid #d1d5db (gray-300)
Border-radius : 6px (rounded-md)
Padding       : 10px 14px (py-2.5 px-3.5)
Font-size     : 14px
Background    : #ffffff
Color         : #374151 (gray-700)
Placeholder   : #9ca3af (gray-400)

Focus:
  Border-color : #2563eb (secondary)
  Ring         : 3px rgba(37, 99, 235, 0.2)
  Outline      : none

Error State:
  Border-color : #dc2626
  Ring         : 3px rgba(220, 38, 38, 0.2)

Disabled State:
  Background   : #f3f4f6
  Cursor       : not-allowed
  Opacity      : 0.7
```

**Label Form**
```
Font-size   : 14px
Font-weight : 500
Color       : #374151
Margin-bottom : 6px
```

**Teks Error Validasi**
```
Font-size : 12px
Color     : #dc2626
Margin-top : 4px
```

---

### 4.4 Card

```
Background    : #ffffff
Border        : 1px solid #e5e7eb (gray-200)
Border-radius : 8px (rounded-lg)
Box-shadow    : 0 1px 3px rgba(0,0,0,0.1)
Padding       : 24px (p-6)
```

**Card dengan Header**
```
Header Padding  : 16px 24px (py-4 px-6)
Header Border-B : 1px solid #e5e7eb
Content Padding : 24px (p-6)
```

---

### 4.5 Sidebar Navigasi

```
Lebar        : 256px (w-64)
Background   : #1e3a5f (primary)
Height       : 100vh
Position     : fixed, kiri
Overflow-y   : auto
```

**Logo Area**
```
Padding      : 24px (p-6)
Border-B     : 1px solid rgba(255,255,255,0.1)
```

**Nav Item (Normal)**
```
Padding      : 10px 16px (py-2.5 px-4)
Color        : rgba(255,255,255,0.75)
Font-size    : 14px
Border-radius : 6px (rounded-md)
Margin       : 2px 8px
Display      : flex + items-center gap-3
```

**Nav Item (Aktif)**
```
Background   : rgba(255,255,255,0.15)
Color        : #ffffff
Font-weight  : 600
```

**Nav Item (Hover)**
```
Background   : rgba(255,255,255,0.1)
Color        : #ffffff
```

**Nav Section Label**
```
Padding      : 8px 16px (py-2 px-4)
Font-size    : 11px
Font-weight  : 700
Color        : rgba(255,255,255,0.4)
Text-transform : uppercase
Letter-spacing : 0.05em
```

---

### 4.6 Notifikasi Bell (Header)

```
Posisi       : Kanan atas header, sebelum avatar user
Icon Size    : 20px
Color        : #6b7280 (idle), #1e3a5f (hover)

Badge Notif:
  Background : #dc2626
  Color      : #ffffff
  Size       : 16px x 16px
  Font-size  : 10px
  Posisi     : top-right dari icon (absolute)
  Border-radius : 9999px
```

**Dropdown Notifikasi**
```
Width        : 320px
Max-height   : 400px
Overflow-y   : auto
Background   : #ffffff
Border       : 1px solid #e5e7eb
Border-radius : 8px
Box-shadow   : 0 4px 12px rgba(0,0,0,0.15)
```

---

### 4.7 Early Warning Bar (Pagu Anggaran)

Komponen khusus di Dashboard Kaprodi dan WD II untuk monitoring pagu anggaran.

```
Container:
  Background   : #ffffff
  Border       : 1px solid #e5e7eb
  Border-radius : 8px
  Padding      : 16px 20px

Label:
  Font-size    : 14px
  Font-weight  : 600
  Color        : #374151

Nilai Saat Ini / Pagu:
  Font-size    : 12px
  Color        : #6b7280

Progress Bar Track:
  Height       : 10px
  Background   : #e5e7eb
  Border-radius : 9999px

Progress Bar Fill:
  Border-radius : 9999px
  Transition    : width 0.3s ease
```

**Warna Fill Progress Berdasarkan Persentase:**

| Persentase | Warna Fill | Status         |
|------------|------------|----------------|
| < 60%      | `#16a34a`  | Aman           |
| 60% - 79%  | `#d97706`  | Perhatian      |
| >= 80%     | `#dc2626`  | Bahaya / Kritis |

**Teks Peringatan:**
```
Font-size    : 12px
Font-weight  : 500
Margin-top   : 6px

< 60%    : Warna #16a34a — "Anggaran dalam batas aman"
60-79%   : Warna #d97706 — "Anggaran mendekati batas pagu"
>= 80%   : Warna #dc2626 — "PERINGATAN: Anggaran hampir habis!"
```

---

### 4.8 Tabel

**Struktur Dasar**
```
Width        : 100%
Border-collapse : collapse
Font-size    : 14px
```

**Header Tabel (thead)**
```
Background   : #1f2937 (gray-800)
Color        : #f9fafb (gray-50)
Font-size    : 12px
Font-weight  : 600
Text-transform : uppercase
Letter-spacing : 0.05em
Padding cell : 12px 16px (py-3 px-4)
```

**Baris Tabel (tbody tr)**
```
Baris Ganjil : Background #ffffff
Baris Genap  : Background #f9fafb (striped)
Hover        : Background #eff6ff

Border-B per baris : 1px solid #e5e7eb
Padding cell : 14px 16px (py-3.5 px-4)
```

**Kolom Aksi**
```
Text-align   : center
White-space  : nowrap
Min-width    : 120px
```

**Responsive Tabel**
```
Wrapper      : overflow-x: auto
Min-width tabel : 600px
```

---

## 5. Layout Halaman

### 5.1 Struktur Umum

```
┌─────────────────────────────────────────────┐
│  SIDEBAR (256px, fixed)  │   MAIN CONTENT   │
│                          │   (flex-1)        │
│  Logo                    │  ┌─────────────┐ │
│  Nav Items               │  │   HEADER    │ │
│                          │  └─────────────┘ │
│                          │  ┌─────────────┐ │
│                          │  │   CONTENT   │ │
│                          │  └─────────────┘ │
└─────────────────────────────────────────────┘
```

**Main Content Area**
```
Margin-left  : 256px (ml-64, untuk kompensasi sidebar fixed)
Min-height   : 100vh
Background   : #f9fafb
```

**Header Halaman**
```
Background   : #ffffff
Border-B     : 1px solid #e5e7eb
Padding      : 16px 32px (py-4 px-8)
Display      : flex, justify-between, items-center
Position     : sticky, top-0
Z-index      : 10
```

**Content Area**
```
Padding      : 32px (p-8)
Max-width    : 1280px (opsional)
```

---

## 6. Ikon

Gunakan SVG inline atau Heroicons. Ukuran standar:

| Konteks         | Ukuran |
|-----------------|--------|
| Sidebar nav     | 18px   |
| Tombol          | 16px   |
| Header          | 20px   |
| Badge/label     | 12px   |

---

## 7. Animasi & Transisi

```
Transisi Default : 150ms ease-in-out
Hover tombol     : background-color, color
Modal masuk      : fade-in 200ms + scale(0.95 → 1.0)
Dropdown         : fade-in + slide-down 150ms
Toast            : slide-in dari kanan, 300ms
```

---

## 8. Breakpoint Responsif

| Token | Nilai  | Keterangan                              |
|-------|--------|-----------------------------------------|
| sm    | 640px  | Mobile landscape                        |
| md    | 768px  | Tablet — sidebar menjadi collapsible    |
| lg    | 1024px | Desktop kecil                           |
| xl    | 1280px | Desktop standar (layout utama)          |
| 2xl   | 1536px | Desktop besar                           |

Di bawah `md (768px)`: sidebar disembunyikan, gunakan hamburger menu.

---

## 9. Aksesibilitas

- Semua tombol harus punya `aria-label` yang deskriptif
- Form input wajib ada `<label>` terhubung via `for` / `htmlFor`
- Contrast ratio teks minimal 4.5:1 (WCAG AA)
- Focus visible: ring biru 3px saat navigasi keyboard
- Badge status harus punya teks, jangan hanya warna
- Tabel wajib ada `<caption>` atau `aria-label`
