<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('final_project_id')->constrained()->cascadeOnDelete();
            $table->enum('progress_type', ['PROPOSAL', 'DESIGN', 'IMPLEMENTATION', 'TESTING']);
            $table->unsignedTinyInteger('percentage');
            $table->text('description');
            $table->enum('status', ['WAITING', 'APPROVED', 'REVISION'])->default('WAITING');
            $table->text('review_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_logs');
    }
};
