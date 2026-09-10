<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seminar_schedules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('seminar_proposal_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->time('time');
            $table->string('room');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seminar_schedules');
    }
};
