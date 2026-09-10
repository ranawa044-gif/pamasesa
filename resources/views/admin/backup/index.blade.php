@extends('layouts.app', ['heading' => 'Backup & Download Database'])

@section('content')
<div class="row g-4 mb-4">
    {{-- HERO ACTION CARD --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);">
            <div class="card-body p-4 p-md-5 text-white position-relative">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-10 text-white mb-3" style="font-size: 0.8rem; backdrop-filter: blur(8px);">
                            <i class="bi bi-shield-lock-fill text-warning"></i>
                            <span>Keamanan & Pemeliharaan Sistem</span>
                        </div>
                        <h2 class="fw-extrabold mb-2 text-white">Cadangkan Database Sistem PAMASESA</h2>
                        <p class="text-white text-opacity-75 mb-4 fs-6" style="max-width: 650px;">
                            Unduh seluruh struktur tabel beserta seluruh data transaksi (mahasiswa, dosen, pengajuan judul, bimbingan, revisi, dan seminar) ke dalam satu file berkas standar <strong>.SQL</strong>. File ini dapat langsung digunakan untuk keperluan arsip, migrasi server, atau restore data kapan pun dibutuhkan.
                        </p>
                        <div class="d-flex flex-wrap gap-3 align-items-center">
                            <a href="{{ route('admin.backup.download') }}" class="btn btn-warning btn-lg px-4 py-3 fw-bold rounded-3 shadow-lg d-inline-flex align-items-center gap-2 text-dark">
                                <i class="bi bi-cloud-arrow-down-fill fs-4"></i>
                                <span>Download Database .SQL Sekarang</span>
                            </a>
                            <span class="text-white text-opacity-75 small">
                                <i class="bi bi-info-circle me-1"></i> Format MySQL standar (UTF-8, FK Checks disabled, Transaksi aman)
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center d-none d-lg-block">
                        <div class="d-inline-flex p-4 rounded-circle bg-white bg-opacity-10 border border-white border-opacity-20 shadow-inner">
                            <i class="bi bi-database-fill-down" style="font-size: 5.5rem; color: #fde047;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted small fw-bold text-uppercase">Nama Database</span>
                    <div class="p-2 rounded-3 bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-database-fill fs-5"></i>
                    </div>
                </div>
                <div class="fs-4 fw-extrabold text-slate-800 font-mono">{{ $dbInfo['name'] }}</div>
                <div class="small text-muted mt-1">
                    <i class="bi bi-hdd-network me-1"></i> {{ $dbInfo['host'] }}:{{ $dbInfo['port'] }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted small fw-bold text-uppercase">Total Tabel</span>
                    <div class="p-2 rounded-3 bg-success bg-opacity-10 text-success">
                        <i class="bi bi-table fs-5"></i>
                    </div>
                </div>
                <div class="fs-4 fw-extrabold text-slate-800">{{ $dbInfo['table_count'] }} <span class="fs-6 fw-normal text-muted">Tabel</span></div>
                <div class="small text-muted mt-1">
                    <i class="bi bi-cpu me-1"></i> Engine Utama: InnoDB
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted small fw-bold text-uppercase">Total Baris Data</span>
                    <div class="p-2 rounded-3 bg-info bg-opacity-10 text-info">
                        <i class="bi bi-card-checklist fs-5"></i>
                    </div>
                </div>
                <div class="fs-4 fw-extrabold text-slate-800">{{ number_format($dbInfo['total_rows'], 0, ',', '.') }}</div>
                <div class="small text-muted mt-1">
                    <i class="bi bi-check2-circle me-1"></i> Data riil aktif
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted small fw-bold text-uppercase">Ukuran Database</span>
                    <div class="p-2 rounded-3 bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-pie-chart-fill fs-5"></i>
                    </div>
                </div>
                <div class="fs-4 fw-extrabold text-slate-800">{{ $dbInfo['total_size_mb'] }} <span class="fs-6 fw-normal text-muted">MB</span></div>
                <div class="small text-muted mt-1">
                    <i class="bi bi-server me-1"></i> MySQL v{{ $dbInfo['version'] }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- DAFTAR TABEL & DETAIL UKURAN --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white py-3 px-4 border-0 d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <h5 class="fw-bold mb-0 text-slate-800 d-flex align-items-center gap-2">
                <i class="bi bi-list-columns-reverse text-primary"></i>
                Daftar Tabel & Statistik Penyimpanan
            </h5>
            <div class="text-muted small">Rincian nama tabel, jumlah data, dan alokasi memori fisik per tabel</div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="input-group input-group-sm" style="max-width: 250px;">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" id="filterTableInput" class="form-control bg-light border-start-0" placeholder="Cari nama tabel...">
            </div>
            <a href="{{ route('admin.backup.download') }}" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1">
                <i class="bi bi-download"></i> Download .SQL
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="tableList">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th>Nama Tabel</th>
                    <th class="text-center" style="width: 100px;">Engine</th>
                    <th class="text-end" style="width: 130px;">Jumlah Baris</th>
                    <th class="text-end" style="width: 130px;">Data Size</th>
                    <th class="text-end" style="width: 130px;">Index Size</th>
                    <th class="text-end" style="width: 140px;">Total Ukuran</th>
                    <th style="width: 200px;">Collation</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tables as $index => $t)
                    <tr class="table-row-item">
                        <td class="text-center text-muted fw-semibold">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-table text-primary opacity-75"></i>
                                <span class="fw-bold text-dark font-mono table-name-text">{{ $t['name'] }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border">{{ $t['engine'] }}</span>
                        </td>
                        <td class="text-end fw-semibold">
                            @if($t['rows'] > 0)
                                <span class="text-primary">{{ number_format($t['rows'], 0, ',', '.') }}</span>
                            @else
                                <span class="text-muted">0</span>
                            @endif
                        </td>
                        <td class="text-end text-muted font-mono small">{{ number_format($t['data_kb'], 1, ',', '.') }} KB</td>
                        <td class="text-end text-muted font-mono small">{{ number_format($t['index_kb'], 1, ',', '.') }} KB</td>
                        <td class="text-end fw-bold font-mono text-slate-800">
                            {{ number_format($t['total_kb'], 1, ',', '.') }} KB
                        </td>
                        <td>
                            <span class="text-muted small font-mono">{{ $t['collation'] }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="table-light border-top">
                <tr class="fw-bold">
                    <td colspan="3" class="text-end">Total Keseluruhan:</td>
                    <td class="text-end text-primary">{{ number_format($dbInfo['total_rows'], 0, ',', '.') }}</td>
                    <td colspan="2"></td>
                    <td class="text-end text-primary font-mono">{{ number_format($dbInfo['total_size_kb'], 1, ',', '.') }} KB ({{ $dbInfo['total_size_mb'] }} MB)</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- PANDUAN CARA RESTORE / IMPORT --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white py-3 px-4 border-0">
        <h5 class="fw-bold mb-0 text-slate-800 d-flex align-items-center gap-2">
            <i class="bi bi-question-circle-fill text-warning"></i>
            Panduan Cara Memulihkan (*Restore*) File Database .SQL
        </h5>
    </div>
    <div class="card-body px-4 pb-4 pt-1">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 border h-100">
                    <h6 class="fw-bold text-slate-800 mb-2">
                        <i class="bi bi-browser-chrome text-primary me-2"></i>1. Melalui phpMyAdmin (Laragon / XAMPP)
                    </h6>
                    <ol class="small text-muted mb-0 ps-3">
                        <li class="mb-1">Buka browser dan akses <strong>http://localhost/phpmyadmin</strong>.</li>
                        <li class="mb-1">Pilih database tujuan (misal: <code>{{ $dbInfo['name'] }}</code>) atau buat database baru.</li>
                        <li class="mb-1">Klik tab menu <strong>Import</strong> di bagian atas.</li>
                        <li class="mb-1">Klik tombol <strong>Choose File / Pilih Berkas</strong> dan pilih file <code>.sql</code> yang telah Anda unduh.</li>
                        <li>Gulir ke bawah dan klik tombol <strong>Import / Kirim</strong>.</li>
                    </ol>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 bg-light rounded-3 border h-100">
                    <h6 class="fw-bold text-slate-800 mb-2">
                        <i class="bi bi-terminal-fill text-dark me-2"></i>2. Melalui Terminal / Command Prompt
                    </h6>
                    <div class="small text-muted mb-2">Jalankan perintah berikut di command prompt atau PowerShell:</div>
                    <div class="bg-dark text-white p-2 rounded-2 font-mono small mb-2">
                        mysql -u {{ config('database.connections.mysql.username', 'root') }} -p {{ $dbInfo['name'] }} &lt; {{ "backup_pamasesa_" . $dbInfo['name'] . ".sql" }}
                    </div>
                    <div class="small text-muted">
                        <em>Catatan: Jika user root tidak memakai password, Anda dapat mengabaikan flag <code>-p</code>.</em>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('filterTableInput')?.addEventListener('input', function(e) {
    const query = e.target.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.table-row-item');
    rows.forEach(row => {
        const text = row.querySelector('.table-name-text')?.textContent.toLowerCase() || '';
        if (text.includes(query)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
@endsection
