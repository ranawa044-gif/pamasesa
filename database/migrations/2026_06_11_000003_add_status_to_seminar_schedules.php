<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seminar_schedules', function (Blueprint $table): void {
            if (! Schema::hasColumn('seminar_schedules', 'status')) {
                $table->string('status')->default('DRAFT')->after('room_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('seminar_schedules', function (Blueprint $table): void {
            if (Schema::hasColumn('seminar_schedules', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
