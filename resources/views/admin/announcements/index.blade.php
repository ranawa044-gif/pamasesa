@extends('layouts.app', ['title' => 'Kelola Informasi & Dokumen — PAMASESA', 'heading' => 'Informasi & Dokumen Kelengkapan PA'])

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <div>
            <h5 class="fw-bold mb-1 text-slate-800"><i class="bi bi-megaphone-fill text-primary me-2"></i>Daftar Informasi & Dokumen Pengumuman</h5>
            <div class="text-muted small">Kelola informasi, panduan, dan file lampiran kelengkapan PA yang tampil di Dashboard Mahasiswa.</div>
        </div>
        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Informasi
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">Pinned</th>
                        <th>Judul Informasi</th>
                        <th>File / Link Lampiran</th>
                        <th>Status</th>
                        <th>Tanggal dibuat</th>
                        <th class="text-end" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $announcement)
                        <tr>
                            <td class="text-center">
                                @if($announcement->is_pinned)
                                    <span class="badge bg-amber-subtle text-warning border border-amber-subtle" title="Disematkan di atas"><i class="bi bi-pin-angle-fill"></i></span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-slate-800">{{ $announcement->title }}</div>
                                <div class="text-muted small text-truncate" style="max-width: 320px;">{{ Str::limit(strip_tags($announcement->content), 80) }}</div>
                            </td>
                            <td>
                                @if($announcement->attachment_file)
                                    <a href="{{ route('admin.announcements.download', $announcement) }}" class="badge bg-indigo-subtle text-primary border text-decoration-none me-1" target="_blank">
                                        <i class="bi bi-file-earmark-arrow-down-fill me-1"></i> File Lampiran
                                    </a>
                                @endif
                                @if($announcement->attachment_url)
                                    <a href="{{ $announcement->attachment_url }}" target="_blank" class="badge bg-cyan-subtle text-cyan-emphasis border text-decoration-none">
                                        <i class="bi bi-link-45deg me-1"></i> Link External
                                    </a>
                                @endif
                                @if(!$announcement->attachment_file && !$announcement->attachment_url)
                                    <span class="text-muted small">Tidak ada</span>
                                @endif
                            </td>
                            <td>
                                @if($announcement->is_active)
                                    <span class="badge bg-emerald-subtle text-success border border-emerald-subtle"><i class="bi bi-check-circle-fill me-1"></i>Aktif</span>
                                @else
                                    <span class="badge bg-light text-muted border"><i class="bi bi-eye-slash-fill me-1"></i>Non-aktif</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $announcement->created_at->translatedFormat('d M Y, H:i') }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form method="post" action="{{ route('admin.announcements.destroy', $announcement) }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus informasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-megaphone text-muted fs-2 d-block mb-2"></i>
                                Belum ada informasi/pengumuman yang ditambahkan. Silakan klik tombol <strong>Tambah Informasi</strong>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($announcements->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $announcements->links() }}
        </div>
    @endif
</div>
@endsection
