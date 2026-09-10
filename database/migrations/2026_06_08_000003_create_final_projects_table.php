<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('final_projects', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('student_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('background');
            $table->text('research_object');
            $table->text('business_process');
            $table->string('proposed_supervisor_name')->nullable();
            $table->string('system_type');
            $table->text('main_features');
            $table->unsignedTinyInteger('actor_count');
            $table->string('development_method');
            $table->string('additional_method')->nullable();
            $table->string('technology');
            $table->string('testing_plan');
            $table->boolean('declaration')->default(false);
            $table->enum('status', ['DRAFT', 'SUBMITTED', 'REVIEW', 'APPROVED', 'REVISION', 'REJECTED'])->default('DRAFT');
            $table->text('review_note')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('final_projects');
    }
};
