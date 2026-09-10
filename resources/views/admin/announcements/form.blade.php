@extends('layouts.app', [
    'title' => isset($announcement) ? 'Edit Informasi — PAMASESA' : 'Tambah Informasi — PAMASESA',
    'heading' => isset($announcement) ? 'Edit Informasi & Dokumen' : 'Tambah Informasi & Dokumen Baru'
])

@section('content')
<div class="card shadow-sm border-0 mb-4" style="max-width: 800px;">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <span class="fw-bold"><i class="bi bi-pencil-square text-primary me-2"></i>{{ isset($announcement) ? 'Form Perbarui Informasi' : 'Form Pengisian Informasi Baru' }}</span>
        <a href="{{ route('admin.announcements.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
    <div class="card-body p-4">
        <form method="post" action="{{ isset($announcement) ? route('admin.announcements.update', $announcement) : route('admin.announcements.store') }}" enctype="multipart/form-data">
            @csrf
            @if(isset($announcement))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label class="form-label">Judul Informasi / Dokumen <span class="text-danger">*</span></label>
                <input type="text" name="title" value="{{ old('title', $announcement->title ?? '') }}" class="form-control" placeholder="Contoh: Buku Panduan & Template Dokumen Proposal PA 2026" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Isi Pengumuman / Catatan Informasi <span class="text-danger">*</span></label>
                <textarea name="content" class="form-control" rows="5" placeholder="Tuliskan detail pengumuman atau instruksi kelengkapan dokumen di sini..." required>{{ old('content', $announcement->content ?? '') }}</textarea>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Upload File Lampiran (Dokumen/Template)</label>
                    <input type="file" name="attachment_file" class="form-control">
                    <div class="form-text">Format: PDF, DOC, DOCX, ZIP, RAR, PNG, JPG (Maks. 20MB).</div>
                    @if(isset($announcement) && $announcement->attachment_file)
                        <div class="mt-2 text-muted small">
                            File saat ini: <a href="{{ route('admin.announcements.download', $announcement) }}" target="_blank" class="fw-bold">{{ basename($announcement->attachment_file) }}</a>
                        </div>
                    @endif
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tautan Eksternal (URL Link)</label>
                    <input type="url" name="attachment_url" value="{{ old('attachment_url', $announcement->attachment_url ?? '') }}" class="form-control" placeholder="https://drive.google.com/...">
                    <div class="form-text">Opsional. Link Google Drive, Template Canva, atau repository.</div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="form-check form-switch p-3 bg-light rounded-3 border">
                        <input class="form-check-input ms-0 me-2" type="checkbox" name="is_pinned" value="1" id="is_pinned" @checked(old('is_pinned', $announcement->is_pinned ?? false))>
                        <label class="form-check-label fw-semibold text-slate-800" for="is_pinned">
                            Sematkan di Atas (Pinned)
                        </label>
                        <div class="text-muted small ms-4">Tampilkan pengumuman ini di urutan paling atas.</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-check form-switch p-3 bg-light rounded-3 border">
                        <input class="form-check-input ms-0 me-2" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $announcement->is_active ?? true))>
                        <label class="form-check-label fw-semibold text-slate-800" for="is_active">
                            Status Aktif
                        </label>
                        <div class="text-muted small ms-4">Tampilkan di Dashboard Mahasiswa.</div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ isset($announcement) ? 'Simpan Perubahan' : 'Terbitkan Informasi' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
