<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seminar_assessments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('seminar_schedule_id')->constrained('seminar_schedules')->cascadeOnDelete();
            $table->foreignId('lecturer_id')->constrained('lecturers')->cascadeOnDelete();
            $table->unsignedTinyInteger('problem_score');
            $table->unsignedTinyInteger('method_score');
            $table->unsignedTinyInteger('design_score');
            $table->unsignedTinyInteger('implementation_score');
            $table->unsignedTinyInteger('presentation_score');
            $table->unsignedSmallInteger('total_score');
            $table->enum('decision', ['PASSED', 'PASSED_WITH_REVISION', 'REPEAT']);
            $table->text('revision_note')->nullable();
            $table->timestamp('assessed_at')->nullable();
            $table->timestamps();
            $table->unique(['seminar_schedule_id', 'lecturer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seminar_assessments');
    }
};
