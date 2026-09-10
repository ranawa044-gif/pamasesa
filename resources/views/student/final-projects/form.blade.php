@extends('layouts.app', ['heading' => 'Pengajuan Judul Proyek Akhir'])

@section('content')
@if($lockedSubmission)
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="d-flex align-items-start gap-3">
                <div class="brand-icon flex-shrink-0"><i class="bi {{ $finalProject->status === 'APPROVED' ? 'bi-check-circle' : 'bi-hourglass-split' }}"></i></div>
                <div>
                    <h3 class="h5 fw-bold mb-1">
                        {{ $finalProject->status === 'APPROVED' ? 'Judul sudah ACC' : 'Pengajuan sedang diproses' }}
                    </h3>
                    <p class="text-muted mb-3">
                        @if($finalProject->status === 'APPROVED')
                            Judul sudah disetujui admin sehingga tidak bisa diajukan ulang.
                        @else
                            Judul sudah dikirim dan masih menunggu keputusan admin. Pengajuan baru bisa diperbarui jika admin meminta revisi atau menolak judul.
                        @endif
                    </p>
                    <div class="mb-2"><x-status-badge :status="$finalProject->status" /></div>
                    <h4 class="h6 mb-0">{{ $finalProject->title }}</h4>
                </div>
            </div>
        </div>
    </div>
@endif

@if($finalProject?->titleReview)
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h5 class="mb-1">Hasil Review Validasi Judul</h5>
                    <div class="text-muted">Skor total dan rekomendasi keputusan.</div>
                </div>
                <span class="badge bg-{{ $finalProject->titleReview->decision === 'APPROVED' ? 'success' : ($finalProject->titleReview->decision === 'REVISION' ? 'warning' : 'danger') }}">
                    {{ $finalProject->titleReview->decision }}
                </span>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="border rounded p-3 text-center">
                        <div class="text-muted small">Total Skor</div>
                        <div class="fs-3 fw-semibold">{{ $finalProject->titleReview->total_score }}/50</div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="text-muted small">Permasalahan</div>
                                <div class="fs-5 fw-semibold">{{ $finalProject->titleReview->problem_score }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="text-muted small">Solusi</div>
                                <div class="fs-5 fw-semibold">{{ $finalProject->titleReview->solution_score }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="text-muted small">Kompleksitas</div>
                                <div class="fs-5 fw-semibold">{{ $finalProject->titleReview->complexity_score }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="text-muted small">Metode</div>
                                <div class="fs-5 fw-semibold">{{ $finalProject->titleReview->method_score }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="border rounded p-3">
                                <div class="text-muted small">Pengujian</div>
                                <div class="fs-5 fw-semibold">{{ $finalProject->titleReview->testing_score }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($finalProject->titleReview->comment)
                <div class="mt-3">
                    <div class="fw-semibold">Catatan Reviewer</div>
                    <p class="mb-0">{{ $finalProject->titleReview->comment }}</p>
                </div>
            @endif
        </div>
    </div>
@endif

<form id="finalProjectForm" method="post" action="{{ route('student.final-project.store') }}" class="card shadow-sm border-0">
    @csrf
    <div class="card-body row g-3">
        @if($finalProject?->status === 'REVISION')
            <div class="col-12">
                <div class="alert alert-warning"><strong>Catatan revisi:</strong> {{ $finalProject->review_note }}</div>
            </div>
        @endif
        <div class="col-12">
            <div class="alert alert-info mb-0">
                Form ini digunakan untuk seleksi awal judul Tugas Akhir. Pengajuan harus menyertakan masalah nyata, rencana implementasi sistem, dan fitur utama.
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label">Nama Lengkap</label>
            <input class="form-control" value="{{ $student?->nama }}" disabled>
        </div>
        <div class="col-md-3">
            <label class="form-label">NIM</label>
            <input class="form-control" value="{{ $student?->nim }}" disabled>
        </div>
        <div class="col-md-3">
            <label class="form-label">Kelas</label>
            <input class="form-control" value="{{ $student?->kelas }}" disabled>
        </div>
        <div class="col-md-6">
            <label class="form-label">No. HP (Whatsapp)</label>
            <input name="phone" class="form-control" value="{{ old('phone', $student?->phone) }}" required @disabled($lockedSubmission)>
        </div>
        <div class="col-md-6">
            <label class="form-label">No. HP (Whatsapp) Orang Tua/Kerabat</label>
            <input name="guardian_phone" class="form-control" value="{{ old('guardian_phone', $student?->guardian_phone) }}" required @disabled($lockedSubmission)>
        </div>
        <div class="col-12"><h3 class="h5 border-bottom pb-2 mt-2">Judul, Objek & Masalah</h3></div>
        <div class="col-12">
            <label class="form-label">Judul Tugas Akhir</label>
            <input name="title" class="form-control" value="{{ old('title', $finalProject?->title) }}" required @disabled($lockedSubmission)>
        </div>
        <div class="col-12">
            <label class="form-label">Objek / Lokasi Penelitian</label>
            <input name="research_object" class="form-control" value="{{ old('research_object', $finalProject?->research_object) }}" required @disabled($lockedSubmission)>
            <div class="form-text">Sertakan alamat lengkap.</div>
        </div>
        <div class="col-12">
            <label class="form-label">Latar Belakang Masalah</label>
            <textarea name="background" class="form-control" rows="5" required @disabled($lockedSubmission)>{{ old('background', $finalProject?->background) }}</textarea>
            <div class="form-text">Minimal 3-5 kalimat dan harus menjelaskan masalah nyata, bukan deskripsi umum.</div>
        </div>
        <div class="col-12"><h3 class="h5 border-bottom pb-2 mt-2">Usulan Dosen Pembimbing</h3></div>
        <div class="col-12">
            <label class="form-label">Nama Dosen Pembimbing</label>
            <input name="proposed_supervisor_name" class="form-control" value="{{ old('proposed_supervisor_name', $finalProject?->proposed_supervisor_name) }}" required @disabled($lockedSubmission)>
            <div class="form-text">Mahasiswa hanya mengusulkan. Penetapan akhir tetap ditentukan koordinator.</div>
        </div>
        <div class="col-12"><h3 class="h5 border-bottom pb-2 mt-2">Rencana Sistem</h3></div>
        <div class="col-12">
            <label class="form-label">Proses Bisnis yang Akan Disistemkan</label>
            <textarea name="business_process" class="form-control" rows="4" required @disabled($lockedSubmission)>{{ old('business_process', $finalProject?->business_process) }}</textarea>
            <div class="form-text">Jelaskan alur kegiatan yang ingin diperbaiki atau didigitalisasi.</div>
        </div>
        <div class="col-md-6">
            <label class="form-label">Jenis Sistem yang Akan Dibuat</label>
            <input name="system_type" class="form-control" value="{{ old('system_type', $finalProject?->system_type) }}" placeholder="Web, Mobile, IoT, lainnya" required @disabled($lockedSubmission)>
        </div>
        <div class="col-md-6">
            <label class="form-label">Jumlah Aktor dalam Sistem</label>
            <input type="number" name="actor_count" class="form-control" value="{{ old('actor_count', $finalProject?->actor_count ?? 2) }}" min="2" required @disabled($lockedSubmission)>
            <div class="form-text">Minimal 2 aktor.</div>
        </div>
        <div class="col-12">
            <label class="form-label">Fitur Utama Sistem</label>
            <textarea name="main_features" class="form-control" rows="4" required @disabled($lockedSubmission)>{{ old('main_features', $finalProject?->main_features) }}</textarea>
            <div class="form-text">Tulis minimal 3 fitur utama.</div>
        </div>
        <div class="col-12"><h3 class="h5 border-bottom pb-2 mt-2">Metode</h3></div>
        <div class="col-md-6">
            <label class="form-label">Metode Pengembangan</label>
            <select name="development_method" class="form-select" required @disabled($lockedSubmission)>
                @foreach(['Waterfall', 'Agile Development', 'Prototype', 'Lainnya'] as $method)
                    <option value="{{ $method }}" @selected(old('development_method', $finalProject?->development_method) === $method)>{{ $method }}</option>
                @endforeach
            </select>
            <input name="development_method_other" class="form-control mt-2" value="{{ old('development_method_other') }}" placeholder="Isi jika memilih lainnya" @disabled($lockedSubmission)>
        </div>
        <div class="col-md-6">
            <label class="form-label">Metode / Pendekatan dalam Sistem</label>
            @php
                $selectedMethods = old('additional_method', $finalProject?->additional_method ? array_map('trim', explode(',', $finalProject->additional_method)) : []);
            @endphp
            <div class="card p-3 bg-light border-0 rounded-3 mb-2" style="max-height: 220px; overflow-y: auto;">
                @foreach(['Metode SAW / Topsis / Smart (SPK)', 'Blackbox Testing', 'System Usability Scale (SUS)', 'User Acceptance Testing (UAT)', 'PIECES / SWOT', 'Design Thinking', 'IoT (Monitoring / Rule-based)'] as $index => $method)
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="additional_method[]" value="{{ $method }}" id="method_{{ $index }}" @checked(in_array($method, $selectedMethods, true)) @disabled($lockedSubmission)>
                        <label class="form-check-label text-slate-800" for="method_{{ $index }}">
                            {{ $method }}
                        </label>
                    </div>
                @endforeach
            </div>
            <input name="additional_method_other" class="form-control" value="{{ old('additional_method_other') }}" placeholder="Metode lainnya (opsional)" @disabled($lockedSubmission)>
            <div class="form-text">Opsional, centang metode yang relevan.</div>
        </div>
        <div class="col-12"><h3 class="h5 border-bottom pb-2 mt-2">Implementasi Teknis</h3></div>
        <div class="col-md-6">
            <label class="form-label">Teknologi</label>
            <input name="technology" class="form-control" value="{{ old('technology', $finalProject?->technology) }}" required @disabled($lockedSubmission)>
            <div class="form-text">Contoh: PHP, Laravel, MySQL, Flutter, ESP32.</div>
        </div>
        <div class="col-md-6">
            <label class="form-label">Rencana Pengujian Sistem</label>
            @php
                $selectedTesting = old('testing_plan', $finalProject?->testing_plan ? array_map('trim', explode(',', $finalProject->testing_plan)) : []);
            @endphp
            <div class="card p-3 bg-light border-0 rounded-3 mb-2">
                @foreach(['Blackbox Testing', 'Uji Pengguna', 'Kuesioner', 'UAT'] as $index => $plan)
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="testing_plan[]" value="{{ $plan }}" id="test_{{ $index }}" @checked(in_array($plan, $selectedTesting, true)) @disabled($lockedSubmission)>
                        <label class="form-check-label text-slate-800" for="test_{{ $index }}">
                            {{ $plan }}
                        </label>
                    </div>
                @endforeach
            </div>
            <input name="testing_plan_other" class="form-control" value="{{ old('testing_plan_other') }}" placeholder="Rencana pengujian lainnya (opsional)" @disabled($lockedSubmission)>
        </div>
        <div class="col-12"><h3 class="h5 border-bottom pb-2 mt-2">Pernyataan</h3></div>
        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="declaration" value="1" id="declaration" required @checked(old('declaration', $finalProject?->declaration)) @disabled($lockedSubmission)>
                <label class="form-check-label" for="declaration">
                    Judul ini bukan hasil plagiasi, saya siap mengimplementasikan sistem, bukan hanya perancangan, dan saya siap menyelesaikan Tugas Akhir sesuai timeline.
                </label>
            </div>
        </div>
    </div>
    <div class="card-footer bg-white text-end">
        @if($lockedSubmission)
            <button class="btn btn-primary" disabled>Pengajuan Terkunci</button>
        @else
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#submitConfirmModal">
                Submit Judul
            </button>
        @endif
    </div>
</form>

@unless($lockedSubmission)
    <div class="modal fade" id="submitConfirmModal" tabindex="-1" aria-labelledby="submitConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="submitConfirmModalLabel">Kirim pengajuan judul?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    Setelah dikirim, pengajuan tidak bisa diubah lagi sampai admin memberi keputusan. Pastikan data, masalah nyata, fitur utama, metode, dan rencana pengujian sudah benar.
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cek Lagi</button>
                    <button type="submit" class="btn btn-primary" form="finalProjectForm">Ya, Kirim Judul</button>
                </div>
            </div>
        </div>
    </div>
@endunless
@endsection
