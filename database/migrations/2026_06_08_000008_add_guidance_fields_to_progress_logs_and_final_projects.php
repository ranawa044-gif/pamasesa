<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('progress_logs', function (Blueprint $table): void {
            $table->string('file_path')->nullable()->after('description');
            $table->string('revision_file_path')->nullable()->after('review_note');
        });

        Schema::table('final_projects', function (Blueprint $table): void {
            $table->enum('supervisor_one_approval', ['PENDING', 'APPROVED', 'REVISION'])->default('PENDING')->after('approved_at');
            $table->enum('supervisor_two_approval', ['PENDING', 'APPROVED', 'REVISION'])->default('PENDING')->after('supervisor_one_approval');
        });
    }

    public function down(): void
    {
        Schema::table('progress_logs', function (Blueprint $table): void {
            $table->dropColumn(['file_path', 'revision_file_path']);
        });

        Schema::table('final_projects', function (Blueprint $table): void {
            $table->dropColumn(['supervisor_one_approval', 'supervisor_two_approval']);
        });
    }
};
