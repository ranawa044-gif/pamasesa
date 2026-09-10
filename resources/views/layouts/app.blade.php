<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'PAMASESA — Manajemen Proyek Akhir' }}</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --app-bg: #f8fafc;
            --app-surface: #ffffff;
            --app-sidebar-bg: #0f172a;
            --app-sidebar-border: rgba(255, 255, 255, 0.08);
            --app-sidebar-text: #94a3b8;
            --app-sidebar-active: #ffffff;
            --app-text: #0f172a;
            --app-muted: #64748b;
            --app-border: #e2e8f0;
            --app-primary: #4f46e5;
            --app-primary-dark: #4338ca;
            --app-primary-light: #eeeffe;
            --app-success: #10b981;
            --app-warning: #f59e0b;
            --app-danger: #ef4444;
            --app-info: #06b6d4;
            --app-radius: 1rem;
            --app-card-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
            --app-card-hover-shadow: 0 12px 30px -4px rgba(15, 23, 42, 0.08), 0 4px 12px -2px rgba(15, 23, 42, 0.04);
            --font-heading: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            --font-body: 'Inter', system-ui, -apple-system, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        body {
            background-color: var(--app-bg);
            background-image: 
                radial-gradient(at 0% 0%, rgba(79, 70, 229, 0.04) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(13, 148, 136, 0.04) 0px, transparent 50%);
            color: var(--app-text);
            font-family: var(--font-body);
            font-size: 0.9375rem;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
            font-family: var(--font-heading);
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--app-text);
        }

        .code, code, pre, .font-mono {
            font-family: var(--font-mono);
        }

        /* Sidebar Styling */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1e293b 0%, var(--app-sidebar-bg) 100%);
            border-right: 1px solid var(--app-sidebar-border);
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            padding: 1.5rem 1.25rem;
        }

        .brand-mark {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid var(--app-sidebar-border);
        }

        .brand-icon {
            width: 2.65rem;
            height: 2.65rem;
            border-radius: 0.85rem;
            background: linear-gradient(135deg, #4f46e5 0%, #0d9488 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 8px 16px -4px rgba(79, 70, 229, 0.4);
        }

        .brand-title {
            font-family: var(--font-heading);
            font-weight: 800;
            font-size: 1.15rem;
            color: #ffffff;
            letter-spacing: -0.01em;
            line-height: 1.1;
        }

        .brand-subtitle {
            font-size: 0.725rem;
            color: #94a3b8;
            font-weight: 500;
        }

        .user-profile-widget {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 0.85rem;
            padding: 0.85rem;
            margin: 1.25rem 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.65rem;
            background: rgba(79, 70, 229, 0.2);
            border: 1px solid rgba(79, 70, 229, 0.4);
            color: #818cf8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .role-badge {
            font-size: 0.675rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            padding: 0.2rem 0.5rem;
            border-radius: 0.4rem;
            text-transform: uppercase;
            display: inline-block;
        }
        .role-badge-ADMIN { background: rgba(239, 68, 68, 0.15); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3); }
        .role-badge-DOSEN { background: rgba(6, 182, 212, 0.15); color: #67e8f9; border: 1px solid rgba(6, 182, 212, 0.3); }
        .role-badge-MAHASISWA { background: rgba(16, 185, 129, 0.15); color: #6ee7b7; border: 1px solid rgba(16, 185, 129, 0.3); }

        .nav-label {
            color: #64748b;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin: 0.75rem 0 0.5rem 0.5rem;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            flex: 1;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 0.9rem;
            color: var(--app-sidebar-text);
            text-decoration: none;
            border-radius: 0.75rem;
            font-weight: 500;
            font-size: 0.8875rem;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }

        .sidebar-nav a i {
            font-size: 1.1rem;
            color: #64748b;
            transition: color 0.2s ease;
        }

        .sidebar-nav a:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.06);
        }

        .sidebar-nav a:hover i {
            color: #a5b4fc;
        }

        .sidebar-nav a.active {
            color: #ffffff;
            background: rgba(79, 70, 229, 0.25);
            border: 1px solid rgba(129, 140, 248, 0.3);
            font-weight: 600;
        }

        .sidebar-nav a.active i {
            color: #818cf8;
        }

        .sidebar-nav a.active::before {
            content: '';
            position: absolute;
            left: -0.25rem;
            top: 20%;
            height: 60%;
            width: 0.25rem;
            background: #818cf8;
            border-radius: 0 0.25rem 0.25rem 0;
            box-shadow: 0 0 10px #818cf8;
        }

        /* Topbar & Content Area */
        .content-area {
            padding: 2rem;
            min-height: 100vh;
        }

        .topbar {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--app-border);
            border-radius: var(--app-radius);
            padding: 1.15rem 1.5rem;
            margin-bottom: 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--app-card-shadow);
        }

        .page-kicker {
            font-size: 0.775rem;
            font-weight: 600;
            color: var(--app-primary);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 0.15rem;
        }

        .page-heading {
            font-size: 1.35rem;
            font-weight: 800;
            margin: 0;
            line-height: 1.2;
        }

        /* Hallmark Cards & Panels */
        .card {
            background: var(--app-surface);
            border: 1px solid var(--app-border) !important;
            border-radius: var(--app-radius);
            box-shadow: var(--app-card-shadow) !important;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .card:hover {
            box-shadow: var(--app-card-hover-shadow) !important;
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--app-border);
            padding: 1.15rem 1.35rem;
            font-weight: 700;
        }

        .card-body {
            padding: 1.35rem;
        }

        .card-footer {
            background: transparent;
            border-top: 1px solid var(--app-border);
            padding: 1rem 1.35rem;
        }

        /* Stat Card Hallmark Styling */
        .card-stat {
            position: relative;
            overflow: hidden;
            border-radius: var(--app-radius);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-stat:hover {
            transform: translateY(-2px);
        }

        .card-stat .stat-icon {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            margin-bottom: 0.85rem;
        }

        .stat-icon-indigo { background: #eeeffe; color: #4f46e5; }
        .stat-icon-teal { background: #ccfbf1; color: #0d9488; }
        .stat-icon-amber { background: #fef3c7; color: #d97706; }
        .stat-icon-rose { background: #ffe4e6; color: #e11d48; }
        .stat-icon-cyan { background: #cffaff; color: #0891b2; }

        .card-stat .stat-label {
            font-size: 0.825rem;
            font-weight: 600;
            color: var(--app-muted);
            margin-bottom: 0.35rem;
        }

        .card-stat .stat-value {
            font-family: var(--font-heading);
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--app-text);
            line-height: 1.1;
        }

        /* Tables Styling */
        .table-responsive {
            border-radius: 0.85rem;
            border: 1px solid var(--app-border);
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        .table thead th {
            background: #f1f5f9;
            color: #475569;
            font-family: var(--font-heading);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 0.9rem 1.15rem;
            border-bottom: 1px solid var(--app-border);
        }

        .table tbody td {
            padding: 0.95rem 1.15rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--app-border);
            color: #334155;
        }

        .table tbody tr:last-child td {
            border-bottom: 0;
        }

        .table-hover tbody tr {
            transition: background-color 0.15s ease;
        }

        .table-hover tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Buttons Styling */
        .btn {
            font-family: var(--font-heading);
            font-weight: 600;
            border-radius: 0.65rem;
            padding: 0.55rem 1.1rem;
            font-size: 0.875rem;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn-primary {
            background: var(--app-primary);
            border-color: var(--app-primary);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover {
            background: var(--app-primary-dark);
            border-color: var(--app-primary-dark);
            box-shadow: 0 6px 18px rgba(79, 70, 229, 0.4);
        }

        .btn-outline-primary {
            color: var(--app-primary);
            border-color: #c7d2fe;
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--app-primary-light);
            border-color: var(--app-primary);
            color: var(--app-primary-dark);
        }

        .btn-sm {
            padding: 0.38rem 0.75rem;
            font-size: 0.8125rem;
            border-radius: 0.5rem;
        }

        /* Form Control Styling */
        .form-label {
            font-family: var(--font-heading);
            font-weight: 650;
            font-size: 0.85rem;
            color: #334155;
            margin-bottom: 0.4rem;
        }

        .form-control, .form-select {
            border-radius: 0.65rem;
            border: 1px solid #cbd5e1;
            padding: 0.65rem 0.9rem;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            background-color: #ffffff;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--app-primary);
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.15);
            outline: none;
        }

        /* Badges Styling */
        .badge {
            font-family: var(--font-heading);
            font-weight: 700;
            padding: 0.35em 0.75em;
            border-radius: 9999px;
            font-size: 0.75rem;
            letter-spacing: 0.02em;
        }

        /* Alerts Styling */
        .alert {
            border-radius: 0.85rem;
            border: 1px solid transparent;
            padding: 1rem 1.25rem;
            font-weight: 500;
            box-shadow: var(--app-card-shadow);
            margin-bottom: 1.5rem;
        }
        .alert-success { background: #ecfdf5; border-color: #a7f3d0; color: #065f46; }
        .alert-danger { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
        .alert-warning { background: #fffbeb; border-color: #fde68a; color: #92400e; }
        .alert-info { background: #ecfeff; border-color: #a5f3fc; color: #155e75; }

        /* Timeline Hallmark Component */
        .timeline-hallmark {
            position: relative;
            padding-left: 2rem;
        }
        .timeline-hallmark::before {
            content: '';
            position: absolute;
            left: 0.85rem;
            top: 0.5rem;
            bottom: 0.5rem;
            width: 2px;
            background: #e2e8f0;
        }
        .timeline-step {
            position: relative;
            margin-bottom: 2rem;
        }
        .timeline-step:last-child {
            margin-bottom: 0;
        }
        .timeline-number {
            position: absolute;
            left: -2rem;
            top: 0;
            width: 1.75rem;
            height: 1.75rem;
            border-radius: 50%;
            background: var(--app-primary);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 0 4px #ffffff, 0 2px 8px rgba(79, 70, 229, 0.3);
        }

        @media (max-width: 991.98px) {
            .sidebar {
                min-height: auto;
                position: static;
            }
            .content-area {
                padding: 1.25rem;
            }
            .topbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Sidebar Navigation -->
        <aside class="col-lg-2 sidebar">
            <div class="brand-mark">
                <div class="brand-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div>
                    <div class="brand-title">PAMASESA</div>
                    <div class="brand-subtitle">D3 Sistem Informasi</div>
                </div>
            </div>

            <div class="user-profile-widget">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <div class="fw-bold text-white text-truncate" style="font-size: 0.85rem;">{{ auth()->user()->name }}</div>
                    <span class="role-badge role-badge-{{ auth()->user()->role }}">
                        {{ auth()->user()->role }}
                    </span>
                </div>
            </div>

            <div class="nav-label">Menu Utama</div>
            <nav class="sidebar-nav">
                <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>

                @if(auth()->user()->role === 'ADMIN')
                    <a class="{{ request()->routeIs('admin.students.*') ? 'active' : '' }}" href="{{ route('admin.students.index') }}">
                        <i class="bi bi-people-fill"></i> Master Mahasiswa
                    </a>
                    <a class="{{ request()->routeIs('admin.lecturers.*') ? 'active' : '' }}" href="{{ route('admin.lecturers.index') }}">
                        <i class="bi bi-person-badge-fill"></i> Master Dosen
                    </a>
                    <a class="{{ request()->routeIs('admin.titles.*') ? 'active' : '' }}" href="{{ route('admin.titles.index') }}">
                        <i class="bi bi-journal-check"></i> Validasi Judul
                    </a>
                    <a class="{{ request()->routeIs('admin.supervisors.*') ? 'active' : '' }}" href="{{ route('admin.supervisors.index') }}">
                        <i class="bi bi-diagram-3-fill"></i> Pembimbing
                    </a>
                    <a class="{{ request()->routeIs('admin.seminars.*') ? 'active' : '' }}" href="{{ route('admin.seminars.index') }}">
                        <i class="bi bi-calendar-event-fill"></i> Seminar Proposal
                    </a>
                    <a class="{{ request()->routeIs('admin.final-defenses.*') ? 'active' : '' }}" href="{{ route('admin.final-defenses.index') }}">
                        <i class="bi bi-award-fill"></i> Sidang Akhir
                    </a>
                    <a class="{{ request()->routeIs('admin.seminars.monitor') ? 'active' : '' }}" href="{{ route('admin.seminars.monitor') }}">
                        <i class="bi bi-bar-chart-fill"></i> Monitoring Seminar
                    </a>
                    <a class="{{ request()->routeIs('admin.workflow') ? 'active' : '' }}" href="{{ route('admin.workflow') }}">
                        <i class="bi bi-diagram-2-fill"></i> Alur Sistem
                    </a>
                    <a class="{{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}" href="{{ route('admin.announcements.index') }}">
                        <i class="bi bi-megaphone-fill"></i> Informasi & Dokumen
                    </a>
                @elseif(auth()->user()->role === 'MAHASISWA')
                    <a class="{{ (request()->routeIs('student.final-project.*') || request()->routeIs('student.pengajuan.*')) ? 'active' : '' }}" href="{{ route('student.pengajuan.create') }}">
                        <i class="bi bi-file-earmark-text-fill"></i> Pengajuan Judul
                    </a>
                    <a class="{{ request()->routeIs('student.progress.*') ? 'active' : '' }}" href="{{ route('student.progress.index') }}">
                        <i class="bi bi-activity"></i> Progress PA
                    </a>
                    <a class="{{ request()->routeIs('student.seminars.*') ? 'active' : '' }}" href="{{ route('student.seminars.index') }}">
                        <i class="bi bi-calendar3-event-fill"></i> Seminar Proposal
                    </a>
                    <a class="{{ request()->routeIs('student.final-defenses.*') ? 'active' : '' }}" href="{{ route('student.final-defenses.index') }}">
                        <i class="bi bi-award-fill"></i> Sidang Akhir
                    </a>
                @elseif(auth()->user()->role === 'DOSEN')
                    <a class="{{ request()->routeIs('lecturer.guidances.*') ? 'active' : '' }}" href="{{ route('lecturer.guidances.index') }}">
                        <i class="bi bi-people-fill"></i> Mahasiswa Bimbingan
                    </a>
                    <a class="{{ request()->routeIs('lecturer.seminars.*') ? 'active' : '' }}" href="{{ route('lecturer.seminars.index') }}">
                        <i class="bi bi-journal-text"></i> Seminar Proposal
                    </a>
                @endif
            </nav>

            <form method="post" action="{{ route('logout') }}" class="mt-auto pt-3 border-top border-secondary border-opacity-25">
                @csrf
                <button class="btn btn-outline-light w-100 py-2" style="border-color: rgba(255,255,255,0.15); font-size: 0.85rem;">
                    <i class="bi bi-box-arrow-left me-1"></i> Logout
                </button>
            </form>
        </aside>

        <!-- Main Content Area -->
        <main class="col-lg-10 content-area">
            <!-- Topbar Header -->
            <div class="topbar">
                <div>
                    <div class="page-kicker"><i class="bi bi-shield-check me-1"></i> PAMASESA • System Management</div>
                    <h1 class="page-heading">{{ $heading ?? 'Dashboard' }}</h1>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-white text-dark border px-3 py-2 shadow-sm d-flex align-items-center gap-2" style="font-weight: 600;">
                        <i class="bi bi-calendar-check text-primary"></i> {{ now()->translatedFormat('d F Y') }}
                    </span>
                </div>
            </div>

            <!-- Global Flash Messages & Alerts -->
            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif
            @if(session('import_summary'))
                <div class="alert alert-info">
                    <strong class="d-block mb-1"><i class="bi bi-info-circle-fill me-1"></i> Ringkasan Impor Data:</strong>
                    <ul class="mb-0 ps-3">
                        @foreach(session('import_summary') as $label => $value)
                            <li>{{ $label }}: {{ $value }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(session('import_errors'))
                <div class="alert alert-warning">
                    <strong class="d-block mb-1"><i class="bi bi-exclamation-circle-fill me-1"></i> Detail Catatan Impor:</strong>
                    <ul class="mb-0 ps-3">
                        @foreach(session('import_errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <strong class="d-block mb-1"><i class="bi bi-x-circle-fill me-1"></i> Silakan periksa kembali formulir Anda:</strong>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

