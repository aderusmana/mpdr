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
        if (!Schema::hasColumn('users', 'position_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('position_id')->nullable()->after('department_id');
            });
        }

        if (Schema::hasTable('positions')) {
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->foreign('position_id')->references('id')->on('positions')->nullOnDelete();
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
                $table->dropForeign(['position_id']);
            });
        } catch (\Throwable $e) {}

        if (Schema::hasColumn('users', 'position_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('position_id');
            });
        }
    }
};
