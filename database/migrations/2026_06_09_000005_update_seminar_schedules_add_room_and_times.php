<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seminar_schedules', function (Blueprint $table): void {
            if (! Schema::hasColumn('seminar_schedules', 'start_time')) {
                $table->time('start_time')->after('date');
            }

            if (! Schema::hasColumn('seminar_schedules', 'end_time')) {
                $table->time('end_time')->after('start_time');
            }

            if (! Schema::hasColumn('seminar_schedules', 'room_id')) {
                $table->foreignId('room_id')->nullable()->after('end_time')->constrained()->cascadeOnDelete();
            }

            if (Schema::hasColumn('seminar_schedules', 'time')) {
                $table->dropColumn('time');
            }

            if (Schema::hasColumn('seminar_schedules', 'room')) {
                $table->dropColumn('room');
            }
        });
    }

    public function down(): void
    {
        Schema::table('seminar_schedules', function (Blueprint $table): void {
            if (! Schema::hasColumn('seminar_schedules', 'time')) {
                $table->time('time')->after('date');
            }

            if (! Schema::hasColumn('seminar_schedules', 'room')) {
                $table->string('room')->after('time');
            }

            if (Schema::hasColumn('seminar_schedules', 'room_id')) {
                $table->dropForeign(['room_id']);
                $table->dropColumn('room_id');
            }

            if (Schema::hasColumn('seminar_schedules', 'start_time')) {
                $table->dropColumn('start_time');
            }

            if (Schema::hasColumn('seminar_schedules', 'end_time')) {
                $table->dropColumn('end_time');
            }
        });
    }
};
