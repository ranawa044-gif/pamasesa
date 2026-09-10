<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_perancangan', function (Blueprint $table) {
            $table->renameColumn('latar_belakang', 'pendahuluan');
        });

        Schema::table('detail_implementasi', function (Blueprint $table) {
            $table->renameColumn('latar_belakang', 'pendahuluan');
        });
    }

    public function down(): void
    {
        Schema::table('detail_perancangan', function (Blueprint $table) {
            $table->renameColumn('pendahuluan', 'latar_belakang');
        });

        Schema::table('detail_implementasi', function (Blueprint $table) {
            $table->renameColumn('pendahuluan', 'latar_belakang');
        });
    }
};
