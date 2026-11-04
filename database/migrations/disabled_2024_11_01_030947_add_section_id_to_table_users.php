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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'section_id')) {
                $table->unsignedBigInteger('section_id')->nullable();
            }
            if (Schema::hasTable('sections')) {
                try {
                    $table->foreign('section_id')->references('id')->on('sections')->nullOnDelete();
                } catch (\Throwable $e) {}
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'section_id')) {
                try {
                    $table->dropForeign(['section_id']);
                } catch (\Throwable $e) {}
                $table->dropColumn('section_id');
            }
        });
    }
};

