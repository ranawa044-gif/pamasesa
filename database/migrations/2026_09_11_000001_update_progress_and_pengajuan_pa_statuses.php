<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        // 1. Update existing progress_logs statuses to 'pending' or 'verified'
        if (Schema::hasTable('progress_logs')) {
            if ($driver === 'mysql' || $driver === 'pgsql') {
                // First modify enum to accept both sets of values before converting
                DB::statement("ALTER TABLE progress_logs MODIFY status ENUM('WAITING', 'APPROVED', 'REVISION', 'pending', 'verified') NOT NULL DEFAULT 'pending'");
                DB::statement("UPDATE progress_logs SET status = 'verified' WHERE status = 'APPROVED'");
                DB::statement("UPDATE progress_logs SET status = 'pending' WHERE status IN ('WAITING', 'REVISION')");
                // Now constrain to only 'pending' and 'verified'
                DB::statement("ALTER TABLE progress_logs MODIFY status ENUM('pending', 'verified') NOT NULL DEFAULT 'pending'");
            } elseif ($driver === 'sqlite') {
                DB::statement("UPDATE progress_logs SET status = 'verified' WHERE status = 'APPROVED'");
                DB::statement("UPDATE progress_logs SET status = 'pending' WHERE status IN ('WAITING', 'REVISION')");
            }
        }

        // 2. Update pengajuan_pa status_pengajuan enum to include 'acc_seminar'
        if (Schema::hasTable('pengajuan_pa')) {
            if ($driver === 'mysql' || $driver === 'pgsql') {
                DB::statement("ALTER TABLE pengajuan_pa MODIFY status_pengajuan ENUM('pending', 'review', 'approved', 'rejected', 'revision', 'acc_seminar') NOT NULL DEFAULT 'pending'");
            }
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if (Schema::hasTable('progress_logs') && ($driver === 'mysql' || $driver === 'pgsql')) {
            DB::statement("ALTER TABLE progress_logs MODIFY status ENUM('WAITING', 'APPROVED', 'REVISION', 'pending', 'verified') NOT NULL DEFAULT 'WAITING'");
            DB::statement("UPDATE progress_logs SET status = 'APPROVED' WHERE status = 'verified'");
            DB::statement("UPDATE progress_logs SET status = 'WAITING' WHERE status = 'pending'");
            DB::statement("ALTER TABLE progress_logs MODIFY status ENUM('WAITING', 'APPROVED', 'REVISION') NOT NULL DEFAULT 'WAITING'");
        }

        if (Schema::hasTable('pengajuan_pa') && ($driver === 'mysql' || $driver === 'pgsql')) {
            DB::statement("UPDATE pengajuan_pa SET status_pengajuan = 'approved' WHERE status_pengajuan = 'acc_seminar'");
            DB::statement("ALTER TABLE pengajuan_pa MODIFY status_pengajuan ENUM('pending', 'review', 'approved', 'rejected', 'revision') NOT NULL DEFAULT 'pending'");
        }
    }
};
