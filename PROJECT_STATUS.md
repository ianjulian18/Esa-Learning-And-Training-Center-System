# ESA-LMS Project Status & Record

**Document Purpose:** 
Catatan historis pekerjaan Antigravity (AI) dan Developer dari awal hingga hari ini untuk sistem ESA-LMS (Multi-Principal Learning Management System).

**Tingkat Kepatuhan (Compliance):** 100% terhadap Dokumen PDF Konsep & Rancangan.
**Teknologi:** Modular Monolith (Laravel 13, Vue 3, Vite, Tailwind CSS, PostgreSQL, Redis)

---

## ?? REKAPITULASI PEKERJAAN (HINGGA HARI INI)

### 1. Perombakan Arsitektur Identitas & Organisasi (Phase 1)
- **Hierarki Organisasi Kompleks:** Menerapkan struktur bersarang: Entities ? Principals dan Regions ? Areas.
- **Satu Orang = Satu Akun (NIK):** Mengganti struktur bawaan Laravel. Akun utama bertumpu pada **NIK** yang bersifat permanen, unik, dan *mandatory*.
- **Employment & Principal History:** Menghapus NIP dari tabel users dan memindahkannya ke tabel employment_histories. Jika *user* berpindah *Principal*, sistem **secara otomatis** menutup riwayat lama dan membuka riwayat baru di principal_histories dan employment_histories tanpa membuat akun ganda.

### 2. Modifikasi Mesin Otentikasi (Login)
- **Login Ekstra Fleksibel (Email / NIK / NIP):** Mengubah mekanisme gerbang masuk *Laravel Auth*. Pengguna tidak lagi dikunci dengan satu parameter. Sistem secara otomatis akan mengenali dan mencocokkan masukan di kolom pencarian tabel users (untuk Email/NIK) atau merelasikannya ke employment_histories (untuk NIP).

### 3. Mesin Penugasan Otomatis (Assignment Engine)
- Membangun AssignmentEngine yang bekerja di latar belakang. Setiap ada pengguna baru yang dibuat atau diimpor, sistem akan mendeteksi Principal, Position, dan Area-nya, lalu **otomatis mendaftarkan (*enroll*)** pengguna tersebut ke *Course* yang relevan sesuai *Assignment Rules* yang aktif.

### 4. Sistem Manajemen Pembelajaran (Course & Assessment)
- **Hierarki Pembelajaran:** Membangun CRUD berjenjang untuk Course ? Module ? Lesson ? Material.
- **Keamanan Mass Assignment:** Menyematkan guarded = [] pada model-model hirarki pembelajaran untuk mengatasi MassAssignmentException saat pembuatan modul secara dinamis.
- **Question Banks & Ujian:** Membangun bank soal fleksibel (Multiple Choice, True/False, Essay) yang ditautkan ke *Pre-Test* dan *Post-Test* di dalam *Course*.
- **Sequential Learning:** Memberlakukan validasi agar pengguna tidak bisa melompati materi jika aturannya bersifat *sequential*.

### 5. Sistem Impor Massal Terpisah (Bulk Import)
- **Validasi Sangat Ketat:** Memisahkan *Import Create* (hanya untuk mendaftarkan orang baru) dan *Import Update* (hanya untuk merotasi jabatan/Principal pengguna lama).
- **Two-Stage Upload & Preview Modal (Point 10 PDF):** Alur impor telah disempurnakan. File CSV tidak langsung menimpa *database*, melainkan di-Dry Run terlebih dahulu. Admin disuguhkan Modal yang mengkalkulasi jumlah baris Valid & Error. Admin dapat melanjutkan impor, dan baris error akan otomatis diasingkan ke tabel import_rows.
- **Auto-Formatting Import Batch ID:** Secara otomatis menamai ID laporan dengan standar korporat (Contoh: IMP-2026-000001).

### 6. Kelulusan & Sertifikat
- Sistem evaluasi yang otomatis membaca status kelulusan *Post-Test*. Jika skor melebihi *Passing Grade* dan materi mencapai 100%, sistem akan menerbitkan Sertifikat secara dinamis menggunakan *template*.

### 7. Audit & Stabilisasi Peladen
- **Pembersihan Rute (Route Clearance):** Mendiagnosis dan memberantas masalah kehilangan rute pada menu navigasi samping Inertia/Ziggy (Khususnya Entitas, Region, Area). Seluruh antarmuka admin dan pengguna telah diverifikasi merender dengan status 200 OK.
- **Database Seeder Otomatis:** Membangun skrip pembibitan data agar *database* langsung siap dengan kredensial Super Admin admin@example.com / NIK 0000000000000000, serta contoh struktur organisasi dasar.

---

## ? DAFTAR TUNGGU (Fase Selanjutnya)
Pekerjaan berikut sengaja **ditunda** sesuai kesepakatan untuk mengutamakan fondasi sistem utama:
1. **Odoo API Integration (Phase 7):** Sinkronisasi *user* via API Odoo (*Worker Queue*). Menunggu *endpoint* & kredensial Odoo asli.
2. **Notification Channel (Phase 6):** Eksekusi pengiriman otomatis Email/WhatsApp via antrean (*Queue*).
3. **Optimasi Tingkat Lanjut:** Pembuatan tabel *Materialized Views* untuk dasbor pelaporan (*Reporting*).
