<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('title_reviews', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('final_project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('problem_score');
            $table->unsignedTinyInteger('solution_score');
            $table->unsignedTinyInteger('complexity_score');
            $table->unsignedTinyInteger('method_score');
            $table->unsignedTinyInteger('testing_score');
            $table->unsignedSmallInteger('total_score');
            $table->enum('decision', ['APPROVED', 'REVISION', 'REJECTED']);
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('title_reviews');
    }
};
