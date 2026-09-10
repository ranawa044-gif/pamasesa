<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seminar_proposals', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('final_project_id')->unique()->constrained()->cascadeOnDelete();
            $table->enum('status', ['SUBMITTED', 'WAITING_APPROVAL', 'READY_FOR_SEMINAR', 'SCHEDULED', 'COMPLETED', 'REVISION'])->default('SUBMITTED');
            $table->string('proposal_file');
            $table->enum('supervisor_one_approval', ['PENDING', 'APPROVED', 'REVISION'])->default('PENDING');
            $table->enum('supervisor_two_approval', ['PENDING', 'APPROVED', 'REVISION'])->default('PENDING');
            $table->text('supervisor_one_note')->nullable();
            $table->text('supervisor_two_note')->nullable();
            $table->unsignedTinyInteger('supervisor_one_score')->nullable();
            $table->unsignedTinyInteger('supervisor_two_score')->nullable();
            $table->text('final_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seminar_proposals');
    }
};
