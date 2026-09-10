@extends('layouts.app', ['heading' => 'Detail & Review Pengajuan Judul'])

@section('content')
<div class="row g-3">
    <div class="col-lg-7">
        <div class="card shadow-sm border-0 rounded-3 mb-3">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="card-title fw-bold mb-0 text-primary">
                    <i class="bi bi-file-earmark-text me-2"></i>{{ $pengajuan->judul_tampil }}
                </h5>
                <a href="{{ route('student.pengajuan.export-pdf', $pengajuan->id) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Unduh PDF
                </a>
            </div>
            <div class="card-body">
                <div class="alert alert-light border mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold fs-6">{{ $pengajuan->user?->student?->nama ?? $pengajuan->user?->name }}</div>
                            <div class="text-muted small">
                                NIM: {{ $pengajuan->user?->student?->nim ?? '-' }} | Kelas: {{ $pengajuan->user?->student?->kelas ?? '-' }}
                            </div>
                        </div>
                        <span class="badge bg-{{ $pengajuan->status_pengajuan === 'approved' ? 'success' : ($pengajuan->status_pengajuan === 'rejected' ? 'danger' : ($pengajuan->status_pengajuan === 'revision' ? 'warning text-dark' : 'primary')) }} px-3 py-2 text-uppercase">
                            {{ $pengajuan->status_pengajuan }}
                        </span>
                    </div>
                    <hr class="my-2">
                    <div class="small text-muted">
                        No. HP (WA): <strong>{{ $pengajuan->user?->student?->phone ?? '-' }}</strong> &bull;
                        WA Ortu: <strong>{{ $pengajuan->user?->student?->guardian_phone ?? '-' }}</strong> &bull;
                        Diajukan pada: <strong>{{ $pengajuan->created_at->translatedFormat('d F Y H:i') }}</strong>
                    </div>
                </div>

                @if($pengajuan->titleReview)
                    <div class="p-3 bg-indigo-subtle border border-indigo-subtle rounded-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-primary"><i class="bi bi-ui-checks me-1"></i>Hasil Penilaian Rubrik Tersimpan</span>
                            <span class="badge bg-primary fs-6">{{ $pengajuan->titleReview->total_score }} / 50</span>
                        </div>
                        <div class="row g-2 text-center small mb-2">
                            <div class="col"><div class="p-2 bg-white rounded border">Permasalahan<br><strong>{{ $pengajuan->titleReview->problem_score }}/10</strong></div></div>
                            <div class="col"><div class="p-2 bg-white rounded border">Solusi<br><strong>{{ $pengajuan->titleReview->solution_score }}/10</strong></div></div>
                            <div class="col"><div class="p-2 bg-white rounded border">Kompleksitas<br><strong>{{ $pengajuan->titleReview->complexity_score }}/10</strong></div></div>
                            <div class="col"><div class="p-2 bg-white rounded border">Metode<br><strong>{{ $pengajuan->titleReview->method_score }}/10</strong></div></div>
                            <div class="col"><div class="p-2 bg-white rounded border">Pengujian<br><strong>{{ $pengajuan->titleReview->testing_score }}/10</strong></div></div>
                        </div>
                        <div class="small text-slate-700">
                            Reviewer: <strong>{{ $pengajuan->titleReview->reviewer?->name ?? 'Admin' }}</strong> &bull; Keputusan: <strong class="text-uppercase">{{ $pengajuan->titleReview->decision }}</strong>
                        </div>
                    </div>
                @endif

                <dl class="row mb-0">
                    <dt class="col-sm-4">Skema Proyek Akhir</dt>
                    <dd class="col-sm-8 text-uppercase fw-bold text-primary">{{ $pengajuan->jenis_skema }}</dd>

                    @if($pengajuan->jenis_skema === 'prestasi' && $pengajuan->detailPrestasi)
                        <dt class="col-sm-4">Nama Lomba/Kegiatan</dt>
                        <dd class="col-sm-8">{{ $pengajuan->detailPrestasi->nama_lomba }}</dd>

                        <dt class="col-sm-4">Penyelenggara</dt>
                        <dd class="col-sm-8">{{ $pengajuan->detailPrestasi->penyelenggara }}</dd>

                        <dt class="col-sm-4">Tingkat Kompetisi</dt>
                        <dd class="col-sm-8">{{ $pengajuan->detailPrestasi->tingkat }}</dd>

                        <dt class="col-sm-4">Tanggal Pelaksanaan</dt>
                        <dd class="col-sm-8">{{ $pengajuan->detailPrestasi->tanggal_pelaksanaan?->translatedFormat('d F Y') }}</dd>

                        <dt class="col-sm-4">URL Karya/Produk</dt>
                        <dd class="col-sm-8"><a href="{{ $pengajuan->detailPrestasi->url_produk }}" target="_blank">{{ $pengajuan->detailPrestasi->url_produk }}</a></dd>

                        <dt class="col-sm-4">Berkas Lampiran</dt>
                        <dd class="col-sm-8">
                            <ul class="list-unstyled mb-0">
                                <li><a href="{{ asset('storage/' . $pengajuan->detailPrestasi->file_form_asesmen) }}" target="_blank"><i class="bi bi-file-earmark-pdf text-danger me-1"></i> Form Asesmen</a></li>
                                <li><a href="{{ asset('storage/' . $pengajuan->detailPrestasi->file_sertifikat) }}" target="_blank"><i class="bi bi-award text-warning me-1"></i> Sertifikat</a></li>
                                <li><a href="{{ asset('storage/' . $pengajuan->detailPrestasi->file_presentasi) }}" target="_blank"><i class="bi bi-file-earmark-slides text-primary me-1"></i> Dokumen Presentasi</a></li>
                            </ul>
                        </dd>
                    @elseif($pengajuan->detail)
                        <dt class="col-sm-4">Judul Proyek Akhir</dt>
                        <dd class="col-sm-8 fw-bold">{{ $pengajuan->detail->judul_pa }}</dd>

                        <dt class="col-sm-4">Lokasi/Objek Penelitian</dt>
                        <dd class="col-sm-8">{{ $pengajuan->detail->lokasi_penelitian }}</dd>

                        <dt class="col-sm-4">Usulan Pembimbing</dt>
                        <dd class="col-sm-8">{{ $pengajuan->detail->dosen_pembimbing }}</dd>

                        <dt class="col-sm-4">Jenis Sistem</dt>
                        <dd class="col-sm-8">{{ $pengajuan->detail->jenis_sistem }} ({{ $pengajuan->detail->jumlah_aktor }} Aktor)</dd>

                        <dt class="col-sm-4">Metode Pengembangan</dt>
                        <dd class="col-sm-8">{{ $pengajuan->detail->metode_pengembangan }}</dd>

                        @if($pengajuan->detail->metode_pendekatan)
                            <dt class="col-sm-4">Metode / Pendekatan</dt>
                            <dd class="col-sm-8">
                                @foreach($pengajuan->detail->metode_pendekatan as $m)
                                    <span class="badge bg-light text-dark border me-1">{{ $m }}</span>
                                @endforeach
                            </dd>
                        @endif

                        @if(isset($pengajuan->detail->tools_perancangan))
                            <dt class="col-sm-4">Tools Perancangan</dt>
                            <dd class="col-sm-8">{{ $pengajuan->detail->tools_perancangan }}</dd>
                        @endif

                        @if(isset($pengajuan->detail->teknologi))
                            <dt class="col-sm-4">Stack Teknologi</dt>
                            <dd class="col-sm-8">{{ $pengajuan->detail->teknologi }}</dd>
                        @endif

                        @if(isset($pengajuan->detail->rencana_pengujian) && is_array($pengajuan->detail->rencana_pengujian))
                            <dt class="col-sm-4">Rencana Pengujian</dt>
                            <dd class="col-sm-8">
                                @foreach($pengajuan->detail->rencana_pengujian as $rp)
                                    <span class="badge bg-light text-dark border me-1">{{ $rp }}</span>
                                @endforeach
                            </dd>
                        @endif

                        <dt class="col-sm-4">Proses Bisnis</dt>
                        <dd class="col-sm-8 text-break">{{ $pengajuan->detail->proses_bisnis }}</dd>

                        <dt class="col-sm-4">Fitur Utama</dt>
                        <dd class="col-sm-8 text-break">{{ $pengajuan->detail->fitur_utama }}</dd>

                        <dt class="col-sm-4">Pendahuluan</dt>
                        <dd class="col-sm-8 text-break text-justify">{!! nl2br(e($pengajuan->detail->pendahuluan)) !!}</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        @if(in_array($pengajuan->jenis_skema, ['perancangan', 'implementasi']))
            {{-- Form Rubrik Penilaian untuk Perancangan dan Implementasi --}}
            <form method="POST" action="{{ route('admin.titles.update-status', $pengajuan->id) }}" class="card shadow-sm border-0 rounded-3">
                @csrf
                @method('PUT')
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title fw-bold mb-0 text-dark">
                            <i class="bi bi-ui-checks text-primary me-2"></i>Form Penilaian Judul
                        </h5>
                        <div class="text-muted small">Skema {{ ucfirst($pengajuan->jenis_skema) }} &bull; Rubrik 5 Kriteria</div>
                    </div>
                    <span class="badge bg-indigo-subtle text-primary border">Maks 50 Poin</span>
                </div>
                <div class="card-body p-3">
                    @php
                        $review = $pengajuan->titleReview;
                        $pScore = old('problem_score', $review?->problem_score ?? 8);
                        $sScore = old('solution_score', $review?->solution_score ?? 8);
                        $cScore = old('complexity_score', $review?->complexity_score ?? 8);
                        $mScore = old('method_score', $review?->method_score ?? 8);
                        $tScore = old('testing_score', $review?->testing_score ?? 8);
                        $initTotal = $pScore + $sScore + $cScore + $mScore + $tScore;
                    @endphp

                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <div class="row g-2 align-items-center">
                            <div class="col-6">
                                <span class="text-muted small d-block">Total Skor Akumulasi</span>
                                <div class="h3 fw-bold text-primary mb-0" id="show_total_display">{{ $initTotal }} / 50</div>
                            </div>
                            <div class="col-6 text-end">
                                <span class="text-muted small d-block mb-1">Rekomendasi Skor</span>
                                <span id="show_recommendation_badge" class="badge {{ $initTotal >= 40 ? 'bg-success' : ($initTotal >= 30 ? 'bg-warning text-dark' : 'bg-danger') }} fs-6 px-2 py-1">
                                    {{ $initTotal >= 40 ? 'Disetujui (>=40)' : ($initTotal >= 30 ? 'Perlu Revisi (30-39)' : 'Ditolak (<30)') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- 1. Permasalahan --}}
                    <div class="mb-3 pb-2 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold mb-0 text-slate-800 small">
                                1. Permasalahan & Urgensi
                            </label>
                            <span class="badge bg-light text-dark border font-mono" id="val_show_problem">{{ $pScore }}/10</span>
                        </div>
                        <div class="text-muted small mb-2" style="font-size: 0.8rem;">Kejelasan latar belakang, urgensi, rumusan masalah, dan batasan masalah.</div>
                        <input type="range" class="form-range" min="0" max="10" step="1" name="problem_score" id="show_problem" value="{{ $pScore }}" oninput="updateShowRubric()">
                    </div>

                    {{-- 2. Solusi --}}
                    <div class="mb-3 pb-2 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold mb-0 text-slate-800 small">
                                2. Solusi Sistem yang Ditawarkan
                            </label>
                            <span class="badge bg-light text-dark border font-mono" id="val_show_solution">{{ $sScore }}/10</span>
                        </div>
                        <div class="text-muted small mb-2" style="font-size: 0.8rem;">Ketepatan dan kelayakan sistem sebagai solusi masalah yang dihadapi.</div>
                        <input type="range" class="form-range" min="0" max="10" step="1" name="solution_score" id="show_solution" value="{{ $sScore }}" oninput="updateShowRubric()">
                    </div>

                    {{-- 3. Kompleksitas --}}
                    <div class="mb-3 pb-2 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold mb-0 text-slate-800 small">
                                3. Kompleksitas & Ruang Lingkup
                            </label>
                            <span class="badge bg-light text-dark border font-mono" id="val_show_complexity">{{ $cScore }}/10</span>
                        </div>
                        <div class="text-muted small mb-2" style="font-size: 0.8rem;">Tingkat kerumitan fungsionalitas, alur proses bisnis, dan aktor pengguna.</div>
                        <input type="range" class="form-range" min="0" max="10" step="1" name="complexity_score" id="show_complexity" value="{{ $cScore }}" oninput="updateShowRubric()">
                    </div>

                    {{-- 4. Metode --}}
                    <div class="mb-3 pb-2 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold mb-0 text-slate-800 small">
                                4. Metode & Pendekatan Pengembangan
                            </label>
                            <span class="badge bg-light text-dark border font-mono" id="val_show_method">{{ $mScore }}/10</span>
                        </div>
                        <div class="text-muted small mb-2" style="font-size: 0.8rem;">Kesesuaian metode SDLC, pendekatan analisis, dan stack teknologi/tools.</div>
                        <input type="range" class="form-range" min="0" max="10" step="1" name="method_score" id="show_method" value="{{ $mScore }}" oninput="updateShowRubric()">
                    </div>

                    {{-- 5. Pengujian --}}
                    <div class="mb-3 pb-2 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold mb-0 text-slate-800 small">
                                5. Rencana Pengujian Sistem
                            </label>
                            <span class="badge bg-light text-dark border font-mono" id="val_show_testing">{{ $tScore }}/10</span>
                        </div>
                        <div class="text-muted small mb-2" style="font-size: 0.8rem;">Cakupan rencana pengujian sistem (Blackbox, UAT, Whitebox) dan metrik validasi.</div>
                        <input type="range" class="form-range" min="0" max="10" step="1" name="testing_score" id="show_testing" value="{{ $tScore }}" oninput="updateShowRubric()">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Keputusan Admin <span class="text-danger">*</span></label>
                        <select name="status_pengajuan" id="show_status" class="form-select fw-semibold" required onchange="this.dataset.manual = 'true'">
                            <option value="pending" {{ $pengajuan->status_pengajuan === 'pending' ? 'selected' : '' }}>Pending (Menunggu Verifikasi)</option>
                            <option value="approved" {{ $pengajuan->status_pengajuan === 'approved' ? 'selected' : '' }}>Disetujui (Approved)</option>
                            <option value="revision" {{ $pengajuan->status_pengajuan === 'revision' ? 'selected' : '' }}>Perlu Revisi (Revision)</option>
                            <option value="rejected" {{ $pengajuan->status_pengajuan === 'rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan Review / Instruksi Perbaikan</label>
                        <textarea name="catatan_review" class="form-control" rows="4" placeholder="Tuliskan catatan review, arahan revisi, atau alasan keputusan...">{{ old('catatan_review', $pengajuan->catatan_review ?? $review?->comment) }}</textarea>
                        <div class="form-text">Catatan ini akan tampil di dashboard akun mahasiswa.</div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary shadow-sm py-2 fw-semibold">
                            <i class="bi bi-save me-1"></i> Simpan Penilaian & Keputusan
                        </button>
                        <a class="btn btn-light border" href="{{ route('admin.titles.index') }}">
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </form>
        @else
            {{-- Form Keputusan Langsung untuk Skema Prestasi --}}
            <form method="POST" action="{{ route('admin.titles.update-status', $pengajuan->id) }}" class="card shadow-sm border-0 rounded-3">
                @csrf
                @method('PUT')
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title fw-bold mb-0 text-dark">
                        <i class="bi bi-award text-warning me-2"></i>Verifikasi Skema Prestasi
                    </h5>
                    <div class="text-muted small">Validasi bukti piagam, kejuaraan, dan kelayakan konversi PA</div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Pengajuan <span class="text-danger">*</span></label>
                        <select name="status_pengajuan" class="form-select fw-semibold" required>
                            <option value="pending" {{ $pengajuan->status_pengajuan === 'pending' ? 'selected' : '' }}>Pending (Menunggu Verifikasi)</option>
                            <option value="approved" {{ $pengajuan->status_pengajuan === 'approved' ? 'selected' : '' }}>Disetujui (Approved)</option>
                            <option value="revision" {{ $pengajuan->status_pengajuan === 'revision' ? 'selected' : '' }}>Perlu Revisi (Revision)</option>
                            <option value="rejected" {{ $pengajuan->status_pengajuan === 'rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan Review / Verifikasi</label>
                        <textarea name="catatan_review" class="form-control" rows="5" placeholder="Tuliskan catatan verifikasi sertifikat, kelengkapan berkas, atau alasan keputusan...">{{ old('catatan_review', $pengajuan->catatan_review) }}</textarea>
                        <div class="form-text">Catatan ini akan tampil di dashboard akun mahasiswa.</div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary shadow-sm py-2 fw-semibold">
                            <i class="bi bi-save me-1"></i> Simpan Keputusan
                        </button>
                        <a class="btn btn-light border" href="{{ route('admin.titles.index') }}">
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div>

<script>
function updateShowRubric() {
    const p = parseInt(document.getElementById('show_problem')?.value || 0);
    const s = parseInt(document.getElementById('show_solution')?.value || 0);
    const c = parseInt(document.getElementById('show_complexity')?.value || 0);
    const m = parseInt(document.getElementById('show_method')?.value || 0);
    const t = parseInt(document.getElementById('show_testing')?.value || 0);

    document.getElementById('val_show_problem').innerText = p + '/10';
    document.getElementById('val_show_solution').innerText = s + '/10';
    document.getElementById('val_show_complexity').innerText = c + '/10';
    document.getElementById('val_show_method').innerText = m + '/10';
    document.getElementById('val_show_testing').innerText = t + '/10';

    const total = p + s + c + m + t;
    const totalEl = document.getElementById('show_total_display');
    if (totalEl) totalEl.innerText = total + ' / 50';

    const badgeEl = document.getElementById('show_recommendation_badge');
    const selectEl = document.getElementById('show_status');

    let decision = 'rejected';
    let label = 'Ditolak (<30)';
    let badgeClass = 'bg-danger';

    if (total >= 40) {
        decision = 'approved';
        label = 'Disetujui (>=40)';
        badgeClass = 'bg-success';
    } else if (total >= 30) {
        decision = 'revision';
        label = 'Perlu Revisi (30-39)';
        badgeClass = 'bg-warning text-dark';
    }

    if (badgeEl) {
        badgeEl.className = 'badge ' + badgeClass + ' fs-6 px-2 py-1';
        badgeEl.innerText = label;
    }

    if (selectEl && !selectEl.dataset.manual) {
        selectEl.value = decision;
    }
}
</script>
@endsection

