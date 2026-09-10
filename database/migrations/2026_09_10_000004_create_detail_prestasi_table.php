<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_prestasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_pa_id')->constrained('pengajuan_pa')->cascadeOnDelete();
            $table->string('nama_lomba');
            $table->string('penyelenggara');
            $table->string('tingkat');
            $table->date('tanggal_pelaksanaan');
            $table->string('file_form_asesmen');
            $table->string('file_sertifikat');
            $table->string('file_presentasi');
            $table->string('url_produk');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_prestasi');
    }
};
