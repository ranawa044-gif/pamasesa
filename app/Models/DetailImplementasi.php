<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailImplementasi extends Model
{
    use HasFactory;

    protected $table = 'detail_implementasi';

    protected $fillable = [
        'pengajuan_pa_id',
        'judul_pa',
        'lokasi_penelitian',
        'pendahuluan',
        'dosen_pembimbing',
        'proses_bisnis',
        'jenis_sistem',
        'jumlah_aktor',
        'fitur_utama',
        'metode_pengembangan',
        'metode_pendekatan',
        'teknologi',
        'rencana_pengujian',
    ];

    protected $casts = [
        'metode_pendekatan' => 'array',
        'rencana_pengujian' => 'array',
        'jumlah_aktor' => 'integer',
    ];

    public function pengajuanPa(): BelongsTo
    {
        return $this->belongsTo(PengajuanPa::class, 'pengajuan_pa_id');
    }
}
