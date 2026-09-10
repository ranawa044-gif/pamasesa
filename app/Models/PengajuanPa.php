<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PengajuanPa extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_pa';

    protected $fillable = [
        'user_id',
        'jenis_skema',
        'status_pengajuan',
        'catatan_review',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detailPerancangan(): HasOne
    {
        return $this->hasOne(DetailPerancangan::class, 'pengajuan_pa_id');
    }

    public function detailImplementasi(): HasOne
    {
        return $this->hasOne(DetailImplementasi::class, 'pengajuan_pa_id');
    }

    public function detailPrestasi(): HasOne
    {
        return $this->hasOne(DetailPrestasi::class, 'pengajuan_pa_id');
    }

    // Alias relasi snake_case untuk kompatibilitas eager loading
    public function detail_perancangan(): HasOne
    {
        return $this->detailPerancangan();
    }

    public function detail_implementasi(): HasOne
    {
        return $this->detailImplementasi();
    }

    public function detail_prestasi(): HasOne
    {
        return $this->detailPrestasi();
    }

    /**
     * Mengambil instance detail aktif sesuai jenis skema
     */
    public function getDetailAttribute()
    {
        return match ($this->jenis_skema) {
            'perancangan' => $this->detailPerancangan,
            'implementasi' => $this->detailImplementasi,
            'prestasi' => $this->detailPrestasi,
            default => null,
        };
    }

    /**
     * Accessor: Judul atau Nama Lomba yang ditampilkan di tabel
     */
    public function getJudulTampilAttribute(): string
    {
        return match ($this->jenis_skema) {
            'perancangan' => $this->detailPerancangan?->judul_pa ?? '-',
            'implementasi' => $this->detailImplementasi?->judul_pa ?? '-',
            'prestasi' => $this->detailPrestasi?->nama_lomba ?? '-',
            default => '-',
        };
    }

    /**
     * Accessor: Nama Dosen Pembimbing yang ditampilkan di tabel
     */
    public function getPembimbingTampilAttribute(): ?string
    {
        return match ($this->jenis_skema) {
            'perancangan' => $this->detailPerancangan?->dosen_pembimbing ?? '-',
            'implementasi' => $this->detailImplementasi?->dosen_pembimbing ?? '-',
            'prestasi' => '-',
            default => '-',
        };
    }

    /**
     * Sinkronisasi data pengajuan ke tabel final_projects
     */
    public function syncToFinalProject(?string $overrideStatus = null): ?FinalProject
    {
        $student = $this->user?->student;
        if (!$student) {
            return null;
        }

        $detail = $this->detail;
        $status = $overrideStatus ?? match ($this->status_pengajuan) {
            'approved' => 'APPROVED',
            'revision' => 'REVISION',
            'rejected' => 'REJECTED',
            default => 'SUBMITTED',
        };

        return FinalProject::updateOrCreate(
            ['student_id' => $student->id],
            [
                'title' => $this->judul_tampil,
                'research_object' => $detail?->lokasi_penelitian ?? ($this->jenis_skema === 'prestasi' ? ($this->detailPrestasi?->penyelenggara ?? '-') : '-'),
                'background' => $detail?->pendahuluan ?? ($this->jenis_skema === 'prestasi' ? ('Skema Prestasi: ' . ($this->detailPrestasi?->nama_lomba ?? '-')) : '-'),
                'business_process' => $detail?->proses_bisnis ?? ($this->jenis_skema === 'prestasi' ? 'Perlombaan / Kompetisi Prestasi' : '-'),
                'proposed_supervisor_name' => $this->pembimbing_tampil ?? '-',
                'system_type' => $detail?->jenis_sistem ?? ($this->jenis_skema === 'prestasi' ? 'Skema Prestasi' : 'Sistem Informasi'),
                'main_features' => $detail?->fitur_utama ?? ($this->jenis_skema === 'prestasi' ? ('Lomba: ' . ($this->detailPrestasi?->nama_lomba ?? '-') . ' | Tingkat: ' . ($this->detailPrestasi?->tingkat ?? '-')) : '-'),
                'actor_count' => $detail?->jumlah_aktor ?? 1,
                'development_method' => $detail?->metode_pengembangan ?? 'Waterfall',
                'additional_method' => is_array($detail?->metode_pendekatan) ? implode(', ', $detail->metode_pendekatan) : ($detail?->metode_pendekatan ?? '-'),
                'technology' => $detail?->teknologi ?? ($detail?->tools_perancangan ?? '-'),
                'testing_plan' => is_array($detail?->rencana_pengujian) ? implode(', ', $detail->rencana_pengujian) : ($detail?->rencana_pengujian ?? '-'),
                'declaration' => true,
                'status' => $status,
                'review_note' => $this->catatan_review,
                'submitted_at' => $this->created_at ?? now(),
                'approved_at' => $this->status_pengajuan === 'approved' ? ($this->updated_at ?? now()) : null,
            ]
        );
    }

    /**
     * Accessor untuk mengambil data TitleReview dari FinalProject terkait
     */
    public function getTitleReviewAttribute(): ?TitleReview
    {
        return $this->user?->student?->finalProject?->titleReview;
    }
}
