<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPrestasi extends Model
{
    use HasFactory;

    protected $table = 'detail_prestasi';

    protected $fillable = [
        'pengajuan_pa_id',
        'nama_lomba',
        'penyelenggara',
        'tingkat',
        'tanggal_pelaksanaan',
        'file_form_asesmen',
        'file_sertifikat',
        'file_presentasi',
        'url_produk',
    ];

    protected $casts = [
        'tanggal_pelaksanaan' => 'date',
    ];

    public function pengajuanPa(): BelongsTo
    {
        return $this->belongsTo(PengajuanPa::class, 'pengajuan_pa_id');
    }
}
