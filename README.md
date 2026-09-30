# Website K'mplang Salatiga (PHP OOP, MVC & MySQL)

Sistem Informasi Resmi **Keluarga Mahasiswa Perantauan Lampung (K'mplang) Salatiga**. Aplikasi ini telah dirombak total dari website *client-side* statis menjadi sistem web dinamis berbasis **PHP 8.2 (OOP & MVC)**, basis data **MySQL / MariaDB**, serta sistem keamanan tingkat lanjut (*defense-in-depth*).

---

## 1. Arsitektur Proyek (MVC Murni)

```
Website Kmplang/
├── app/
│   ├── Config/          # Database PDO, Security (CSRF, Session, Headers), App Env
│   ├── Core/            # Router, Controller, Model, Middleware, View
│   ├── Controllers/     # Home, Auth, Program, Activity, Article, Comment, Member, Download
│   │   └── Admin/       # Dashboard, Setting (CMS), Proker, ActivityAdmin, ArticleAdmin, MemberAdmin
│   ├── Models/          # User, SiteSetting, WorkProgram, Activity, ActivityMedia, Article, Comment, AuditLog
│   ├── Helpers/         # AES-256 Encryption, FileUpload (50MB), Formatter, Sanitizer
│   └── Views/           # Layouts, Partials, Home, Auth, Programs, Activities, Articles, Members, Admin
├── database/
│   ├── schema.sql       # Struktur tabel DDL MySQL
│   └── seed.sql         # Data awal default (admin, proker 2025/2026, settings)
├── public/              # Web Root (Front Controller index.php & static assets)
│   ├── index.php
│   ├── .htaccess
│   └── assets/          # CSS, JS, Gambar, Musik Lampung
├── storage/             # Direktori berkas unggahan terlindungi (anti eksekusi PHP)
│   ├── uploads/
│   └── .htaccess
├── .env.example
├── .env
├── .htaccess            # Apache rewrite rules (assets & front controller)
├── index.php            # Root Entry Point
└── README.md
```

---

## 2. Fitur Utama

### A. Role & Otorisasi
- **Role Admin / Pengurus**:
  - **CMS Konten Website**: Mengubah judul, narasi sambutan, latar belakang, filosofi, dan tautan sosial media secara dinamis tanpa mengubah baris kode.
  - **Manajemen Program Kerja**: Menambah, mengubah, memantau progres, dan transparansi anggaran dari seluruh divisi (BPH, Humas, Wirausaha, Olahraga, Tari, Perkap, Musik, Kerohanian).
  - **Manajemen Kegiatan & Album (Maks 50 MB)**: Mengunggah dokumentasi kegiatan dengan banyak berkas foto & video sekaligus.
  - **Manajemen & Verifikasi Anggota**: Melihat pendaftar baru, melihat nomor WhatsApp terenkripsi yang didekripsi secara aman, tombol kirim pesan langsung, persetujuan (*approve*/*reject*), serta ekspor data ke CSV.
  - **Manajemen Artikel & Budaya**: Mempublikasikan wawasan budaya Lampung dan berita organisasi.
  - **Audit Trail & Keamanan**: Mencatat setiap aktivitas krusial pengguna bersama IP Address dan User Agent.

- **Role User (Anggota K'mplang)**:
  - **Registrasi Akun Resmi**: Mendaftar dengan data lengkap (Nama, NIM, Fakultas, Asal Daerah, WhatsApp terenkripsi, Tanggal Lahir, Motivasi) langsung tersimpan ke basis data tanpa dialihkan ke WhatsApp.
  - **Transparansi Program Kerja**: Menelusuri seluruh program kerja dan capaian kepengurusan.
  - **Album Dokumentasi ala Google Drive**:
    - Tombol *Grid View* dan *List View*.
    - Informasi nama berkas asli, tipe berkas, ukuran (KB/MB), dan tanggal unggah.
    - Pratinjau resolusi penuh (*lightbox modal*).
    - Unduh berkas satuan atau **Unduh Seluruh Album dalam format ZIP**.
  - **Interaksi Komunitas**: Memberikan komentar dan tanggapan berfaedah pada dokumentasi kegiatan dan artikel.

- **Publik / Tamu**:
  - Landing page informatif dan interaktif.
  - Pemutar musik daerah Lampung (*Tanoh Lado*, *Lampung Sai*, *Remix Lamunan*).
  - Akses formulir pendaftaran anggota dan direktori anggota resmi.

---

## 3. Aspek Keamanan Sistem

| Fitur Keamanan | Implementasi Teknis |
| :--- | :--- |
| **Enkripsi Data Sensitif** | Nomor WhatsApp dan data kontak pribadi dienkripsi menggunakan **AES-256-CBC** dengan *random IV* dan verifikasi integritas **HMAC-SHA256**. |
| **SQL Injection Prevention** | 100% operasi query menggunakan **PDO Prepared Statements** dengan *parameter binding*. |
| **Cross-Site Request Forgery (CSRF)** | Token kriptografis acak `bin2hex(random_bytes(32))` divalidasi pada setiap formulir `POST`. |
| **Cross-Site Scripting (XSS)** | Pembersihan output dengan helper global `e()` menggunakan `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`. |
| **Role-Based Access Control (RBAC)** | Middleware mencegat upaya akses ilegal ke area admin dengan pengalihan / HTTP 403 Forbidden. |
| **Validasi Berkas Unggahan** | Batas maksimal berkas **50 MB**, verifikasi *MIME type* asli via PHP `finfo` (JPG, PNG, WEBP, MP4, WEBM), serta nama berkas acak kriptografis (anti *directory traversal*). |
| **Anti Execution Protection** | Direktori `storage/` dilindungi berkas `.htaccess` yang mematikan eksekusi mesin skrip PHP (`php_flag engine off`). |
| **Session Hardening** | `session_regenerate_id(true)` saat login, flag cookie `HttpOnly`, `SameSite=Lax`, dan pembatasan masa aktif sesi. |

---

## 4. Standar UI/UX & Ikon

- **Tanpa Emoji**: Seluruh elemen visual, navigasi, dan tombol menggunakan **Google Material Symbols (Google Icons)**.
- **AOS Terbatas**: Animasi **AOS hanya aktif pada Halaman Utama (Landing Page)**. Pada sub-halaman (login, register, proker, album, direktori, admin), AOS dinonaktifkan demi performa dan responsivitas.
- **Identitas Visual**: Palet emas Lampung (`#FFBF00`, `#DAA520`) berpadu dengan tema modern slate/dark.

---

## 5. Cara Menjalankan Aplikasi

### Kebutuhan Sistem
- PHP 8.2 atau lebih baru (dengan ekstensi `pdo_mysql`, `openssl`, `fileinfo`, `zip` aktif).
- MySQL / MariaDB.

### Langkah Instalasi
1. **Konfigurasi Lingkungan (`.env`)**:
   Salin `.env.example` menjadi `.env` lalu sesuaikan konfigurasi basis data Anda:
   ```ini
   APP_NAME="K'mplang Salatiga"
   APP_URL=http://localhost:8000
   APP_KEY=base64:7f9b8c2d3e4a5f60718293a4b5c6d7e8f90123456789abcdef0123456789abcd

   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_NAME=db_kmplang
   DB_USER=root
   DB_PASS=
   ```

2. **Migrasi Basis Data**:
   Jalankan skrip DDL dan seeder ke MySQL:
   ```powershell
   Get-Content database\schema.sql | & "D:\xampp\mysql\bin\mysql.exe" -u root
   Get-Content database\seed.sql | & "D:\xampp\mysql\bin\mysql.exe" -u root
   ```

3. **Menjalankan Aplikasi**:
   - **Opsi A (Via Apache XAMPP)**:
     Cukup aktifkan Apache & MySQL di XAMPP Control Panel, lalu buka peramban:
     `http://localhost/Kmplang/`
   
   - **Opsi B (Via PHP Built-in Server)**:
     ```powershell
     & "D:\xampp\php\php.exe" -S localhost:8000
     ```
     Lalu buka peramban di tautan: `http://localhost:8000`

---

## 6. Akun Default Pengurus

- **Email**: `admin@kmplang.org`
- **Kata Sandi**: `AdminKmplang2026`
- **Hak Akses**: Super Admin
