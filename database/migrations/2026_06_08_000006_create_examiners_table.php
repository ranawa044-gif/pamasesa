<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('examiners', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('final_project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lecturer_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['PENGUJI_1', 'PENGUJI_2']);
            $table->timestamps();

            $table->unique(['final_project_id', 'type']);
            $table->unique(['final_project_id', 'lecturer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examiners');
    }
};
