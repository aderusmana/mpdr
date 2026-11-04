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
        // 1. Ensure 'nik' has an index on users table if it exists
        if (Schema::hasColumn('users', 'nik')) {
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->index('nik', 'users_nik_index');
                });
            } catch (\Throwable $e) {}
        }

        // 2. Ensure 'atasan_nik' column exists
        if (!Schema::hasColumn('users', 'atasan_nik')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('atasan_nik')->nullable();
                $table->index('atasan_nik', 'users_atasan_nik_index');
            });
        }

        // 3. Try to add foreign key constraint if supported
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->foreign('atasan_nik')->references('nik')->on('users');
            });
        } catch (\Throwable $e) {
            // If MySQL InnoDB rejects foreign key (e.g. errno 150 or collation mismatch),
            // catch it gracefully so migration succeeds. Eloquent relationship atasan() works via application level.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['atasan_nik']);
            });
        } catch (\Throwable $e) {}

        if (Schema::hasColumn('users', 'atasan_nik')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('atasan_nik');
            });
        }
    }
};
