<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE seminar_proposals MODIFY status ENUM('SUBMITTED','WAITING_APPROVAL','APPROVED_BY_SUPERVISORS','READY_TO_SCHEDULE','SCHEDULED','FINISHED','REVISION','SEMINAR_PASSED','SEMINAR_REPEAT') NOT NULL DEFAULT 'SUBMITTED'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE seminar_proposals MODIFY status ENUM('SUBMITTED','WAITING_APPROVAL','APPROVED_BY_SUPERVISORS','READY_TO_SCHEDULE','SCHEDULED','FINISHED','REVISION') NOT NULL DEFAULT 'SUBMITTED'");
    }
};
