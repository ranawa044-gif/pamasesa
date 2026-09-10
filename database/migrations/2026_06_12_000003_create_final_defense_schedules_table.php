<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('final_defense_schedules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('final_defense_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['DRAFT', 'PUBLISHED'])->default('DRAFT');
            $table->timestamps();

            $table->unique(['final_defense_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('final_defense_schedules');
    }
};
