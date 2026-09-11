@extends('layouts.app', ['heading' => 'Validasi Pengajuan Judul PA'])

@section('content')
{{-- CARD UTAMA: TABEL DAFTAR PENGAJUAN --}}
<div class="card shadow-sm border-0 rounded-3 mb-4">
    <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between">
        <h5 class="card-title fw-bold mb-0 text-primary">
            <i class="bi bi-file-earmark-text me-2"></i>Daftar Pengajuan Judul Proyek Akhir
        </h5>
        <span class="badge bg-light text-dark border px-3 py-2">
            Total: {{ $pengajuans->count() }} Pengajuan
        </span>
    </div>

    @if(session('success'))
        <div class="mx-3 mt-2 alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th style="width: 200px;">Mahasiswa & NIM</th>
                    <th style="width: 130px;">Skema</th>
                    <th>Judul / Nama Lomba</th>
                    <th style="width: 180px;">Dosen Pembimbing</th>
                    <th class="text-center" style="width: 120px;">Status</th>
                    <th class="text-center" style="width: 170px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuans as $index => $item)
                    <tr>
                        <td class="text-center fw-semibold text-muted">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->user?->student?->nama ?? $item->user?->name }}</div>
                            <div class="small text-muted">
                                NIM: {{ $item->user?->student?->nim ?? '-' }}
                                @if($item->user?->student?->kelas)
                                    &bull; Kelas: {{ $item->user->student->kelas }}
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($item->jenis_skema === 'perancangan')
                                <span class="badge bg-success px-2 py-1">
                                    <i class="bi bi-pencil-square me-1"></i>Perancangan
                                </span>
                            @elseif($item->jenis_skema === 'implementasi')
                                <span class="badge bg-info text-dark px-2 py-1">
                                    <i class="bi bi-code-slash me-1"></i>Implementasi
                                </span>
                            @elseif($item->jenis_skema === 'prestasi')
                                <span class="badge bg-warning text-dark px-2 py-1">
                                    <i class="bi bi-trophy me-1"></i>Prestasi
                                </span>
                            @else
                                <span class="badge bg-secondary px-2 py-1">{{ ucfirst($item->jenis_skema) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $item->judul_tampil }}</div>
                            @if($item->jenis_skema === 'prestasi' && $item->detailPrestasi)
                                <div class="small text-muted">
                                    <i class="bi bi-award me-1"></i>{{ $item->detailPrestasi->penyelenggara }} (Tingkat {{ $item->detailPrestasi->tingkat }})
                                </div>
                            @elseif($item->detail && isset($item->detail->lokasi_penelitian))
                                <div class="small text-muted">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $item->detail->lokasi_penelitian }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="text-secondary">{{ $item->pembimbing_tampil }}</span>
                        </td>
                        <td class="text-center">
                            @if($item->status_pengajuan === 'acc_seminar')
                                <span class="badge bg-success px-3 py-2"><i class="bi bi-award-fill me-1"></i>ACC Sempro</span>
                            @elseif($item->status_pengajuan === 'approved')
                                <span class="badge bg-success px-3 py-2">Disetujui</span>
                            @elseif($item->status_pengajuan === 'rejected')
                                <span class="badge bg-danger px-3 py-2">Ditolak</span>
                            @elseif($item->status_pengajuan === 'revision')
                                <span class="badge bg-warning text-dark px-3 py-2">Revisi</span>
                            @else
                                <span class="badge bg-primary px-3 py-2">Pending</span>
                            @endif

                            @if($item->titleReview)
                                <div class="small fw-semibold mt-1 text-primary">
                                    <i class="bi bi-star-fill text-warning me-1"></i>Skor: {{ $item->titleReview->total_score }}/50
                                </div>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                {{-- Tombol Detail --}}
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $item->id }}" title="Lihat Detail">
                                    <i class="bi bi-eye"></i> Detail
                                </button>
                                
                                {{-- Tombol Ubah Status / Nilai --}}
                                <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#modalStatus{{ $item->id }}" title="{{ in_array($item->jenis_skema, ['perancangan', 'implementasi']) ? 'Nilai & Status' : 'Ubah Status' }}">
                                    <i class="bi {{ in_array($item->jenis_skema, ['perancangan', 'implementasi']) ? 'bi-ui-checks' : 'bi-pencil' }}"></i> {{ in_array($item->jenis_skema, ['perancangan', 'implementasi']) ? 'Nilai' : 'Status' }}
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                            Belum ada pengajuan judul proyek akhir yang masuk.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODALS DITEMPATKAN DI LUAR CARD DAN TABEL AGAR STRUKTUR DOM TIDAK RUSAK --}}
@foreach($pengajuans as $item)
    {{-- 1. MODAL DETAIL PENGAJUAN --}}
    <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1" aria-labelledby="modalDetailLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-light border-bottom py-3">
                    <h5 class="modal-title fw-bold text-primary" id="modalDetailLabel{{ $item->id }}">
                        <i class="bi bi-file-earmark-person me-2"></i>Detail Pengajuan Judul
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-light border d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="text-muted small">Skema:</span>
                            <strong class="text-uppercase ms-1 text-primary">{{ $item->jenis_skema }}</strong>
                            &bull;
                            <span class="text-muted small ms-2">Tanggal Pengajuan:</span>
                            <strong>{{ $item->created_at->translatedFormat('d F Y H:i') }}</strong>
                        </div>
                        <a href="{{ route('student.pengajuan.export-pdf', $item->id) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Unduh PDF
                        </a>
                    </div>

                    @if($item->titleReview)
                        <div class="p-3 bg-indigo-subtle border border-indigo-subtle rounded-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-primary"><i class="bi bi-ui-checks me-1"></i>Hasil Penilaian Rubrik</span>
                                <span class="badge bg-primary fs-6">{{ $item->titleReview->total_score }} / 50</span>
                            </div>
                            <div class="row g-2 text-center small mb-2">
                                <div class="col"><div class="p-2 bg-white rounded border">Permasalahan<br><strong>{{ $item->titleReview->problem_score }}/10</strong></div></div>
                                <div class="col"><div class="p-2 bg-white rounded border">Solusi<br><strong>{{ $item->titleReview->solution_score }}/10</strong></div></div>
                                <div class="col"><div class="p-2 bg-white rounded border">Kompleksitas<br><strong>{{ $item->titleReview->complexity_score }}/10</strong></div></div>
                                <div class="col"><div class="p-2 bg-white rounded border">Metode<br><strong>{{ $item->titleReview->method_score }}/10</strong></div></div>
                                <div class="col"><div class="p-2 bg-white rounded border">Pengujian<br><strong>{{ $item->titleReview->testing_score }}/10</strong></div></div>
                            </div>
                            <div class="small text-slate-700">
                                Reviewer: <strong>{{ $item->titleReview->reviewer?->name ?? 'Admin' }}</strong> &bull; Keputusan: <strong class="text-uppercase">{{ $item->titleReview->decision }}</strong>
                            </div>
                        </div>
                    @endif

                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted">Nama Mahasiswa</dt>
                        <dd class="col-sm-8 fw-bold">{{ $item->user?->student?->nama ?? $item->user?->name }} ({{ $item->user?->student?->nim ?? '-' }})</dd>

                        <dt class="col-sm-4 text-muted">Kontak</dt>
                        <dd class="col-sm-8">
                            HP/WA: {{ $item->user?->student?->phone ?? '-' }} | Ortu: {{ $item->user?->student?->guardian_phone ?? '-' }}
                        </dd>

                        @if($item->jenis_skema === 'prestasi' && $item->detailPrestasi)
                            <dt class="col-sm-4 text-muted">Nama Lomba / Kegiatan</dt>
                            <dd class="col-sm-8 fw-bold text-dark">{{ $item->detailPrestasi->nama_lomba }}</dd>

                            <dt class="col-sm-4 text-muted">Penyelenggara</dt>
                            <dd class="col-sm-8">{{ $item->detailPrestasi->penyelenggara }} (Tingkat {{ $item->detailPrestasi->tingkat }})</dd>

                            <dt class="col-sm-4 text-muted">Tanggal Pelaksanaan</dt>
                            <dd class="col-sm-8">{{ $item->detailPrestasi->tanggal_pelaksanaan?->translatedFormat('d F Y') }}</dd>

                            <dt class="col-sm-4 text-muted">URL Karya / Produk</dt>
                            <dd class="col-sm-8"><a href="{{ $item->detailPrestasi->url_produk }}" target="_blank">{{ $item->detailPrestasi->url_produk }}</a></dd>

                            <dt class="col-sm-4 text-muted">Berkas Terlampir</dt>
                            <dd class="col-sm-8">
                                <ul class="list-unstyled mb-0">
                                    <li><a href="{{ asset('storage/' . $item->detailPrestasi->file_form_asesmen) }}" target="_blank" class="text-danger"><i class="bi bi-file-earmark-pdf me-1"></i> Form Asesmen</a></li>
                                    <li><a href="{{ asset('storage/' . $item->detailPrestasi->file_sertifikat) }}" target="_blank" class="text-warning"><i class="bi bi-award me-1"></i> Sertifikat</a></li>
                                    <li><a href="{{ asset('storage/' . $item->detailPrestasi->file_presentasi) }}" target="_blank" class="text-primary"><i class="bi bi-file-earmark-slides me-1"></i> Dokumen Presentasi</a></li>
                                </ul>
                            </dd>
                        @elseif($item->detail)
                            <dt class="col-sm-4 text-muted">Judul Proyek Akhir</dt>
                            <dd class="col-sm-8 fw-bold text-dark">{{ $item->detail->judul_pa }}</dd>

                            <dt class="col-sm-4 text-muted">Lokasi Penelitian</dt>
                            <dd class="col-sm-8">{{ $item->detail->lokasi_penelitian }}</dd>

                            <dt class="col-sm-4 text-muted">Usulan Pembimbing</dt>
                            <dd class="col-sm-8">{{ $item->detail->dosen_pembimbing }}</dd>

                            <dt class="col-sm-4 text-muted">Jenis Sistem</dt>
                            <dd class="col-sm-8">{{ $item->detail->jenis_sistem }} ({{ $item->detail->jumlah_aktor }} Aktor)</dd>

                            <dt class="col-sm-4 text-muted">Metode Pengembangan</dt>
                            <dd class="col-sm-8">{{ $item->detail->metode_pengembangan }}</dd>

                            @if($item->detail->metode_pendekatan)
                                <dt class="col-sm-4 text-muted">Metode / Pendekatan</dt>
                                <dd class="col-sm-8">
                                    @foreach($item->detail->metode_pendekatan as $m)
                                        <span class="badge bg-light text-dark border me-1">{{ $m }}</span>
                                    @endforeach
                                </dd>
                            @endif

                            @if(isset($item->detail->tools_perancangan))
                                <dt class="col-sm-4 text-muted">Tools Perancangan</dt>
                                <dd class="col-sm-8">{{ $item->detail->tools_perancangan }}</dd>
                            @endif

                            @if(isset($item->detail->teknologi))
                                <dt class="col-sm-4 text-muted">Stack Teknologi</dt>
                                <dd class="col-sm-8">{{ $item->detail->teknologi }}</dd>
                            @endif

                            @if(isset($item->detail->rencana_pengujian) && is_array($item->detail->rencana_pengujian))
                                <dt class="col-sm-4 text-muted">Rencana Pengujian</dt>
                                <dd class="col-sm-8">
                                    @foreach($item->detail->rencana_pengujian as $rp)
                                        <span class="badge bg-light text-dark border me-1">{{ $rp }}</span>
                                    @endforeach
                                </dd>
                            @endif

                            <dt class="col-sm-4 text-muted">Proses Bisnis</dt>
                            <dd class="col-sm-8 text-break">{{ $item->detail->proses_bisnis }}</dd>

                            <dt class="col-sm-4 text-muted">Fitur Utama</dt>
                            <dd class="col-sm-8 text-break">{{ $item->detail->fitur_utama }}</dd>

                            <dt class="col-sm-4 text-muted">Pendahuluan</dt>
                            <dd class="col-sm-8 text-break text-justify">{!! nl2br(e($item->detail->pendahuluan)) !!}</dd>
                        @endif

                        @if($item->catatan_review)
                            <dt class="col-sm-4 text-warning">Catatan Saat Ini</dt>
                            <dd class="col-sm-8 text-warning fw-semibold">{{ $item->catatan_review }}</dd>
                        @endif
                    </dl>
                </div>
                <div class="modal-footer bg-light border-top py-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="{{ route('admin.titles.show', $item->id) }}" class="btn btn-primary">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka Halaman Penuh
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. MODAL UBAH STATUS & PENILAIAN PENGAJUAN --}}
    <div class="modal fade" id="modalStatus{{ $item->id }}" tabindex="-1" aria-labelledby="modalStatusLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered {{ in_array($item->jenis_skema, ['perancangan', 'implementasi']) ? 'modal-lg' : '' }}">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <form method="POST" action="{{ route('admin.titles.update-status', $item->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-light border-bottom py-3">
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0" id="modalStatusLabel{{ $item->id }}">
                                <i class="bi {{ in_array($item->jenis_skema, ['perancangan', 'implementasi']) ? 'bi-ui-checks text-primary' : 'bi-check2-circle text-primary' }} me-2"></i>{{ in_array($item->jenis_skema, ['perancangan', 'implementasi']) ? 'Penilaian Rubrik Judul' : 'Ubah Status Pengajuan' }}
                            </h5>
                            @if(in_array($item->jenis_skema, ['perancangan', 'implementasi']))
                                <span class="badge bg-indigo-subtle text-primary border mt-1">Skema {{ ucfirst($item->jenis_skema) }} &bull; Rubrik 5 Kriteria</span>
                            @endif
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-light border mb-3 py-2 px-3">
                            <div class="small text-muted">Mahasiswa: <strong>{{ $item->user?->student?->nama ?? $item->user?->name }} ({{ $item->user?->student?->nim ?? '-' }})</strong></div>
                            <div class="small text-dark fw-semibold text-truncate">Judul: {{ $item->judul_tampil }}</div>
                        </div>

                        @if(in_array($item->jenis_skema, ['perancangan', 'implementasi']))
                            @php
                                $rev = $item->titleReview;
                                $p = old('problem_score', $rev?->problem_score ?? 8);
                                $s = old('solution_score', $rev?->solution_score ?? 8);
                                $c = old('complexity_score', $rev?->complexity_score ?? 8);
                                $m = old('method_score', $rev?->method_score ?? 8);
                                $t = old('testing_score', $rev?->testing_score ?? 8);
                                $tot = $p + $s + $c + $m + $t;
                            @endphp

                            <div class="p-3 bg-light rounded-3 border mb-3">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <span class="text-muted small d-block">Total Skor Akumulasi</span>
                                        <div class="h3 fw-bold text-primary mb-0" id="modal_tot_{{ $item->id }}">{{ $tot }} / 50</div>
                                    </div>
                                    <div class="col-sm-6 text-sm-end mt-2 mt-sm-0">
                                        <span class="text-muted small d-block mb-1">Rekomendasi Skor</span>
                                        <span id="modal_rec_{{ $item->id }}" class="badge {{ $tot >= 40 ? 'bg-success' : ($tot >= 30 ? 'bg-warning text-dark' : 'bg-danger') }} fs-6 px-2 py-1">
                                            {{ $tot >= 40 ? 'Disetujui (>=40)' : ($tot >= 30 ? 'Perlu Revisi (30-39)' : 'Ditolak (<30)') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <div class="p-2 border rounded bg-white">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="form-label fw-bold mb-0 small text-slate-800">1. Permasalahan & Urgensi</label>
                                            <span class="badge bg-light text-dark border font-mono" id="modal_v_prob_{{ $item->id }}">{{ $p }}/10</span>
                                        </div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Urgensi, latar belakang, dan batasan masalah.</div>
                                        <input type="range" class="form-range mt-1" min="0" max="10" step="1" name="problem_score" id="modal_prob_{{ $item->id }}" value="{{ $p }}" oninput="updateRubricModal({{ $item->id }})">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 border rounded bg-white">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="form-label fw-bold mb-0 small text-slate-800">2. Solusi Sistem</label>
                                            <span class="badge bg-light text-dark border font-mono" id="modal_v_sol_{{ $item->id }}">{{ $s }}/10</span>
                                        </div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Ketepatan & kelayakan sistem sebagai solusi.</div>
                                        <input type="range" class="form-range mt-1" min="0" max="10" step="1" name="solution_score" id="modal_sol_{{ $item->id }}" value="{{ $s }}" oninput="updateRubricModal({{ $item->id }})">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 border rounded bg-white">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="form-label fw-bold mb-0 small text-slate-800">3. Kompleksitas & Lingkup</label>
                                            <span class="badge bg-light text-dark border font-mono" id="modal_v_comp_{{ $item->id }}">{{ $c }}/10</span>
                                        </div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Kerumitan proses bisnis, fitur & jumlah aktor.</div>
                                        <input type="range" class="form-range mt-1" min="0" max="10" step="1" name="complexity_score" id="modal_comp_{{ $item->id }}" value="{{ $c }}" oninput="updateRubricModal({{ $item->id }})">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 border rounded bg-white">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="form-label fw-bold mb-0 small text-slate-800">4. Metode Pengembangan</label>
                                            <span class="badge bg-light text-dark border font-mono" id="modal_v_meth_{{ $item->id }}">{{ $m }}/10</span>
                                        </div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Kesesuaian SDLC, pendekatan & tools/teknologi.</div>
                                        <input type="range" class="form-range mt-1" min="0" max="10" step="1" name="method_score" id="modal_meth_{{ $item->id }}" value="{{ $m }}" oninput="updateRubricModal({{ $item->id }})">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-2 border rounded bg-white">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="form-label fw-bold mb-0 small text-slate-800">5. Rencana Pengujian</label>
                                            <span class="badge bg-light text-dark border font-mono" id="modal_v_test_{{ $item->id }}">{{ $t }}/10</span>
                                        </div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Cakupan pengujian (Blackbox, UAT, dll) dan indikator validasi.</div>
                                        <input type="range" class="form-range mt-1" min="0" max="10" step="1" name="testing_score" id="modal_test_{{ $item->id }}" value="{{ $t }}" oninput="updateRubricModal({{ $item->id }})">
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Keputusan Final <span class="text-danger">*</span></label>
                            <select name="status_pengajuan" id="modal_status_{{ $item->id }}" class="form-select form-select-lg fs-6 fw-semibold" required onchange="this.dataset.manual = 'true'">
                                <option value="pending" {{ $item->status_pengajuan === 'pending' ? 'selected' : '' }}>Pending (Menunggu Verifikasi)</option>
                                <option value="approved" {{ $item->status_pengajuan === 'approved' ? 'selected' : '' }}>Disetujui (Approved)</option>
                                <option value="revision" {{ $item->status_pengajuan === 'revision' ? 'selected' : '' }}>Perlu Revisi (Revision)</option>
                                <option value="rejected" {{ $item->status_pengajuan === 'rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Catatan Review / Instruksi Perbaikan</label>
                            <textarea name="catatan_review" class="form-control" rows="3" placeholder="Tuliskan alasan keputusan atau instruksi perbaikan untuk mahasiswa...">{{ old('catatan_review', $item->catatan_review ?? $item->titleReview?->comment) }}</textarea>
                            <div class="form-text">Catatan ini dapat langsung dibaca oleh mahasiswa di dashboard akun mereka.</div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top py-3">
                        <button type="button" class="btn btn-outline-secondary px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Simpan Keputusan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<script>
function updateRubricModal(id) {
    const p = parseInt(document.getElementById('modal_prob_' + id)?.value || 0);
    const s = parseInt(document.getElementById('modal_sol_' + id)?.value || 0);
    const c = parseInt(document.getElementById('modal_comp_' + id)?.value || 0);
    const m = parseInt(document.getElementById('modal_meth_' + id)?.value || 0);
    const t = parseInt(document.getElementById('modal_test_' + id)?.value || 0);

    const vp = document.getElementById('modal_v_prob_' + id);
    if (vp) vp.innerText = p + '/10';
    const vs = document.getElementById('modal_v_sol_' + id);
    if (vs) vs.innerText = s + '/10';
    const vc = document.getElementById('modal_v_comp_' + id);
    if (vc) vc.innerText = c + '/10';
    const vm = document.getElementById('modal_v_meth_' + id);
    if (vm) vm.innerText = m + '/10';
    const vt = document.getElementById('modal_v_test_' + id);
    if (vt) vt.innerText = t + '/10';

    const total = p + s + c + m + t;
    const totEl = document.getElementById('modal_tot_' + id);
    if (totEl) totEl.innerText = total + ' / 50';

    const badge = document.getElementById('modal_rec_' + id);
    const select = document.getElementById('modal_status_' + id);

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

    if (badge) {
        badge.className = 'badge ' + badgeClass + ' fs-6 px-2 py-1';
        badge.innerText = label;
    }

    if (select && !select.dataset.manual) {
        select.value = decision;
    }
}
</script>
@endsection
