@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="page-kicker">Sidang Akhir</div>
            <h3 class="fw-bold">Pendaftaran Sidang Akhir</h3>
        </div>
    </div>

    @if(! $finalProject)
        <div class="alert alert-info">
            Anda harus mengajukan judul terlebih dahulu sebelum mendaftar sidang akhir.
        </div>
    @elseif($finalDefense)
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card p-4 h-100">
                    <h5 class="mb-3">Status Pendaftaran</h5>
                    <x-status-badge :status="$finalDefense->status" />
                    <dl class="row mt-3">
                        <dt class="col-5">Tanggal Pengajuan</dt>
                        <dd class="col-7">{{ $finalDefense->submitted_at?->format('d M Y H:i') }}</dd>
                        <dt class="col-5">File Laporan Akhir</dt>
                        <dd class="col-7"><a href="{{ asset('storage/' . $finalDefense->final_report_file) }}" target="_blank">Lihat</a></dd>
                        <dt class="col-5">Aplikasi / Repository</dt>
                        <dd class="col-7">
                            @if(filter_var($finalDefense->application_file, FILTER_VALIDATE_URL))
                                <a href="{{ $finalDefense->application_file }}" target="_blank">Link Repository</a>
                            @else
                                <a href="{{ asset('storage/' . $finalDefense->application_file) }}" target="_blank">File Aplikasi</a>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-4 h-100">
                    <h5 class="mb-3">Penguji</h5>
                    @if($finalDefense->examiners->isEmpty())
                        <p class="text-muted">Penguji belum ditetapkan. Tunggu konfirmasi dari admin.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($finalDefense->examiners as $examiner)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $examiner->type }}
                                    <span>{{ $examiner->lecturer->nama }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    @elseif($finalProject->status !== 'READY_FOR_DEFENSE')
        <div class="alert alert-warning">
            Final project Anda belum siap untuk sidang akhir. Status saat ini: <strong>{{ $finalProject->status }}</strong>.
        </div>
    @else
        <div class="alert alert-success">
            Final project Anda sudah siap untuk daftar sidang akhir. Silakan lengkapi formulir pendaftaran di bawah.
        </div>
        <div class="card p-4">
            <form method="post" action="{{ route('student.final-defenses.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Upload Laporan Akhir (PDF)</label>
                    <input type="file" name="final_report_file" class="form-control" accept="application/pdf" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Unggah File Aplikasi</label>
                    <input type="file" name="application_upload" class="form-control" accept=".pdf,.zip,.rar,.doc,.docx,.txt">
                </div>
                <div class="mb-3">
                    <label class="form-label">Atau Tautan Repository</label>
                    <input type="url" name="application_link" class="form-control" placeholder="https://github.com/username/repo">
                </div>
                <button type="submit" class="btn btn-primary">Kirim Pendaftaran</button>
            </form>
        </div>
    @endif
@endsection
