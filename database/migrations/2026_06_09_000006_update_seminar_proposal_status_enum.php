<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seminar_proposals', function (Blueprint $table): void {
            $table->enum('status', ['REQUESTED', 'WAITING_APPROVAL', 'READY_TO_SCHEDULE', 'SCHEDULED', 'FINISHED', 'REVISION'])
                ->default('REQUESTED')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('seminar_proposals', function (Blueprint $table): void {
            $table->enum('status', ['SUBMITTED', 'WAITING_APPROVAL', 'READY_FOR_SEMINAR', 'SCHEDULED', 'COMPLETED', 'REVISION'])
                ->default('SUBMITTED')
                ->change();
        });
    }
};
