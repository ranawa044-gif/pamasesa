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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
            background: linear-gradient(135deg, #0b0f19 0%, #0f172a 45%, #1e1b4b 100%);
            color: #ffffff;
            padding: 5.5rem 0 4.5rem;
            position: relative;
            overflow: hidden;
        }

        .hero-bg-grid {
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
            background-size: 36px 36px;
            mask-image: radial-gradient(ellipse 70% 70% at 50% 50%, #000 60%, transparent 100%);
            -webkit-mask-image: radial-gradient(ellipse 70% 70% at 50% 50%, #000 60%, transparent 100%);
            pointer-events: none;
        }

        .hero-glow-1 {
            position: absolute;
            width: 550px;
            height: 550px;
            top: -120px;
            left: -120px;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.28) 0%, transparent 70%);
            filter: blur(50px);
            pointer-events: none;
        }

        .hero-glow-2 {
            position: absolute;
            width: 500px;
            height: 500px;
            bottom: -100px;
            right: -80px;
            background: radial-gradient(circle, rgba(13, 148, 136, 0.22) 0%, rgba(99, 102, 241, 0.15) 50%, transparent 70%);
            filter: blur(60px);
            pointer-events: none;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(99, 102, 241, 0.14);
            border: 1px solid rgba(165, 180, 252, 0.25);
            padding: 0.45rem 1.1rem;
            border-radius: 9999px;
            font-size: 0.825rem;
            font-weight: 700;
            color: #a5b4fc;
            letter-spacing: 0.04em;
        }

        .hero-title {
            font-size: clamp(2.35rem, 4.2vw, 3.5rem);
            line-height: 1.15;
            letter-spacing: -0.025em;
            color: #ffffff;
        }

        .hero-gradient-text {
            background: linear-gradient(135deg, #a5b4fc 0%, #38bdf8 50%, #34d399 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-description {
            font-size: 1.05rem;
            color: #cbd5e1;
            line-height: 1.7;
            max-width: 560px;
        }

        /* Mockup & Levitation */
        .hero-mockup-wrapper {
            position: relative;
        }

        .hero-mockup-card {
            position: relative;
            border-radius: 1.25rem;
            padding: 0.4rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.5),
                0 0 40px rgba(79, 70, 229, 0.25);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            animation: heroLevitation 6s ease-in-out infinite;
        }

        .hero-mockup-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 
                0 35px 60px -15px rgba(0, 0, 0, 0.6),
                0 0 50px rgba(79, 70, 229, 0.4);
            border-color: rgba(165, 180, 252, 0.4);
        }

        @keyframes heroLevitation {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .hero-mockup-img {
            border-radius: 0.95rem;
            display: block;
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .hero-float-badge {
            position: absolute;
            bottom: -15px;
            left: -15px;
            background: rgba(15, 23, 42, 0.92);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 0.75rem 1rem;
            border-radius: 0.95rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: heroLevitation 5s ease-in-out 1s infinite;
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

        /* Schema Cards */
        .schema-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            padding: 2rem 1.75rem;
            transition: all 0.25s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04);
        }

        .schema-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 36px -6px rgba(15, 23, 42, 0.1);
            border-color: #cbd5e1;
        }

        .schema-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .schema-card-perancangan::before {
            background: linear-gradient(90deg, #4f46e5, #8b5cf6);
        }

        .schema-card-implementasi::before {
            background: linear-gradient(90deg, #0d9488, #10b981);
        }

        .schema-card-prestasi::before {
            background: linear-gradient(90deg, #f59e0b, #ef4444);
        }

        .schema-icon-box {
            width: 3.25rem;
            height: 3.25rem;
            border-radius: 1rem;
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

        /* =========================================================
           VERTICAL TIMELINE SECTION
           ========================================================= */
        .v-timeline-wrapper {
            position: relative;
            max-width: 960px;
            margin: 0 auto;
            padding: 2rem 0;
        }

        .v-timeline-wrapper::before {
            content: '';
            position: absolute;
            top: 25px;
            bottom: 35px;
            left: 50%;
            width: 3px;
            background: linear-gradient(180deg, #4f46e5 0%, #cbd5e1 50%, #10b981 100%);
            transform: translateX(-50%);
            border-radius: 999px;
        }

        .v-timeline-item {
            position: relative;
            width: 50%;
            margin-bottom: 2.75rem;
        }

        .v-timeline-item:last-child {
            margin-bottom: 0;
        }

        .v-timeline-item.left {
            left: 0;
            padding-right: 3.5rem;
        }

        .v-timeline-item.right {
            left: 50%;
            padding-left: 3.5rem;
        }

        .v-timeline-node {
            position: absolute;
            top: 18px;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #ffffff;
            border: 3px solid var(--app-primary);
            color: var(--app-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            z-index: 3;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.18);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .v-timeline-item.left .v-timeline-node {
            right: -24px;
            left: auto;
        }

        .v-timeline-item.right .v-timeline-node {
            left: -24px;
            right: auto;
        }

        .v-timeline-item:hover .v-timeline-node {
            transform: scale(1.15);
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.28);
        }

        /* Danger / Highlight Node (Batas Akhir Pengajuan Judul) */
        .v-timeline-node.danger-node {
            border-color: #ef4444;
            background: #ef4444;
            color: #ffffff;
            animation: pulseDangerNode 2s infinite;
        }

        @keyframes pulseDangerNode {
            0% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.6);
            }
            70% {
                box-shadow: 0 0 0 14px rgba(239, 68, 68, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
            }
        }

        /* Success Node (Sidang Akhir) */
        .v-timeline-node.success-node {
            border-color: #10b981;
            background: #ffffff;
            color: #10b981;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.25);
        }

        /* Warning Node */
        .v-timeline-node.warning-node {
            border-color: #f59e0b;
            background: #ffffff;
            color: #d97706;
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.25);
        }

        .v-timeline-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.15rem;
            padding: 1.5rem 1.75rem;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
            transition: all 0.25s ease;
            position: relative;
            text-align: left;
        }

        .v-timeline-item:hover .v-timeline-card {
            transform: translateY(-3px);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
            border-color: #cbd5e1;
        }

        /* Urgent Card Highlight (Batas Akhir Pengajuan Judul) */
        .v-timeline-card.card-urgent {
            border: 2px solid #ef4444;
            background: linear-gradient(145deg, #ffffff 0%, #fff5f5 100%);
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.12);
        }

        .v-timeline-card.card-urgent:hover {
            box-shadow: 0 14px 32px rgba(239, 68, 68, 0.2);
            border-color: #dc2626;
        }

        .timeline-date-badge {
            font-family: var(--font-mono);
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.35rem 0.75rem;
            border-radius: 0.5rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        /* Mobile responsive */
        @media (max-width: 767.98px) {
            .v-timeline-wrapper::before {
                left: 22px;
            }

            .v-timeline-item {
                width: 100% !important;
                left: 0 !important;
                padding-left: 60px !important;
                padding-right: 0 !important;
                margin-bottom: 2rem;
            }

            .v-timeline-node {
                left: 0 !important;
                right: auto !important;
                top: 14px;
                width: 44px;
                height: 44px;
                font-size: 1rem;
            }

            .v-timeline-card {
                padding: 1.25rem;
            }
        }
    </style>
    <link rel="icon" href="{{ asset('favicon.jpg') }}" type="image/jpg">
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
            <a href="#dokumen" class="nav-link-custom">Dokumen</a>
            <a href="#skema" class="nav-link-custom">Skema PA</a>
            <a href="#jadwal" class="nav-link-custom">Jadwal & Timeline</a>
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
<section class="hero-section">
    <div class="hero-bg-grid"></div>
    <div class="hero-glow-1"></div>
    <div class="hero-glow-2"></div>

    <div class="container position-relative">
        <div class="row align-items-center g-5">
            <!-- Kolom Kiri (Teks) -->
            <div class="col-lg-6 text-start">
                <div class="hero-badge mb-3">
                    <i class="bi bi-mortarboard-fill me-1"></i> D3 Sistem Informasi Polsub
                </div>
                <h1 class="hero-title fw-extrabold mb-3">
                    Satu Portal untuk Semua Urusan <span class="hero-gradient-text">Proyek Akhir Anda.</span>
                </h1>
                <p class="hero-description mb-4">
                    Ucapkan selamat tinggal pada tumpukan kertas. Ajukan judul, catat bimbingan, hingga daftar sidang akhir dengan lebih cepat, transparan, dan terstruktur melalui PAMASESA.
                </p>

                <div class="d-flex align-items-center gap-3 flex-wrap mb-4">
                    <a href="{{ route('login') }}" class="btn-landing-primary">
                        <i class="bi bi-box-arrow-in-right fs-5"></i> Masuk ke Portal
                    </a>
                    <a href="#dokumen" class="btn-landing-outline">
                        <i class="bi bi-journal-bookmark fs-5"></i> Lihat Panduan
                    </a>
                </div>

                <div class="d-flex align-items-center gap-4 text-slate-300 small pt-3 border-top border-white border-opacity-10 flex-wrap">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success"></i>
                        <span>100% Digital Workflow</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success"></i>
                        <span>3 Pilihan Skema PA</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success"></i>
                        <span>Monitoring Real-Time</span>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan (Visual / Mockup) -->
            <div class="col-lg-6">
                <div class="hero-mockup-wrapper">
                    <div class="hero-mockup-card">
                        <img src="{{ asset('images/hero-mockup.jpg') }}" alt="Mockup Dashboard PAMASESA" class="img-fluid hero-mockup-img shadow-sm">
                    </div>
                    <!-- Floating Badge -->
                    <div class="hero-float-badge d-none d-sm-flex">
                        <div class="brand-icon-nav" style="width: 2.25rem; height: 2.25rem; font-size: 1rem; border-radius: 0.6rem;">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-white small">Sistem PA Terverifikasi</div>
                            <div class="text-success small fw-semibold" style="font-size: 0.75rem;">
                                <i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i> Portal Aktif D3 SI
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Real-time Public Stats Counter -->
        <div class="row g-3 mt-5 pt-4 border-top border-white border-opacity-10">
            <div class="col-md-4 col-sm-12">
                <div class="stat-counter-card">
                    <div class="stat-counter-number">{{ $studentCount }}</div>
                    <div class="stat-counter-label">Mahasiswa Aktif PA</div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="stat-counter-card">
                    <div class="stat-counter-number">{{ $approvedProjectCount }}</div>
                    <div class="stat-counter-label">Judul Ter-ACC & Lulus</div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
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

<!-- Section 3 Skema Proyek Akhir -->
<section id="skema" class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center max-width-md mx-auto mb-5">
            <span class="badge bg-indigo-subtle text-primary border px-3 py-2 fw-bold mb-2">JALUR PENYELESAIAN PA</span>
            <h2 class="section-title">3 Skema Proyek Akhir</h2>
            <p class="section-subtitle">Pilih jalur penyelesaian Proyek Akhir yang paling sesuai dengan minat dan pencapaian Anda.</p>
        </div>

        <div class="row g-4">
            <!-- Skema 1: Perancangan -->
            <div class="col-lg-4 col-md-6">
                <div class="schema-card schema-card-perancangan">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="schema-icon-box" style="background: rgba(79, 70, 229, 0.12); color: #4f46e5;">
                            <i class="bi bi-bezier2"></i>
                        </div>
                        <span class="badge border px-2 py-1 small fw-bold" style="background: rgba(79, 70, 229, 0.1); color: #4338ca; border-color: rgba(79, 70, 229, 0.25) !important;">Skema 1</span>
                    </div>
                    <h4 class="fw-bold fs-5 mb-2 text-slate-900">Skema Perancangan</h4>
                    <p class="text-muted small mb-4 flex-grow-1" style="line-height: 1.65;">
                        Berfokus pada analisis dan perancangan sistem/UI/UX tanpa kewajiban coding hingga tahap deployment akhir. Output berupa dokumen perancangan dan prototipe.
                    </p>
                    <div class="pt-3 border-top border-slate-100 mt-auto">
                        <div class="d-flex align-items-center gap-2 small fw-semibold" style="color: #334155;">
                            <i class="bi bi-file-earmark-diff fs-6" style="color: #4f46e5;"></i>
                            <span>Output: Dokumen & Prototipe</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skema 2: Implementasi -->
            <div class="col-lg-4 col-md-6">
                <div class="schema-card schema-card-implementasi">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="schema-icon-box" style="background: rgba(13, 148, 136, 0.12); color: #0d9488;">
                            <i class="bi bi-code-slash"></i>
                        </div>
                        <span class="badge border px-2 py-1 small fw-bold" style="background: rgba(13, 148, 136, 0.1); color: #0f766e; border-color: rgba(13, 148, 136, 0.25) !important;">Skema 2</span>
                    </div>
                    <h4 class="fw-bold fs-5 mb-2 text-slate-900">Skema Implementasi</h4>
                    <p class="text-muted small mb-4 flex-grow-1" style="line-height: 1.65;">
                        Berfokus pada pengembangan sistem nyata (software/hardware) berbasis penyelesaian masalah di lapangan. Output berupa aplikasi fungsional.
                    </p>
                    <div class="pt-3 border-top border-slate-100 mt-auto">
                        <div class="d-flex align-items-center gap-2 small fw-semibold" style="color: #334155;">
                            <i class="bi bi-laptop fs-6" style="color: #0d9488;"></i>
                            <span>Output: Aplikasi Fungsional</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skema 3: Mahasiswa Berprestasi -->
            <div class="col-lg-4 col-md-12">
                <div class="schema-card schema-card-prestasi">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="schema-icon-box" style="background: rgba(245, 158, 11, 0.14); color: #d97706;">
                            <i class="bi bi-trophy-fill"></i>
                        </div>
                        <span class="badge border px-2 py-1 small fw-bold" style="background: rgba(245, 158, 11, 0.1); color: #b45309; border-color: rgba(245, 158, 11, 0.25) !important;">Skema 3</span>
                    </div>
                    <h4 class="fw-bold fs-5 mb-2 text-slate-900">Skema Mahasiswa Berprestasi</h4>
                    <p class="text-muted small mb-4 flex-grow-1" style="line-height: 1.65;">
                        Jalur ekuivalensi bagi mahasiswa yang memenangkan kompetisi tingkat Nasional/Internasional (misal: KMIPN, Gemastik) atau memiliki karya luar biasa yang diakui. Output berupa laporan konversi prestasi.
                    </p>
                    <div class="pt-3 border-top border-slate-100 mt-auto">
                        <div class="d-flex align-items-center gap-2 small fw-semibold" style="color: #334155;">
                            <i class="bi bi-award fs-6" style="color: #d97706;"></i>
                            <span>Output: Laporan Konversi Prestasi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Bidang Fokus Penelitian SI -->
<section id="bidang-fokus" class="py-5" style="background-color: #f8fafc;">
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

<!-- Section Timeline & Jadwal Penting -->
<section id="jadwal" class="py-5 bg-white border-top border-bottom">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="badge bg-indigo-subtle text-primary border border-primary border-opacity-25 px-3 py-2 fw-bold mb-2">
                <i class="fa-regular fa-calendar-days me-1"></i> AGENDA AKADEMIK
            </span>
            <h2 class="section-title">Timeline & Jadwal Penting Proyek Akhir 2026/2027</h2>
            <p class="text-muted text-center mx-auto" style="max-width: 680px;">
                Pantau seluruh jadwal dan batas tenggat waktu krusial mulai dari sosialisasi, pengajuan judul, hingga sidang kelulusan agar progres Proyek Akhir Anda tepat waktu.
            </p>
        </div>

        <div class="v-timeline-wrapper">
            <!-- Timeline Item 1: Sosialisasi -->
            <div class="v-timeline-item left">
                <div class="v-timeline-node">
                    <i class="fa-solid fa-flag-checkered"></i>
                </div>
                <div class="v-timeline-card">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                        <span class="badge bg-primary-subtle text-primary border border-primary border-opacity-25 px-2.5 py-1">
                            Titik Awal
                        </span>
                        <span class="timeline-date-badge bg-light text-secondary border">
                            <i class="fa-regular fa-calendar me-1"></i> 15 Sep 2026
                        </span>
                    </div>
                    <h5 class="fw-bold fs-5 mb-2 text-slate-900">Sosialisasi & Pembekalan PA</h5>
                    <p class="text-muted small mb-0">
                        Pemberian pedoman Proyek Akhir tahun akademik 2026/2027, sosialisasi 3 skema (Perancangan, Implementasi, & Prestasi), tata tulis dokumen, serta aktivasi akun portal PAMASESA.
                    </p>
                </div>
            </div>

            <!-- Timeline Item 2: Batas Akhir Pengajuan Judul (PENTING / DANGER) -->
            <div class="v-timeline-item right">
                <div class="v-timeline-node danger-node">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div class="v-timeline-card card-urgent">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                        <span class="badge bg-danger text-white px-2.5 py-1 shadow-sm">
                            <i class="fa-solid fa-circle-exclamation me-1"></i> PENTING / DEADLINE
                        </span>
                        <span class="timeline-date-badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">
                            <i class="fa-solid fa-clock me-1"></i> 05 Okt 2026 • 23:59 WIB
                        </span>
                    </div>
                    <h5 class="fw-bold fs-5 mb-1 text-danger">Batas Akhir Pengajuan Judul</h5>
                    <p class="text-muted small mb-2">
                        Batas akhir penginputan formulir proposal judul, dokumen pendukung, dan pemilihan skema Proyek Akhir di portal PAMASESA.
                    </p>
                    <div class="p-2.5 rounded-3 bg-danger bg-opacity-10 text-danger-emphasis small border border-danger border-opacity-25 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-bell text-danger fs-6"></i>
                        <span><strong>Penting:</strong> Portal akan terkunci otomatis setelah batas akhir. Tidak ada toleransi keterlambatan tanpa konfirmasi koordinator.</span>
                    </div>
                </div>
            </div>

            <!-- Timeline Item 3: Pengumuman Dosen Pembimbing -->
            <div class="v-timeline-item left">
                <div class="v-timeline-node">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <div class="v-timeline-card">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                        <span class="badge bg-indigo-subtle text-primary border border-primary border-opacity-25 px-2.5 py-1">
                            Penetapan SK
                        </span>
                        <span class="timeline-date-badge bg-light text-secondary border">
                            <i class="fa-regular fa-calendar me-1"></i> 15 Okt 2026
                        </span>
                    </div>
                    <h5 class="fw-bold fs-5 mb-2 text-slate-900">Pengumuman Dosen Pembimbing</h5>
                    <p class="text-muted small mb-0">
                        Pengumuman hasil review judul dan penerbitan Surat Keputusan (SK) pembimbing. Mahasiswa dapat melihat Dosen Pembimbing 1 dan 2 langsung di dashboard akun masing-masing.
                    </p>
                </div>
            </div>

            <!-- Timeline Item 4: Batas Pendaftaran Seminar Proposal -->
            <div class="v-timeline-item right">
                <div class="v-timeline-node">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
                <div class="v-timeline-card">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                        <span class="badge bg-info-subtle text-info-emphasis border border-info border-opacity-25 px-2.5 py-1">
                            Pendaftaran Sempro
                        </span>
                        <span class="timeline-date-badge bg-light text-secondary border">
                            <i class="fa-regular fa-calendar me-1"></i> 15 Des 2026
                        </span>
                    </div>
                    <h5 class="fw-bold fs-5 mb-2 text-slate-900">Batas Pendaftaran Seminar Proposal</h5>
                    <p class="text-muted small mb-0">
                        Batas akhir pengunggahan draft naskah proposal BAB 1-3 yang telah mendapatkan persetujuan (acc) dosen pembimbing beserta formulir pendaftaran ujian seminar proposal.
                    </p>
                </div>
            </div>

            <!-- Timeline Item 5: Pelaksanaan Seminar Proposal -->
            <div class="v-timeline-item left">
                <div class="v-timeline-node">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
                <div class="v-timeline-card">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                        <span class="badge bg-secondary-subtle text-secondary-emphasis border px-2.5 py-1">
                            Ujian Sempro
                        </span>
                        <span class="timeline-date-badge bg-light text-secondary border">
                            <i class="fa-regular fa-calendar me-1"></i> 05 – 12 Jan 2027
                        </span>
                    </div>
                    <h5 class="fw-bold fs-5 mb-2 text-slate-900">Pelaksanaan Seminar Proposal</h5>
                    <p class="text-muted small mb-0">
                        Presentasi usulan penelitian di hadapan tim dosen penguji untuk memvalidasi kelayakan rumusan masalah, metodologi, dan desain solusi teknis yang diajukan.
                    </p>
                </div>
            </div>

            <!-- Timeline Item 6: Batas Pendaftaran Sidang Akhir -->
            <div class="v-timeline-item right">
                <div class="v-timeline-node warning-node">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div class="v-timeline-card">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning border-opacity-50 px-2.5 py-1 fw-bold">
                            Tenggat Berkas Sidang
                        </span>
                        <span class="timeline-date-badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning border-opacity-25">
                            <i class="fa-solid fa-clock me-1"></i> 15 Jun 2027 • 23:59 WIB
                        </span>
                    </div>
                    <h5 class="fw-bold fs-5 mb-2 text-slate-900">Batas Pendaftaran Sidang Akhir</h5>
                    <p class="text-muted small mb-0">
                        Batas akhir upload laporan lengkap (BAB 1–5), aplikasi/prototipe siap uji, persetujuan pembimbing, bebas tanggungan perpustakaan/jurusan, dan lembar bimbingan minimal 8 kali.
                    </p>
                </div>
            </div>

            <!-- Timeline Item 7: Pelaksanaan Sidang Akhir -->
            <div class="v-timeline-item left">
                <div class="v-timeline-node success-node">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div class="v-timeline-card">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                        <span class="badge bg-success-subtle text-success border border-success border-opacity-25 px-2.5 py-1 fw-bold">
                            Tahap Final (Kelulusan)
                        </span>
                        <span class="timeline-date-badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                            <i class="fa-solid fa-calendar-check me-1"></i> 01 – 10 Jul 2027
                        </span>
                    </div>
                    <h5 class="fw-bold fs-5 mb-2 text-slate-900">Pelaksanaan Sidang Akhir</h5>
                    <p class="text-muted small mb-0">
                        Ujian sidang akhir tugas akhir berupa demonstrasi sistem langsung, verifikasi hasil pengujian, dan sesi tanya jawab dewan penguji sebagai penentu kelulusan mahasiswa D3 Sistem Informasi.
                    </p>
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

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed fw-bold text-slate-800" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        Apakah juara lomba bisa dikonversi menjadi Proyek Akhir?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-muted small">
                        Ya, sangat bisa! D3 Sistem Informasi menyediakan <strong>Skema Mahasiswa Berprestasi</strong>. Jika Anda memenangkan lomba tingkat Nasional/Internasional (seperti KMIPN, Gemastik, dll) yang relevan dengan IT, karya Anda dapat dikonversi menjadi Proyek Akhir tanpa harus membuat sistem baru dari nol.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFive">
                    <button class="accordion-button collapsed fw-bold text-slate-800" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        Apa bedanya skema Perancangan dan Implementasi?
                    </button>
                </h2>
                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-muted small">
                        <strong>Skema Perancangan</strong> berfokus pada kedalaman analisis bisnis dan desain prototipe (seperti UI/UX Figma atau arsitektur sistem), sedangkan <strong>skema Implementasi</strong> mewajibkan mahasiswa untuk memprogram (coding) dan menghasilkan aplikasi yang siap pakai.
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
                    <li><a href="#skema" class="footer-link"><i class="bi bi-chevron-right me-1 text-primary small"></i> 3 Skema PA</a></li>
                    <li><a href="#jadwal" class="footer-link"><i class="bi bi-chevron-right me-1 text-primary small"></i> Jadwal & Timeline</a></li>
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
