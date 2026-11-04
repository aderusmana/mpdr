<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'section_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('section_id')->nullable();
            });
        }

        if (Schema::hasTable('sections')) {
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->foreign('section_id')->references('id')->on('sections')->nullOnDelete();
                });
            } catch (\Throwable $e) {}
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['section_id']);
            });
        } catch (\Throwable $e) {}

        if (Schema::hasColumn('users', 'section_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('section_id');
            });
        }
    }
};
