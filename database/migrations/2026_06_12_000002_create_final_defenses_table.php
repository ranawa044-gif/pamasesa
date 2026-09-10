<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('final_defenses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('final_project_id')->constrained()->cascadeOnDelete();
            $table->string('final_report_file');
            $table->text('application_file');
            $table->enum('status', ['SUBMITTED', 'WAITING_EXAMINER', 'READY_TO_SCHEDULE', 'SCHEDULED', 'FINISHED', 'REVISION', 'PASSED'])->default('SUBMITTED');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        $finalProjectFkExists = collect(DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'examiners' AND COLUMN_NAME = 'final_project_id' AND REFERENCED_TABLE_NAME = 'final_projects'"))->isNotEmpty();

        Schema::table('examiners', function (Blueprint $table) use ($finalProjectFkExists): void {
            if ($finalProjectFkExists) {
                $table->dropForeign(['final_project_id']);
            }

            if (! Schema::hasColumn('examiners', 'final_defense_id')) {
                $table->foreignId('final_defense_id')->nullable()->after('final_project_id')->constrained('final_defenses')->nullOnDelete();
            }
            if (! Schema::hasColumn('examiners', 'assigned_at')) {
                $table->timestamp('assigned_at')->nullable()->after('type');
            }

            $existingIndexes = collect(DB::select('SHOW INDEX FROM `examiners` WHERE Non_unique = 0'))->pluck('Key_name')->unique()->all();
            if (in_array('examiners_final_project_id_type_unique', $existingIndexes, true)) {
                $table->dropUnique(['final_project_id', 'type']);
            }
            if (in_array('examiners_final_project_id_lecturer_id_unique', $existingIndexes, true)) {
                $table->dropUnique(['final_project_id', 'lecturer_id']);
            }

            $table->foreign('final_project_id')->references('id')->on('final_projects')->cascadeOnDelete();
            $table->unique(['final_defense_id', 'type']);
            $table->unique(['final_defense_id', 'lecturer_id']);
        });
    }

    public function down(): void
    {
        Schema::table('examiners', function (Blueprint $table): void {
            if (Schema::hasColumn('examiners', 'final_defense_id')) {
                $table->dropForeign(['final_defense_id']);
                $table->dropColumn('final_defense_id');
            }
            if (Schema::hasColumn('examiners', 'assigned_at')) {
                $table->dropColumn('assigned_at');
            }
            $table->dropUnique(['final_defense_id', 'type']);
            $table->dropUnique(['final_defense_id', 'lecturer_id']);
            $table->unique(['final_project_id', 'type']);
            $table->unique(['final_project_id', 'lecturer_id']);
        });

        Schema::dropIfExists('final_defenses');
    }
};
