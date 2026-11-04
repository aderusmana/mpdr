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
            if (!Schema::hasColumn('users', 'atasan_nik')) {
                $table->string('atasan_nik')->nullable();
            }
            try {
                $table->foreign('atasan_nik')->references('nik')->on('users');
            } catch (\Throwable $e) {}
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'atasan_nik')) {
                try {
                    $table->dropForeign(['atasan_nik']);
                } catch (\Throwable $e) {}
                $table->dropColumn('atasan_nik');
            }
        });
    }
};