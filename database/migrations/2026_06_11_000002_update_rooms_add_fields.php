<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table): void {
            if (! Schema::hasColumn('rooms', 'location')) {
                $table->string('location')->nullable()->after('name');
            }

            if (! Schema::hasColumn('rooms', 'capacity')) {
                $table->unsignedInteger('capacity')->default(0)->after('location');
            }

            if (! Schema::hasColumn('rooms', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('capacity');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table): void {
            if (Schema::hasColumn('rooms', 'is_active')) {
                $table->dropColumn('is_active');
            }
            if (Schema::hasColumn('rooms', 'capacity')) {
                $table->dropColumn('capacity');
            }
            if (Schema::hasColumn('rooms', 'location')) {
                $table->dropColumn('location');
            }
        });
    }
};
