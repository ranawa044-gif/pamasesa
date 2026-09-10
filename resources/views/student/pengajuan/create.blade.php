@extends('layouts.app', ['heading' => 'Pengajuan Judul Proyek Akhir'])

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            {{-- Status Submission jika sudah ada --}}
            @if($pengajuan)
                <div class="card shadow-sm border-0 mb-4 rounded-3">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="brand-icon flex-shrink-0">
                                <i class="bi {{ $pengajuan->status_pengajuan === 'approved' ? 'bi-check-circle-fill text-success' : ($pengajuan->status_pengajuan === 'rejected' ? 'bi-x-circle-fill text-danger' : 'bi-hourglass-split text-warning') }} fs-3"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <h5 class="fw-bold mb-0">
                                        @if($pengajuan->status_pengajuan === 'approved')
                                            Pengajuan Judul Disetujui (ACC)
                                        @elseif($pengajuan->status_pengajuan === 'rejected')
                                            Pengajuan Judul Ditolak
                                        @elseif($pengajuan->status_pengajuan === 'revision')
                                            Pengajuan Perlu Revisi
                                        @else
                                            Pengajuan Sedang Dalam Proses Verifikasi
                                        @endif
                                    </h5>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('student.pengajuan.export-pdf', $pengajuan->id) }}" class="btn btn-outline-danger btn-sm px-3 shadow-sm">
                                            <i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF
                                        </a>
                                        <span class="badge bg-{{ $pengajuan->status_pengajuan === 'approved' ? 'success' : ($pengajuan->status_pengajuan === 'rejected' ? 'danger' : ($pengajuan->status_pengajuan === 'revision' ? 'warning text-dark' : 'primary')) }} px-3 py-2 text-uppercase">
                                            {{ $pengajuan->status_pengajuan }}
                                        </span>
                                    </div>
                                </div>
                                <p class="text-muted mb-2">
                                    Skema Terpilih: <strong class="text-dark">{{ ucfirst($pengajuan->jenis_skema) }}</strong> &bull; Diajukan pada: {{ $pengajuan->created_at->translatedFormat('d F Y H:i') }}
                                </p>

                                @if($pengajuan->jenis_skema === 'prestasi' && $pengajuan->detailPrestasi)
                                    <div class="p-3 bg-light rounded-3 mb-2">
                                        <div class="fw-semibold text-dark">{{ $pengajuan->detailPrestasi->nama_lomba }}</div>
                                        <div class="small text-muted">Penyelenggara: {{ $pengajuan->detailPrestasi->penyelenggara }} (Tingkat {{ $pengajuan->detailPrestasi->tingkat }})</div>
                                    </div>
                                @elseif($pengajuan->detail)
                                    <div class="p-3 bg-light rounded-3 mb-2">
                                        <div class="fw-semibold text-dark">{{ $pengajuan->detail->judul_pa }}</div>
                                        <div class="small text-muted">Objek/Lokasi: {{ $pengajuan->detail->lokasi_penelitian }} &bull; Usulan Pembimbing: {{ $pengajuan->detail->dosen_pembimbing }}</div>
                                    </div>
                                @endif

                                @if($pengajuan->titleReview)
                                    <div class="p-3 bg-indigo-subtle border border-indigo-subtle rounded-3 mb-2 mt-2">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold text-primary small"><i class="bi bi-ui-checks me-1"></i>Hasil Rubrik Penilaian Judul</span>
                                            <span class="badge bg-primary fs-6">{{ $pengajuan->titleReview->total_score }} / 50</span>
                                        </div>
                                        <div class="row g-2 text-center small mb-1">
                                            <div class="col"><div class="p-2 bg-white rounded border"><div class="text-muted" style="font-size:0.75rem;">Permasalahan</div><strong>{{ $pengajuan->titleReview->problem_score }}/10</strong></div></div>
                                            <div class="col"><div class="p-2 bg-white rounded border"><div class="text-muted" style="font-size:0.75rem;">Solusi</div><strong>{{ $pengajuan->titleReview->solution_score }}/10</strong></div></div>
                                            <div class="col"><div class="p-2 bg-white rounded border"><div class="text-muted" style="font-size:0.75rem;">Kompleksitas</div><strong>{{ $pengajuan->titleReview->complexity_score }}/10</strong></div></div>
                                            <div class="col"><div class="p-2 bg-white rounded border"><div class="text-muted" style="font-size:0.75rem;">Metode</div><strong>{{ $pengajuan->titleReview->method_score }}/10</strong></div></div>
                                            <div class="col"><div class="p-2 bg-white rounded border"><div class="text-muted" style="font-size:0.75rem;">Pengujian</div><strong>{{ $pengajuan->titleReview->testing_score }}/10</strong></div></div>
                                        </div>
                                    </div>
                                @endif

                                @if($pengajuan->catatan_review)
                                    @if($pengajuan->status_pengajuan === 'revision')
                                        <div class="alert alert-warning mb-0 mt-3 py-2">
                                            <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Instruksi Revisi dari Koordinator:</strong> {{ $pengajuan->catatan_review }}
                                        </div>
                                    @elseif($pengajuan->status_pengajuan === 'rejected')
                                        <div class="alert alert-danger mb-0 mt-3 py-2">
                                            <strong><i class="bi bi-x-circle-fill me-1"></i> Alasan Penolakan:</strong> {{ $pengajuan->catatan_review }}
                                        </div>
                                    @else
                                        <div class="alert alert-secondary mb-0 mt-3 py-2">
                                            <strong><i class="bi bi-info-circle me-1"></i> Catatan Review Sebelumnya:</strong> {{ $pengajuan->catatan_review }}
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong class="d-flex align-items-center gap-2"><i class="bi bi-exclamation-triangle-fill"></i> Terdapat kesalahan pengisian:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form id="formPengajuanPa" method="POST" action="{{ route('student.pengajuan.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- CARD 1: DATA MAHASISWA (STATIS & READONLY) --}}
                <div class="card shadow-sm border-0 mb-4 rounded-3">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="card-title fw-bold mb-0 text-primary">
                            <i class="bi bi-person-badge me-2"></i>Data Mahasiswa
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-semibold">Nama Lengkap</label>
                                <input type="text" class="form-control bg-light" value="{{ $student?->nama ?? Auth::user()->name }}" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-semibold">NIM</label>
                                <input type="text" class="form-control bg-light" value="{{ $student?->nim ?? '-' }}" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-semibold">Kelas</label>
                                <input type="text" class="form-control bg-light" value="{{ $student?->kelas ?? '-' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">No. HP (WhatsApp) <span class="text-danger">*</span></label>
                                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone', $student?->phone) }}" placeholder="Contoh: 081234567890" required @disabled($lockedSubmission)>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="guardian_phone" class="form-label fw-semibold">No. HP Orang Tua / Kerabat <span class="text-danger">*</span></label>
                                <input type="text" name="guardian_phone" id="guardian_phone" class="form-control @error('guardian_phone') is-invalid @enderror" 
                                       value="{{ old('guardian_phone', $student?->guardian_phone) }}" placeholder="Contoh: 081298765432" required @disabled($lockedSubmission)>
                                @error('guardian_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD 2: PILIHAN SKEMA PROYEK AKHIR --}}
                <div class="card shadow-sm border-0 mb-4 rounded-3">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="card-title fw-bold mb-0 text-primary">
                            <i class="bi bi-diagram-3 me-2"></i>Pilihan Skema Proyek Akhir
                        </h5>
                    </div>
                    <div class="card-body">
                        <label for="select_skema" class="form-label fw-semibold">Pilih Skema PA <span class="text-danger">*</span></label>
                        <select name="jenis_skema" id="select_skema" class="form-select form-select-lg @error('jenis_skema') is-invalid @enderror" required @disabled($lockedSubmission)>
                            <option value="" disabled {{ old('jenis_skema', $pengajuan?->jenis_skema) ? '' : 'selected' }}>-- Silakan Pilih Skema Proyek Akhir --</option>
                            <option value="perancangan" {{ old('jenis_skema', $pengajuan?->jenis_skema) === 'perancangan' ? 'selected' : '' }}>Skema Perancangan Sistem</option>
                            <option value="implementasi" {{ old('jenis_skema', $pengajuan?->jenis_skema) === 'implementasi' ? 'selected' : '' }}>Skema Implementasi Sistem</option>
                            <option value="prestasi" {{ old('jenis_skema', $pengajuan?->jenis_skema) === 'prestasi' ? 'selected' : '' }}>Skema Prestasi (Kompetisi / Rekognisi)</option>
                        </select>
                        <div class="form-text mt-2">Form isian teknis akan menyesuaikan secara dinamis dengan skema yang dipilih.</div>
                        @error('jenis_skema')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- FORM DINAMIS A: SKEMA PERANCANGAN --}}
                <div id="form-perancangan" class="skema-container d-none">
                    <div class="card shadow-sm border-0 mb-4 rounded-3">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="card-title fw-bold mb-0 text-success">
                                <i class="bi bi-pencil-square me-2"></i>Isian Form Skema Perancangan
                            </h5>
                        </div>
                        <div class="card-body row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Judul Proyek Akhir <span class="text-danger">*</span></label>
                                <input type="text" name="perancangan_judul_pa" class="form-control" value="{{ old('perancangan_judul_pa', $pengajuan?->detailPerancangan?->judul_pa) }}" data-required="true" @disabled($lockedSubmission)>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Lokasi / Objek Penelitian <span class="text-danger">*</span></label>
                                <input type="text" name="perancangan_lokasi_penelitian" class="form-control" value="{{ old('perancangan_lokasi_penelitian', $pengajuan?->detailPerancangan?->lokasi_penelitian) }}" data-required="true" placeholder="Nama instansi/perusahaan & alamat lengkap" @disabled($lockedSubmission)>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Pendahuluan (Ringkasan) <span class="text-danger">*</span></label>
                                <textarea name="perancangan_pendahuluan" class="form-control" rows="5" placeholder="Jelaskan secara ringkas latar belakang masalah, rumusan masalah, tujuan, dan batasan masalah dari proyek akhir Anda..." data-required="true" @disabled($lockedSubmission)>{{ old('perancangan_pendahuluan', $pengajuan?->detailPerancangan?->pendahuluan) }}</textarea>
                                <div class="form-text">Gunakan poin-poin jika perlu. Rangkum bagian Latar Belakang, Rumusan, Tujuan, dan Batasan Masalah secara padat dan jelas (Maksimal ~500 kata).</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Usulan Dosen Pembimbing <span class="text-danger">*</span></label>
                                <input type="text" name="perancangan_dosen_pembimbing" class="form-control" value="{{ old('perancangan_dosen_pembimbing', $pengajuan?->detailPerancangan?->dosen_pembimbing) }}" data-required="true" @disabled($lockedSubmission)>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jenis Sistem <span class="text-danger">*</span></label>
                                <input type="text" name="perancangan_jenis_sistem" class="form-control" value="{{ old('perancangan_jenis_sistem', $pengajuan?->detailPerancangan?->jenis_sistem) }}" placeholder="Contoh: Web, Mobile, IoT" data-required="true" @disabled($lockedSubmission)>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Proses Bisnis Sistem <span class="text-danger">*</span></label>
                                <textarea name="perancangan_proses_bisnis" class="form-control" rows="3" data-required="true" @disabled($lockedSubmission)>{{ old('perancangan_proses_bisnis', $pengajuan?->detailPerancangan?->proses_bisnis) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jumlah Aktor dalam Sistem <span class="text-danger">*</span></label>
                                <input type="number" name="perancangan_jumlah_aktor" class="form-control" value="{{ old('perancangan_jumlah_aktor', $pengajuan?->detailPerancangan?->jumlah_aktor ?? 2) }}" min="1" data-required="true" @disabled($lockedSubmission)>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Metode Pengembangan Sistem <span class="text-danger">*</span></label>
                                <select name="perancangan_metode_pengembangan" class="form-select" data-required="true" @disabled($lockedSubmission)>
                                    <option value="">-- Pilih Metode --</option>
                                    @foreach(['Waterfall', 'Agile / Scrum', 'Prototype', 'RAD', 'Design Thinking'] as $method)
                                        <option value="{{ $method }}" {{ old('perancangan_metode_pengembangan', $pengajuan?->detailPerancangan?->metode_pengembangan) === $method ? 'selected' : '' }}>{{ $method }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Fitur Utama <span class="text-danger">*</span></label>
                                <textarea name="perancangan_fitur_utama" class="form-control" rows="3" placeholder="Sebutkan minimal 3 fitur utama" data-required="true" @disabled($lockedSubmission)>{{ old('perancangan_fitur_utama', $pengajuan?->detailPerancangan?->fitur_utama) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Metode / Pendekatan (Centang yang relevan) <span class="text-danger">*</span></label>
                                @php
                                    $pSelected = old('perancangan_metode_pendekatan', $pengajuan?->detailPerancangan?->metode_pendekatan ?? []);
                                @endphp
                                <div class="card bg-light p-3 border-0 rounded-3">
                                    @foreach(['UML / BPMN', 'Design Thinking', 'PIECES Framework', 'System Usability Scale (SUS)', 'Blackbox Testing'] as $idx => $pendekatan)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="perancangan_metode_pendekatan[]" value="{{ $pendekatan }}" id="p_pendekatan_{{ $idx }}"
                                                {{ in_array($pendekatan, $pSelected) ? 'checked' : '' }} @disabled($lockedSubmission)>
                                            <label class="form-check-label" for="p_pendekatan_{{ $idx }}">{{ $pendekatan }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tools Perancangan <span class="text-danger">*</span></label>
                                <input type="text" name="perancangan_tools_perancangan" class="form-control" value="{{ old('perancangan_tools_perancangan', $pengajuan?->detailPerancangan?->tools_perancangan) }}" placeholder="Contoh: Figma, Draw.io, Enterprise Architect" data-required="true" @disabled($lockedSubmission)>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FORM DINAMIS B: SKEMA IMPLEMENTASI --}}
                <div id="form-implementasi" class="skema-container d-none">
                    <div class="card shadow-sm border-0 mb-4 rounded-3">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="card-title fw-bold mb-0 text-info">
                                <i class="bi bi-code-slash me-2"></i>Isian Form Skema Implementasi
                            </h5>
                        </div>
                        <div class="card-body row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Judul Proyek Akhir <span class="text-danger">*</span></label>
                                <input type="text" name="implementasi_judul_pa" class="form-control" value="{{ old('implementasi_judul_pa', $pengajuan?->detailImplementasi?->judul_pa) }}" data-required="true" @disabled($lockedSubmission)>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Lokasi / Objek Penelitian <span class="text-danger">*</span></label>
                                <input type="text" name="implementasi_lokasi_penelitian" class="form-control" value="{{ old('implementasi_lokasi_penelitian', $pengajuan?->detailImplementasi?->lokasi_penelitian) }}" data-required="true" placeholder="Instansi/Perusahaan tempat implementasi" @disabled($lockedSubmission)>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Pendahuluan (Ringkasan) <span class="text-danger">*</span></label>
                                <textarea name="implementasi_pendahuluan" class="form-control" rows="5" placeholder="Jelaskan secara ringkas latar belakang masalah, rumusan masalah, tujuan, dan batasan masalah dari proyek akhir Anda..." data-required="true" @disabled($lockedSubmission)>{{ old('implementasi_pendahuluan', $pengajuan?->detailImplementasi?->pendahuluan) }}</textarea>
                                <div class="form-text">Gunakan poin-poin jika perlu. Rangkum bagian Latar Belakang, Rumusan, Tujuan, dan Batasan Masalah secara padat dan jelas (Maksimal ~500 kata).</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Usulan Dosen Pembimbing <span class="text-danger">*</span></label>
                                <input type="text" name="implementasi_dosen_pembimbing" class="form-control" value="{{ old('implementasi_dosen_pembimbing', $pengajuan?->detailImplementasi?->dosen_pembimbing) }}" data-required="true" @disabled($lockedSubmission)>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jenis Sistem <span class="text-danger">*</span></label>
                                <input type="text" name="implementasi_jenis_sistem" class="form-control" value="{{ old('implementasi_jenis_sistem', $pengajuan?->detailImplementasi?->jenis_sistem) }}" placeholder="Contoh: Web App, Mobile Flutter, REST API" data-required="true" @disabled($lockedSubmission)>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Proses Bisnis Sistem <span class="text-danger">*</span></label>
                                <textarea name="implementasi_proses_bisnis" class="form-control" rows="3" data-required="true" @disabled($lockedSubmission)>{{ old('implementasi_proses_bisnis', $pengajuan?->detailImplementasi?->proses_bisnis) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jumlah Aktor dalam Sistem <span class="text-danger">*</span></label>
                                <input type="number" name="implementasi_jumlah_aktor" class="form-control" value="{{ old('implementasi_jumlah_aktor', $pengajuan?->detailImplementasi?->jumlah_aktor ?? 2) }}" min="1" data-required="true" @disabled($lockedSubmission)>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Metode Pengembangan Sistem <span class="text-danger">*</span></label>
                                <select name="implementasi_metode_pengembangan" class="form-select" data-required="true" @disabled($lockedSubmission)>
                                    <option value="">-- Pilih Metode --</option>
                                    @foreach(['Waterfall', 'Agile / Scrum', 'Prototype', 'DevOps'] as $method)
                                        <option value="{{ $method }}" {{ old('implementasi_metode_pengembangan', $pengajuan?->detailImplementasi?->metode_pengembangan) === $method ? 'selected' : '' }}>{{ $method }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Fitur Utama <span class="text-danger">*</span></label>
                                <textarea name="implementasi_fitur_utama" class="form-control" rows="3" placeholder="Sebutkan fitur utama yang diimplementasikan" data-required="true" @disabled($lockedSubmission)>{{ old('implementasi_fitur_utama', $pengajuan?->detailImplementasi?->fitur_utama) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Metode / Pendekatan Khusus <span class="text-danger">*</span></label>
                                @php
                                    $iPendekatan = old('implementasi_metode_pendekatan', $pengajuan?->detailImplementasi?->metode_pendekatan ?? []);
                                @endphp
                                <div class="card bg-light p-3 border-0 rounded-3">
                                    @foreach(['SPK (SAW / TOPSIS / AHP)', 'Machine Learning / Algoritma', 'Rule-Based System', 'Microservices Architecture', 'IoT Integration'] as $idx => $pendekatan)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="implementasi_metode_pendekatan[]" value="{{ $pendekatan }}" id="i_pendekatan_{{ $idx }}"
                                                {{ in_array($pendekatan, $iPendekatan) ? 'checked' : '' }} @disabled($lockedSubmission)>
                                            <label class="form-check-label" for="i_pendekatan_{{ $idx }}">{{ $pendekatan }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Rencana Pengujian <span class="text-danger">*</span></label>
                                @php
                                    $iPengujian = old('implementasi_rencana_pengujian', $pengajuan?->detailImplementasi?->rencana_pengujian ?? []);
                                @endphp
                                <div class="card bg-light p-3 border-0 rounded-3">
                                    @foreach(['Blackbox Testing', 'Whitebox Testing', 'User Acceptance Testing (UAT)', 'Stress / Load Testing', 'Security Testing'] as $idx => $uji)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="implementasi_rencana_pengujian[]" value="{{ $uji }}" id="i_uji_{{ $idx }}"
                                                {{ in_array($uji, $iPengujian) ? 'checked' : '' }} @disabled($lockedSubmission)>
                                            <label class="form-check-label" for="i_uji_{{ $idx }}">{{ $uji }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Stack Teknologi yang Digunakan <span class="text-danger">*</span></label>
                                <input type="text" name="implementasi_teknologi" class="form-control" value="{{ old('implementasi_teknologi', $pengajuan?->detailImplementasi?->teknologi) }}" placeholder="Contoh: Laravel 12, Vue.js, PostgreSQL, Docker, TailwindCSS" data-required="true" @disabled($lockedSubmission)>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FORM DINAMIS C: SKEMA PRESTASI --}}
                <div id="form-prestasi" class="skema-container d-none">
                    <div class="card shadow-sm border-0 mb-4 rounded-3">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="card-title fw-bold mb-0 text-warning">
                                <i class="bi bi-trophy me-2"></i>Isian Form Skema Prestasi / Kompetisi
                            </h5>
                        </div>
                        <div class="card-body row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Nama Lomba / Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lomba" class="form-control" value="{{ old('nama_lomba', $pengajuan?->detailPrestasi?->nama_lomba) }}" placeholder="Contoh: GEMASTIK, PKM, Pagelaran Mahasiswa Nasional" data-required="true" @disabled($lockedSubmission)>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Penyelenggara <span class="text-danger">*</span></label>
                                <input type="text" name="penyelenggara" class="form-control" value="{{ old('penyelenggara', $pengajuan?->detailPrestasi?->penyelenggara) }}" placeholder="Contoh: Kemendikbudristek, PUSPRESNAS" data-required="true" @disabled($lockedSubmission)>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tingkat Kompetisi <span class="text-danger">*</span></label>
                                <select name="tingkat" class="form-select" data-required="true" @disabled($lockedSubmission)>
                                    <option value="">-- Pilih Tingkat --</option>
                                    @foreach(['Kota/Kabupaten', 'Provinsi', 'Regional', 'Nasional', 'Internasional'] as $lvl)
                                        <option value="{{ $lvl }}" {{ old('tingkat', $pengajuan?->detailPrestasi?->tingkat) === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_pelaksanaan" class="form-control" value="{{ old('tanggal_pelaksanaan', $pengajuan?->detailPrestasi?->tanggal_pelaksanaan?->format('Y-m-d')) }}" data-required="true" @disabled($lockedSubmission)>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Upload Form Asesmen (PDF) <span class="text-danger">*</span></label>
                                <input type="file" name="file_form_asesmen" class="form-control" accept=".pdf" data-required="true" @disabled($lockedSubmission)>
                                <div class="form-text">Maksimal 5MB (Format .pdf).</div>
                                @if($pengajuan?->detailPrestasi?->file_form_asesmen)
                                    <div class="mt-1 small"><a href="{{ asset('storage/' . $pengajuan->detailPrestasi->file_form_asesmen) }}" target="_blank" class="text-primary"><i class="bi bi-file-earmark-pdf"></i> Lihat file saat ini</a></div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Upload Sertifikat Penghargaan (PDF/Gambar) <span class="text-danger">*</span></label>
                                <input type="file" name="file_sertifikat" class="form-control" accept=".pdf,image/*" data-required="true" @disabled($lockedSubmission)>
                                <div class="form-text">Maksimal 5MB (Format .pdf, .jpg, .png).</div>
                                @if($pengajuan?->detailPrestasi?->file_sertifikat)
                                    <div class="mt-1 small"><a href="{{ asset('storage/' . $pengajuan->detailPrestasi->file_sertifikat) }}" target="_blank" class="text-primary"><i class="bi bi-file-earmark"></i> Lihat file saat ini</a></div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Upload Dokumen Presentasi / Proposal (PDF/PPTX) <span class="text-danger">*</span></label>
                                <input type="file" name="file_presentasi" class="form-control" accept=".pdf,.ppt,.pptx" data-required="true" @disabled($lockedSubmission)>
                                <div class="form-text">Maksimal 20MB (Format .pdf, .ppt, .pptx).</div>
                                @if($pengajuan?->detailPrestasi?->file_presentasi)
                                    <div class="mt-1 small"><a href="{{ asset('storage/' . $pengajuan->detailPrestasi->file_presentasi) }}" target="_blank" class="text-primary"><i class="bi bi-file-earmark-slides"></i> Lihat file saat ini</a></div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">URL Produk / Karya / Repository <span class="text-danger">*</span></label>
                                <input type="url" name="url_produk" class="form-control" value="{{ old('url_produk', $pengajuan?->detailPrestasi?->url_produk) }}" placeholder="https://github.com/... atau link demo produk" data-required="true" @disabled($lockedSubmission)>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TOMBOL SUBMIT --}}
                <div class="d-flex justify-content-end gap-2 mb-5">
                    <a href="{{ route('dashboard') }}" class="btn btn-light px-4 py-2">Batal</a>
                    @if($lockedSubmission)
                        <button type="button" class="btn btn-secondary px-4 py-2" disabled>
                            <i class="bi bi-lock-fill me-1"></i> Pengajuan Terkunci
                        </button>
                    @else
                        <button type="submit" id="btnSubmit" class="btn btn-primary px-4 py-2" disabled>
                            <i class="bi bi-send me-1"></i> Ajukan Judul Proyek Akhir
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JAVASCRIPT DOM MANIPULATION --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectSkema = document.getElementById('select_skema');
    const btnSubmit = document.getElementById('btnSubmit');
    const skemaContainers = document.querySelectorAll('.skema-container');
    const isLocked = {{ $lockedSubmission ? 'true' : 'false' }};

    function updateFormVisibility(selected) {
        // 1. Sembunyikan semua skema dan matikan inputnya
        skemaContainers.forEach(container => {
            container.classList.add('d-none');
            
            const formElements = container.querySelectorAll('input, select, textarea');
            formElements.forEach(el => {
                el.disabled = true;
                el.removeAttribute('required');
            });
        });

        // 2. Tampilkan form sesuai skema yang dipilih
        if (selected) {
            const activeForm = document.getElementById('form-' + selected);
            if (activeForm) {
                activeForm.classList.remove('d-none');

                const activeElements = activeForm.querySelectorAll('input, select, textarea');
                activeElements.forEach(el => {
                    // Jika form tidak terkunci, aktifkan inputnya
                    if (!isLocked) {
                        el.disabled = false;
                        if (el.dataset.required === 'true' && el.type !== 'checkbox') {
                            el.setAttribute('required', 'required');
                        }
                    }
                });

                if (btnSubmit && !isLocked) {
                    btnSubmit.disabled = false;
                }
            }
        } else {
            if (btnSubmit && !isLocked) {
                btnSubmit.disabled = true;
            }
        }
    }

    if (selectSkema) {
        selectSkema.addEventListener('change', function () {
            updateFormVisibility(this.value);
        });

        if (selectSkema.value) {
            updateFormVisibility(selectSkema.value);
        }
    }
});
</script>
@endsection
