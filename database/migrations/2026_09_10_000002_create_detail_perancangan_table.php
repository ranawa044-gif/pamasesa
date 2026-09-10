<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_perancangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_pa_id')->constrained('pengajuan_pa')->cascadeOnDelete();
            $table->string('judul_pa');
            $table->string('lokasi_penelitian');
            $table->text('latar_belakang');
            $table->string('dosen_pembimbing');
            $table->text('proses_bisnis');
            $table->string('jenis_sistem');
            $table->unsignedInteger('jumlah_aktor');
            $table->text('fitur_utama');
            $table->string('metode_pengembangan');
            $table->json('metode_pendekatan')->nullable();
            $table->string('tools_perancangan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_perancangan');
    }
};
