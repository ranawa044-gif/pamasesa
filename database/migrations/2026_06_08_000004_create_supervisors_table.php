<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supervisors', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('final_project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lecturer_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['PEMBIMBING_1', 'PEMBIMBING_2']);
            $table->timestamp('assigned_at');
            $table->timestamps();

            $table->unique(['final_project_id', 'type']);
            $table->unique(['final_project_id', 'lecturer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supervisors');
    }
};
