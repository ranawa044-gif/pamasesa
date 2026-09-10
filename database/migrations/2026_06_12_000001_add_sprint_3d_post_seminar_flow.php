<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seminar_revisions', function (Blueprint $table): void {
            if (! Schema::hasColumn('seminar_revisions', 'student_note')) {
                $table->text('student_note')->nullable()->after('revision_note');
            }
            if (! Schema::hasColumn('seminar_revisions', 'student_file_path')) {
                $table->string('student_file_path')->nullable()->after('student_note');
            }
            if (! Schema::hasColumn('seminar_revisions', 'validation_note')) {
                $table->text('validation_note')->nullable()->after('student_file_path');
            }
        });

        if (Schema::hasTable('seminar_revisions')) {
            $driver = DB::getDriverName();
            if ($driver === 'mysql' || $driver === 'pgsql') {
                DB::statement("ALTER TABLE seminar_revisions MODIFY status ENUM('OPEN','WAITING_VALIDATION','REVISION','DONE') DEFAULT 'OPEN'");
            } elseif ($driver === 'sqlite') {
                Schema::table('seminar_revisions', function (Blueprint $table): void {
                    $table->enum('status', ['OPEN', 'WAITING_VALIDATION', 'REVISION', 'DONE'])->default('OPEN')->change();
                });
            }
        }

        if (Schema::hasTable('progress_logs')) {
            $driver = DB::getDriverName();
            if ($driver === 'mysql' || $driver === 'pgsql') {
                DB::statement("ALTER TABLE progress_logs MODIFY progress_type ENUM('PROPOSAL','DESIGN','IMPLEMENTATION','TESTING','REVISION_AFTER_SEMINAR','FINAL_REPORT') NOT NULL");
            } elseif ($driver === 'sqlite') {
                Schema::table('progress_logs', function (Blueprint $table): void {
                    $table->enum('progress_type', ['PROPOSAL', 'DESIGN', 'IMPLEMENTATION', 'TESTING', 'REVISION_AFTER_SEMINAR', 'FINAL_REPORT'])->change();
                });
            }
        }

        if (Schema::hasTable('final_projects')) {
            $driver = DB::getDriverName();
            if ($driver === 'mysql' || $driver === 'pgsql') {
                DB::statement("ALTER TABLE final_projects MODIFY status ENUM('DRAFT','SUBMITTED','REVIEW','APPROVED','REVISION','REJECTED','READY_FOR_DEFENSE') DEFAULT 'DRAFT'");
            } elseif ($driver === 'sqlite') {
                Schema::table('final_projects', function (Blueprint $table): void {
                    $table->enum('status', ['DRAFT', 'SUBMITTED', 'REVIEW', 'APPROVED', 'REVISION', 'REJECTED', 'READY_FOR_DEFENSE'])->default('DRAFT')->change();
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('seminar_revisions')) {
            Schema::table('seminar_revisions', function (Blueprint $table): void {
                $table->dropColumn(['student_note', 'student_file_path', 'validation_note']);
                $table->enum('status', ['OPEN', 'DONE'])->default('OPEN')->change();
            });
        }

        if (Schema::hasTable('progress_logs')) {
            Schema::table('progress_logs', function (Blueprint $table): void {
                $table->enum('progress_type', ['PROPOSAL', 'DESIGN', 'IMPLEMENTATION', 'TESTING'])->change();
            });
        }

        if (Schema::hasTable('final_projects')) {
            Schema::table('final_projects', function (Blueprint $table): void {
                $table->enum('status', ['DRAFT', 'SUBMITTED', 'REVIEW', 'APPROVED', 'REVISION', 'REJECTED'])->default('DRAFT')->change();
            });
        }
    }
};
