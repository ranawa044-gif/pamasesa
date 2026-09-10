<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("UPDATE seminar_proposals SET supervisor_one_approval = 'WAITING' WHERE supervisor_one_approval = 'PENDING'");
        DB::statement("UPDATE seminar_proposals SET supervisor_two_approval = 'WAITING' WHERE supervisor_two_approval = 'PENDING'");

        DB::statement("ALTER TABLE seminar_proposals MODIFY status ENUM('DRAFT','SUBMITTED','WAITING_APPROVAL','APPROVED_BY_SUPERVISORS','READY_TO_SCHEDULE','SCHEDULED','FINISHED','REVISION') NOT NULL DEFAULT 'DRAFT'");
        DB::statement("ALTER TABLE seminar_proposals MODIFY supervisor_one_approval ENUM('WAITING','APPROVED','REVISION') NOT NULL DEFAULT 'WAITING'");
        DB::statement("ALTER TABLE seminar_proposals MODIFY supervisor_two_approval ENUM('WAITING','APPROVED','REVISION') NOT NULL DEFAULT 'WAITING'");

        Schema::table('seminar_proposals', function (Blueprint $table): void {
            if (!Schema::hasColumn('seminar_proposals', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE seminar_proposals MODIFY status ENUM('SUBMITTED','WAITING_APPROVAL','READY_FOR_SEMINAR','SCHEDULED','COMPLETED','REVISION') NOT NULL DEFAULT 'SUBMITTED'");
        DB::statement("ALTER TABLE seminar_proposals MODIFY supervisor_one_approval ENUM('PENDING','APPROVED','REVISION') NOT NULL DEFAULT 'PENDING'");
        DB::statement("ALTER TABLE seminar_proposals MODIFY supervisor_two_approval ENUM('PENDING','APPROVED','REVISION') NOT NULL DEFAULT 'PENDING'");

        Schema::table('seminar_proposals', function (Blueprint $table): void {
            $table->dropColumn('submitted_at');
        });
    }
};
