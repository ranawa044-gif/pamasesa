<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_pa', function (Blueprint $table): void {
            if (!Schema::hasColumn('pengajuan_pa', 'is_acc_p1')) {
                $table->boolean('is_acc_p1')->default(false)->after('status_pengajuan');
            }
            if (!Schema::hasColumn('pengajuan_pa', 'is_acc_p2')) {
                $table->boolean('is_acc_p2')->default(false)->after('is_acc_p1');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_pa', function (Blueprint $table): void {
            $table->dropColumn(['is_acc_p1', 'is_acc_p2']);
        });
    }
};
