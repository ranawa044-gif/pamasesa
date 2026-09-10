<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seminar_revisions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('seminar_schedule_id')->constrained('seminar_schedules')->cascadeOnDelete();
            $table->foreignId('lecturer_id')->constrained('lecturers')->cascadeOnDelete();
            $table->enum('revision_category', ['BAB_1', 'BAB_2', 'BAB_3', 'SYSTEM', 'OTHER']);
            $table->text('revision_note');
            $table->enum('status', ['OPEN', 'DONE'])->default('OPEN');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seminar_revisions');
    }
};
