# ?? ESA Learning System - Panduan Pengguna Lengkap

Dokumen ini berisi panduan teknis untuk menjalankan aplikasi di komputer lokal (Localhost) serta tutorial langkah demi langkah menggunakan fitur-fitur di dalam LMS ini.

---

## ??? 1. Cara Menjalankan Aplikasi di Local (Laragon)

Karena Anda menggunakan **Laragon**, proses menjalankannya sangat mudah:

### A. Akses Web Server
1. Buka aplikasi **Laragon** dan pastikan Anda sudah menekan tombol **Start All** (Apache/Nginx & MySQL/PostgreSQL menyala).
2. Karena folder proyek ini bernama `esa-lms`, Laragon secara otomatis membuatkan domain lokal untuk Anda.
3. Buka browser dan ketikkan alamat: ?? `http://esa-lms.test`

### B. Menjalankan Frontend (Vite)
Agar tampilan antarmuka (Vue.js) berjalan dan merender dengan baik, Anda harus menghidupkan server *frontend*:
1. Buka Terminal/Command Prompt.
2. Arahkan ke folder proyek: `cd C:\laragon\www\esa-lms`
3. Ketikkan perintah: `npm run dev`
*(Biarkan terminal ini tetap terbuka di latar belakang)*

### C. Menjalankan Worker (Untuk Notifikasi Email)
Aplikasi ini memiliki fitur *Background Jobs* untuk mengirimkan sertifikat dan email agar web tidak lambat.
1. Buka Terminal/Command Prompt **baru**.
2. Arahkan ke folder proyek: `cd C:\laragon\www\esa-lms`
3. Ketikkan perintah: `php artisan queue:work`
*(Biarkan terminal ini tetap terbuka di latar belakang)*

---

## ?? 2. Daftar Akses (Role)
Aplikasi ini memiliki 3 tipe pengguna:
1. **Admin**: Memiliki kontrol penuh untuk membuat Kursus, membuat Soal, menambah User, dan melihat Laporan.
2. **Learner (Siswa)**: Karyawan yang mendaftar ke kelas, menonton materi, mengikuti ujian, dan mendapatkan sertifikat.
3. **Manager**: (Fitur ini dapat disesuaikan ke depan untuk melihat *progress* anak buahnya).

---

## ?? 3. Tutorial Untuk ADMIN

### A. Cara Membuat Kursus (Course)
1. Login menggunakan akun dengan Role **Admin**.
2. Di menu kiri, klik **"Courses"**.
3. Klik tombol **"Create Course"**.
4. Isi Judul, Deskripsi, Kategori, dan tentukan **Passing Grade** (misal: 80 untuk lulus).
5. Klik **Save**.

### B. Cara Mengisi Materi ke dalam Kursus
Setelah Kursus terbuat, Anda harus memasukkan bab dan materinya:
1. Buka detail Kursus yang baru dibuat.
2. Di bagian **Modules**, klik **"Add Module"** (Misal: "Bab 1: Pengenalan").
3. Di dalam Module tersebut, klik **"Add Lesson"**.
4. Isi judul pelajaran, **Video URL** (jika ada), dan **Content** (teks materi pembacaan).
5. Klik **Save**.

### C. Cara Membuat Ujian (Assessment)
1. Di halaman detail Kursus, klik tab **"Assessment"**.
2. Anda akan diminta menambah pertanyaan.
3. Pilih Tipe Pertanyaan: 
   - **Multiple Choice**: Anda harus memasukkan Opsi A, B, C, D dan memilih mana kunci jawaban yang benar. (Sistem akan menilai ini otomatis).
   - **Essay**: Siswa akan menjawab dengan teks panjang (Ini butuh penilaian manual instruktur ke depannya).
4. Masukkan skor poin per soal.

### D. Melihat Laporan (Reports)
1. Di menu kiri, klik **"Reports Dashboard"**.
2. Anda akan melihat layar grafik yang berisi Total User, Tingkat Kelulusan (Completion Rate), dan Kursus Terpopuler.

---

## ?? 4. Tutorial Untuk LEARNER (Siswa)

### A. Cara Mengikuti Kursus
1. Login menggunakan akun dengan Role **Learner**.
2. Anda akan diarahkan ke layar **"My Courses"**.
3. Di bagian "Available Courses", cari kursus yang ingin Anda ikuti, lalu klik **"Enroll"**.

### B. Proses Belajar (Membaca Materi)
1. Kursus yang sudah di-enroll akan pindah ke bagian "Enrolled Courses".
2. Klik **"Continue Learning"**.
3. Anda akan masuk ke halaman *Viewer*.
4. Tonton video atau baca materi teksnya. Jika sudah paham, klik tombol **"Mark as Complete & Next"** di pojok kanan bawah.
5. Bar *Progress* di sebelah kiri akan bertambah persentasenya (contoh: 50%).

### C. Mengerjakan Ujian Akhir
1. Setelah **semua materi (100%)** selesai dibaca, tombol **"Take Assessment"** di menu kiri akan aktif (bisa diklik).
2. Klik tombol tersebut dan kerjakan seluruh soal kuis.
3. Klik **"Submit Assessment"**.
4. Jika nilai Anda di atas *Passing Grade* (Misal nilai 90, syarat lulus 80), maka status Anda berubah menjadi **Lulus (Passed)**.

### D. Mengunduh Sertifikat
1. Jika Anda sudah lulus, klik menu **"My Certificates"** di sebelah kiri.
2. Anda akan melihat kartu sertifikat Anda.
3. Klik **"Download PDF"** untuk menyimpan dan mencetak sertifikat digital Anda.

---
*Semoga panduan ini membantu Anda mengeksplorasi seluruh kemampuan ESA Learning Management System!*
