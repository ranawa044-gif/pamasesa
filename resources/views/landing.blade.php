<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PAMASESA — Portal Manajemen Seminar & Sidang Akhir D3 Sistem Informasi</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --app-primary: #4f46e5;
            --app-primary-dark: #4338ca;
            --app-teal: #0d9488;
            --app-text: #0f172a;
            --app-muted: #64748b;
            --app-bg: #f8fafc;
            --font-heading: 'Plus Jakarta Sans', system-ui, sans-serif;
            --font-body: 'Inter', system-ui, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        body {
            font-family: var(--font-body);
            color: var(--app-text);
            background-color: var(--app-bg);
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-heading);
            font-weight: 800;
            letter-spacing: -0.025em;
        }

        /* Header Navigation */
        .landing-navbar {
            background: rgba(15, 23, 42, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .brand-icon-nav {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.75rem;
            background: linear-gradient(135deg, #4f46e5 0%, #0d9488 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.4);
        }

        .nav-link-custom {
            color: #94a3b8;
            font-weight: 600;
            font-size: 0.9rem;
            transition: color 0.2s ease;
            text-decoration: none;
            padding: 0.5rem 0.85rem;
        }

        .nav-link-custom:hover {
            color: #ffffff;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            padding: 5rem 0 6rem;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -20%;
            left: 50%;
            transform: translateX(-50%);
            width: 800px;
            height: 400px;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.25) 0%, rgba(13, 148, 136, 0.1) 50%, transparent 70%);
            pointer-events: none;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 0.4rem 1rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 700;
            color: #a5b4fc;
            letter-spacing: 0.05em;
            margin-bottom: 1.5rem;
        }

        .hero-title {
            font-size: clamp(2.25rem, 4.5vw, 3.75rem);
            line-height: 1.12;
            margin-bottom: 1.25rem;
            color: #ffffff;
        }

        .hero-subtitle {
            font-size: clamp(1rem, 1.8vw, 1.2rem);
            color: #94a3b8;
            max-width: 680px;
            margin: 0 auto 2.25rem;
            line-height: 1.6;
        }

        /* Stat Counter Cards */
        .stat-counter-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1.25rem;
            padding: 1.5rem;
            text-align: center;
            backdrop-filter: blur(8px);
        }

        .stat-counter-number {
            font-family: var(--font-mono);
            font-size: 2.25rem;
            font-weight: 700;
            color: #818cf8;
            line-height: 1;
            margin-bottom: 0.35rem;
        }

        .stat-counter-label {
            font-size: 0.85rem;
            color: #94a3b8;
            font-weight: 600;
        }

        /* Cards & Section Styling */
        .section-title {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .section-subtitle {
            color: var(--app-muted);
            font-size: 1rem;
            margin-bottom: 3rem;
        }

        .step-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            padding: 1.75rem;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04);
            transition: all 0.25s ease;
            height: 100%;
            position: relative;
        }

        .step-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px -4px rgba(15, 23, 42, 0.08);
            border-color: #c7d2fe;
        }

        .step-badge {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.75rem;
            background: #eeeffe;
            color: var(--app-primary);
            font-weight: 800;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
        }

        .feature-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            padding: 2rem;
            transition: all 0.25s ease;
        }

        .feature-card:hover {
            border-color: #99f6e4;
            box-shadow: 0 12px 30px -4px rgba(13, 148, 136, 0.1);
        }

        .feature-icon-box {
            width: 3rem;
            height: 3rem;
            border-radius: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.25rem;
        }

        /* Buttons */
        .btn-landing-primary {
            font-family: var(--font-heading);
            font-weight: 700;
            background: var(--app-primary);
            border-color: var(--app-primary);
            color: #ffffff;
            padding: 0.85rem 1.75rem;
            border-radius: 0.85rem;
            box-shadow: 0 8px 20px -4px rgba(79, 70, 229, 0.4);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-landing-primary:hover {
            background: var(--app-primary-dark);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -4px rgba(79, 70, 229, 0.5);
        }

        .btn-landing-outline {
            font-family: var(--font-heading);
            font-weight: 600;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            padding: 0.85rem 1.75rem;
            border-radius: 0.85rem;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-landing-outline:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }

        /* Footer High Contrast */
        .landing-footer {
            background: #090d16;
            color: #cbd5e1;
            padding: 4rem 0 2.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
        }

        .footer-desc {
            color: #cbd5e1;
            font-size: 0.925rem;
            line-height: 1.6;
        }

        .footer-heading {
            font-family: var(--font-heading);
            font-weight: 700;
            font-size: 1rem;
            color: #ffffff;
            letter-spacing: 0.01em;
        }

        .footer-link {
            color: #cbd5e1;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            display: inline-block;
        }

        .footer-link:hover {
            color: #ffffff;
            transform: translateX(2px);
        }

        .footer-info-text {
            color: #cbd5e1;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .footer-bottom-text {
            color: #94a3b8;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

<!-- Header Navigation -->
<nav class="landing-navbar">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="{{ route('landing') }}" class="d-flex align-items-center gap-2 text-decoration-none">
            <div class="brand-icon-nav">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <div>
                <span class="fw-bold text-white fs-5" style="letter-spacing: -0.01em;">PAMASESA</span>
                <span class="badge bg-indigo-subtle text-primary border ms-2" style="font-size: 0.7rem;">D3 SI</span>
            </div>
        </a>

        <div class="d-none d-md-flex align-items-center gap-3">
            <a href="#alur" class="nav-link-custom">Alur SOP</a>
            <a href="#dokumen" class="nav-link-custom">Dokumen & Pengumuman</a>
            <a href="#bidang-fokus" class="nav-link-custom">Bidang Fokus</a>
            <a href="#faq" class="nav-link-custom">FAQ</a>
        </div>

        <div>
            <a href="{{ route('login') }}" class="btn btn-sm btn-landing-primary py-2 px-3 fs-6">
                <i class="bi bi-box-arrow-in-right"></i> Masuk Portal Login
            </a>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section text-center">
    <div class="container position-relative">
        <div class="hero-badge">
            <i class="bi bi-shield-check"></i> PORTAL RESMI PROYEK AKHIR D3 SISTEM INFORMASI
        </div>
        <h1 class="hero-title fw-extrabold">
            Manajemen Seminar & Sidang Akhir<br>
            <span style="background: linear-gradient(135deg, #818cf8 0%, #2dd4bf 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Terpadu, Transparan, & Terstruktur</span>
        </h1>
        <p class="hero-subtitle">
            Kelola seluruh tahapan Proyek Akhir mahasiswa mulai dari seleksi judul, bimbingan berkala, seminar proposal, hingga pelaksanaan sidang akhir dalam satu platform digital.
        </p>

        <div class="d-flex align-items-center justify-content-center gap-3 mb-5 flex-wrap">
            <a href="{{ route('login') }}" class="btn-landing-primary">
                <i class="bi bi-box-arrow-in-right fs-5"></i> Masuk ke Portal PAMASESA
            </a>
            <a href="#alur" class="btn-landing-outline">
                <i class="bi bi-diagram-3 fs-5"></i> Lihat Alur & Tahapan SOP
            </a>
        </div>

        <!-- Real-time Public Stats Counter -->
        <div class="row g-3 max-width-lg mx-auto mt-4" style="max-width: 900px;">
            <div class="col-md-4">
                <div class="stat-counter-card">
                    <div class="stat-counter-number">{{ $studentCount }}</div>
                    <div class="stat-counter-label">Mahasiswa Aktif PA</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-counter-card">
                    <div class="stat-counter-number">{{ $approvedProjectCount }}</div>
                    <div class="stat-counter-label">Judul Ter-ACC & Lulus</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-counter-card">
                    <div class="stat-counter-number">{{ $lecturerCount }}</div>
                    <div class="stat-counter-label">Dosen Pembimbing & Penguji</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Alur & Tahapan SOP PA -->
<section id="alur" class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center max-width-md mx-auto mb-5">
            <span class="badge bg-indigo-subtle text-primary border px-3 py-2 fw-bold mb-2">STANDARD OPERATING PROCEDURE</span>
            <h2 class="section-title">8 Tahap Utama Pengerjaan Proyek Akhir</h2>
            <p class="section-subtitle">Alur sistematis dari awal pengajuan judul hingga pelaksanaan Sidang Akhir di D3 Sistem Informasi.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-badge">1</div>
                    <h5 class="fw-bold fs-6 mb-2">Pengajuan Judul</h5>
                    <p class="text-muted small mb-0">Mahasiswa memilih skema Proyek Akhir (Perancangan, Implementasi, atau Prestasi) dan mengisi form usulan beserta dokumen pendukung sesuai persyaratan masing-masing skema.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-badge">2</div>
                    <h5 class="fw-bold fs-6 mb-2">Validasi & Pembimbing</h5>
                    <p class="text-muted small mb-0">Admin mereview skor validasi judul (ACC / Revisi / Tolak) dan melakukan plotting Pembimbing 1 & Pembimbing 2.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-badge">3</div>
                    <h5 class="fw-bold fs-6 mb-2">Logging Bimbingan</h5>
                    <p class="text-muted small mb-0">Mahasiswa mencatat progress bimbingan secara berkala di portal. Dosen pembimbing menelaah & memberikan umpan balik.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-badge">4</div>
                    <h5 class="fw-bold fs-6 mb-2">Pendaftaran Sempro</h5>
                    <p class="text-muted small mb-0">Setelah mendapat persetujuan pembimbing (status Siap Seminar), mahasiswa mendaftar Ujian Seminar Proposal.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-badge">5</div>
                    <h5 class="fw-bold fs-6 mb-2">Penjadwalan Auto-Schedule</h5>
                    <p class="text-muted small mb-0">Admin mengatur jadwal seminar, ruangan, dan dosen penguji secara manual atau dengan sistem *Auto-Scheduling*.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-badge">6</div>
                    <h5 class="fw-bold fs-6 mb-2">Seminar & Revisi</h5>
                    <p class="text-muted small mb-0">Pelaksanaan ujian proposal, penilaian rubrik oleh dosen penguji, pencatatan revisi, hingga validasi perbaikan revisi.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-badge">7</div>
                    <h5 class="fw-bold fs-6 mb-2">Pendaftaran Sidang Akhir</h5>
                    <p class="text-muted small mb-0">Setelah aplikasi selesai diimplementasikan dan disetujui pembimbing, mahasiswa mengunggah laporan akhir & demo sistem.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-badge">8</div>
                    <h5 class="fw-bold fs-6 mb-2">Sidang Akhir & Kelulusan</h5>
                    <p class="text-muted small mb-0">Penetapan penguji sidang akhir oleh admin, pelaksanaan sidang final, dan pengesahan kelulusan Proyek Akhir.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Pengumuman & Dokumen Terbaru -->
<section id="dokumen" class="py-5" style="background-color: #f1f5f9;">
    <div class="container py-4">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <span class="badge bg-teal-subtle text-teal-emphasis border px-3 py-2 fw-bold mb-2">PENGUMUMAN & PANDUAN</span>
                <h2 class="section-title mb-0">Informasi & Dokumen Kelengkapan PA</h2>
            </div>
            <a href="{{ route('login') }}" class="btn btn-outline-primary"><i class="bi bi-lock-fill me-1"></i> Akses Portal Mahasiswa</a>
        </div>

        <div class="row g-4">
            @forelse($announcements as $info)
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm p-4 rounded-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                @if($info->is_pinned)
                                    <span class="badge bg-amber-subtle text-warning border border-amber-subtle"><i class="bi bi-pin-angle-fill me-1"></i>Pinned</span>
                                @endif
                                <h5 class="fw-bold mb-0 fs-6 text-slate-800">{{ $info->title }}</h5>
                            </div>
                            <span class="text-muted small"><i class="bi bi-clock me-1"></i>{{ $info->created_at->translatedFormat('d M Y') }}</span>
                        </div>
                        <p class="text-slate-600 small mb-4" style="white-space: pre-line;">{{ $info->content }}</p>
                        <div class="mt-auto d-flex align-items-center gap-2 flex-wrap">
                            @if($info->attachment_file)
                                <a href="{{ route('admin.announcements.download', $info) }}" class="btn btn-sm btn-primary shadow-sm" target="_blank">
                                    <i class="bi bi-download me-1"></i> Unduh File Lampiran
                                </a>
                            @endif
                            @if($info->attachment_url)
                                <a href="{{ $info->attachment_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Buka Tautan Link
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="p-5 text-center bg-white rounded-4 border">
                        <i class="bi bi-info-circle text-muted fs-1 mb-2 d-block"></i>
                        <h6 class="fw-bold">Belum Ada Pengumuman Publik</h6>
                        <p class="text-muted small mb-0">Informasi dan berkas panduan akan diperbarui secara berkala oleh Admin Prodi.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Section Bidang Fokus Penelitian SI -->
<section id="bidang-fokus" class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center max-width-md mx-auto mb-5">
            <span class="badge bg-indigo-subtle text-primary border px-3 py-2 fw-bold mb-2">RUMPUN KEPAKARAN</span>
            <h2 class="section-title">Bidang Fokus & Topik Penelitian PA</h2>
            <p class="section-subtitle">Pilihan ruang lingkup pengajuan judul proyek akhir untuk mahasiswa D3 Sistem Informasi.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon-box bg-indigo-subtle text-primary">
                        <i class="bi bi-calculator-fill"></i>
                    </div>
                    <h5 class="fw-bold fs-6 mb-2">Sistem Pendukung Keputusan (SPK)</h5>
                    <p class="text-muted small mb-0">Implementasi metode perangkingan dan komparasi seperti SAW, TOPSIS, SMART, AHP pada kasus nyata organisasi.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon-box bg-teal-subtle text-teal">
                        <i class="bi bi-laptop-fill"></i>
                    </div>
                    <h5 class="fw-bold fs-6 mb-2">Sistem Informasi Web & Mobile</h5>
                    <p class="text-muted small mb-0">Pengembangan aplikasi bisnis enterprise, e-commerce, inventarisasi, dan pelayanan publik menggunakan framework modern.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon-box bg-amber-subtle text-warning">
                        <i class="bi bi-phone-vibrate-fill"></i>
                    </div>
                    <h5 class="fw-bold fs-6 mb-2">Internet of Things (IoT)</h5>
                    <p class="text-muted small mb-0">Sistem monitoring dan otomatisasi perangkat keras mikrokontroler (ESP32/Arduino) yang terhubung ke dashboard web.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon-box bg-rose-subtle text-danger">
                        <i class="bi bi-pie-chart-fill"></i>
                    </div>
                    <h5 class="fw-bold fs-6 mb-2">Pengujian & Evaluasi Usability</h5>
                    <p class="text-muted small mb-0">Pengujian fungsionalitas sistem (*Blackbox Testing*) dan pengukuran tingkat kepuasan pengguna (*SUS & UAT*).</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section FAQ -->
<section id="faq" class="py-5" style="background-color: #f8fafc;">
    <div class="container py-4" style="max-width: 840px;">
        <div class="text-center mb-5">
            <span class="badge bg-light text-dark border px-3 py-2 fw-bold mb-2">PERTANYAAN UMUM</span>
            <h2 class="section-title">Pertanyaan Sering Diajukan (FAQ)</h2>
        </div>

        <div class="accordion accordion-flush rounded-4 shadow-sm overflow-hidden border" id="faqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button fw-bold text-slate-800" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Bagaimana alur pendaftaran pengajuan judul Proyek Akhir?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-muted small">
                        Mahasiswa dapat melakukan login ke portal PAMASESA menggunakan akun mahasiswa, kemudian memilih menu <strong>Pengajuan Judul</strong>. Mahasiswa wajib mengisikan latar belakang masalah nyata, lokasi penelitian, usulan pembimbing, metode, serta rencana pengujian.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed fw-bold text-slate-800" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Siapa yang menentukan penetapan Dosen Pembimbing?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-muted small">
                        Mahasiswa diperkenankan mengusulkan nama Dosen Pembimbing pada form pengajuan judul. Namun, keputusan final penetapan Pembimbing 1 dan Pembimbing 2 ditentukan oleh Admin / Koordinator Prodi berdasarkan kuota dan kesesuaian topik.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed fw-bold text-slate-800" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Apakah mahasiswa dapat memperbarui judul jika diminta revisi?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-muted small">
                        Ya, jika Admin memberikan status <strong>REVISION</strong> pada judul yang diajukan, form pengajuan judul mahasiswa akan terbuka kembali secara otomatis agar mahasiswa dapat memperbaiki data sesuai catatan reviewer.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="landing-footer">
    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="brand-icon-nav">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <span class="fw-bold text-white fs-4">PAMASESA</span>
                </div>
                <p class="footer-desc" style="max-width: 440px;">
                    Sistem Informasi Manajemen Seminar dan Sidang Akhir Proyek Akhir untuk Program Studi D3 Sistem Informasi.
                </p>
            </div>
            <div class="col-lg-3 col-6">
                <h6 class="footer-heading mb-3">Tautan Pintas</h6>
                <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
                    <li><a href="#alur" class="footer-link"><i class="bi bi-chevron-right me-1 text-primary small"></i> Alur SOP</a></li>
                    <li><a href="#dokumen" class="footer-link"><i class="bi bi-chevron-right me-1 text-primary small"></i> Dokumen PA</a></li>
                    <li><a href="#bidang-fokus" class="footer-link"><i class="bi bi-chevron-right me-1 text-primary small"></i> Rumpun Penelitian</a></li>
                    <li><a href="{{ route('login') }}" class="footer-link"><i class="bi bi-chevron-right me-1 text-primary small"></i> Portal Login</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-6">
                <h6 class="footer-heading mb-3">Program Studi</h6>
                <div class="footer-info-text">
                    <div class="fw-bold text-white">D3 Sistem Informasi</div>
                    <div class="text-slate-300">Jurusan Teknologi Informasi dan Komputer</div>
                    <div class="text-slate-300">Politeknik Negeri Subang</div>
                </div>
            </div>
        </div>
        <div class="pt-4 border-top border-secondary border-opacity-25 text-center footer-bottom-text">
            PAMASESA System &bull; Built with Laravel 12 & Hallmark Design System.
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
