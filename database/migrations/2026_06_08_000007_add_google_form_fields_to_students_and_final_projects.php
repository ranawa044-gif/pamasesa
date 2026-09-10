<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table): void {
            if (! Schema::hasColumn('students', 'phone')) {
                $table->string('phone')->nullable()->after('kelas');
            }

            if (! Schema::hasColumn('students', 'guardian_phone')) {
                $table->string('guardian_phone')->nullable()->after('phone');
            }
        });

        Schema::table('final_projects', function (Blueprint $table): void {
            if (! Schema::hasColumn('final_projects', 'proposed_supervisor_name')) {
                $table->string('proposed_supervisor_name')->nullable()->after('business_process');
            }

            if (! Schema::hasColumn('final_projects', 'system_type')) {
                $table->string('system_type')->nullable()->after('proposed_supervisor_name');
            }

            if (! Schema::hasColumn('final_projects', 'main_features')) {
                $table->text('main_features')->nullable()->after('system_type');
            }

            if (! Schema::hasColumn('final_projects', 'actor_count')) {
                $table->unsignedTinyInteger('actor_count')->nullable()->after('main_features');
            }

            if (! Schema::hasColumn('final_projects', 'testing_plan')) {
                $table->string('testing_plan')->nullable()->after('technology');
            }

            if (! Schema::hasColumn('final_projects', 'declaration')) {
                $table->boolean('declaration')->default(false)->after('testing_plan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('final_projects', function (Blueprint $table): void {
            foreach (['declaration', 'testing_plan', 'actor_count', 'main_features', 'system_type', 'proposed_supervisor_name'] as $column) {
                if (Schema::hasColumn('final_projects', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('students', function (Blueprint $table): void {
            foreach (['guardian_phone', 'phone'] as $column) {
                if (Schema::hasColumn('students', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
