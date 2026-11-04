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
            if (!Schema::hasColumn('users', 'position_id')) {
                $table->unsignedBigInteger('position_id')->nullable();
            }
            if (Schema::hasTable('positions')) {
                try {
                    $table->foreign('position_id')->references('id')->on('positions')->nullOnDelete();
                } catch (\Throwable $e) {
                    // foreign key already exists or cannot be created
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'position_id')) {
                try {
                    $table->dropForeign(['position_id']);
                } catch (\Throwable $e) {}
                $table->dropColumn('position_id');
            }
        });
    }
};
