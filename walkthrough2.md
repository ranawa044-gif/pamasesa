# Walkthrough — Redesign Tampilan PAMASESA (Hallmark Design System)

Sistem antarmuka (UI) **PAMASESA** telah diperbarui secara menyeluruh menggunakan prinsip *anti-AI-slop design* dari **Hallmark**. Tampilan baru ini menyajikan desain yang modern, responsif, berestetika tinggi, dan nyaman digunakan (*user-friendly*).

---

## 🎨 Perubahan & Pembaruan Utama

### 1. **Layout & System Design Tokens ([`app.blade.php`](file:///c:/laragon/www/pamasesa/resources/views/layouts/app.blade.php))**
- **Tipografi Modern**: Mengintegrasikan `Plus Jakarta Sans` (Judul & Navigasi), `Inter` (Teks Isi/Body), dan `JetBrains Mono` (Monospace untuk NIM/NIDN/Waktu/Kode).
- **Sidebar Slate Elegus**: 
  - Logo brandmark baru dengan gradasi warna Sapphire-Teal (`#4f46e5` $\rightarrow$ `#0d9488`).
  - Navigasi dengan indikator aktif bergaris aksen *glow* (`::before` pill bar) dan latar transparan.
  - Widget profil pengguna dengan badge *role* berwarna pastel (*Admin*, *Dosen*, *Mahasiswa*).
- **Topbar Floating Glassmorphism**:
  - Tanggal sistem real-time dengan ikon kalender.
  - Sub-kicker halaman yang informatif.
- **Sistem Komponen Hallmark**:
  - Kartu (*Cards*) dengan sudut membulat 16px (`--app-radius`), bayangan lembut (*subtle elevation shadow*), dan efek *micro-interaction hover*.
  - Tabel dengan header kelabu terang bertingkat, kontras tinggi, dan *hover row highlight*.
  - Form & Input dengan *focus ring ring* indigo.

### 2. **Komponen Badge Status ([`status-badge.blade.php`](file:///c:/laragon/www/pamasesa/resources/views/components/status-badge.blade.php))**
- Diubah dari badge warna pekat (*solid colors*) menjadi *soft-colored pastel pills* dengan border halus dan ikon *filled* yang relevan (seperti `bi-check-circle-fill`, `bi-hourglass-split`, `bi-award-fill`).

### 3. **Dashboard Admin ([`admin.blade.php`](file:///c:/laragon/www/pamasesa/resources/views/dashboard/admin.blade.php))**
- Kartu statistik dilengkapi dengan wadah ikon micro (*stat icons*) berwarna (Indigo, Teal, Cyan, Amber, Rose).
- Pengelompokan statistik menjadi 4 bagian berstruktur: *Ringkasan Umum & Progress*, *Status Seminar Proposal*, *Status Sidang Akhir*, dan *Agenda Seminar*.

### 4. **Dashboard Mahasiswa ([`student.blade.php`](file:///c:/laragon/www/pamasesa/resources/views/dashboard/student.blade.php))**
- Kartu ringkasan status judul dan pembimbing dengan ikon stat.
- Visualisasi *progress bar* pengerjaan PA dengan indikator persentase.
- Panel *Status Jalur Kerja PA* yang interaktif.

### 5. **Dashboard Dosen ([`lecturer.blade.php`](file:///c:/laragon/www/pamasesa/resources/views/dashboard/lecturer.blade.php))**
- Statistik jumlah mahasiswa bimbingan, kelayakan sidang akhir, dan tugas penguji.
- Tabel agenda seminar proposal dosen dilengkapi tombol *Mulai Ujian* dan daftar progress log mahasiswa.

### 6. **Alur Sistem / SOP ([`workflow.blade.php`](file:///c:/laragon/www/pamasesa/resources/views/admin/workflow.blade.php))**
- Timeline visual 8 langkah proses PA menggunakan komponen `.timeline-hallmark` dengan lingkaran nomor berurutan dan deskripsi tahapan yang rapi.

---

### 7. **Desain Ulang Halaman Login ([`login.blade.php`](file:///c:/laragon/www/pamasesa/resources/views/auth/login.blade.php))**
- Diubah total dari tampilan bawaan menjadi antarmuka *modern dark-ambient glassmorphism* dengan tipografi `Plus Jakarta Sans`.
- Dilengkapi dengan *icon box logo* gradasi Sapphire-Teal, input field dengan *icon prefix*, tombol login ber-efek *hover shadow*, dan tombol pintas (*quick seeder login*) untuk akun Admin, Mahasiswa, dan Dosen.

### 8. **Form Pengajuan Judul Mahasiswa ([`form.blade.php`](file:///c:/laragon/www/pamasesa/resources/views/student/final-projects/form.blade.php))**
- Mengubah elemen multi-select box (`<select multiple>`) yang kaku pada bagian **Metode / Pendekatan dalam Sistem** dan **Rencana Pengujian Sistem** menjadi komponen **Checkbox Card** yang bersih, rapi, dan mudah dicentang oleh mahasiswa.

### 9. **Fitur Informasi & Dokumen Kelengkapan PA (Pengumuman Admin untuk Mahasiswa)**
- **Database & Model**: Membuat tabel & model [`Announcement`](file:///c:/laragon/www/pamasesa/app/Models/Announcement.php) (`title`, `content`, `attachment_file`, `attachment_url`, `is_pinned`, `is_active`).
- **Modul Kelola Admin**: Controller [`AnnouncementController`](file:///c:/laragon/www/pamasesa/app/Http/Controllers/Admin/AnnouncementController.php) dan tampilan [`index.blade.php`](file:///c:/laragon/www/pamasesa/resources/views/admin/announcements/index.blade.php) & [`form.blade.php`](file:///c:/laragon/www/pamasesa/resources/views/admin/announcements/form.blade.php) untuk CRUD pengumuman, pengunggahan dokumen PDF/ZIP/DOCX, dan penyematan (*pinned*).
- **Tampilan Dashboard Mahasiswa**: Widget **"Informasi & Dokumen Kelengkapan PA"** yang menampilkan daftar pengumuman penting, catatan instruksi, serta tombol unduh file lampiran atau tautan eksternal.


