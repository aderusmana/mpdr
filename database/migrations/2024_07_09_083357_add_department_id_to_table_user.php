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
            if (!Schema::hasColumn('users', 'department_id')) {
                $table->unsignedBigInteger('department_id')->nullable();
            }
            if (Schema::hasTable('departments')) {
                try {
                    $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
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
            if (Schema::hasColumn('users', 'department_id')) {
                try {
                    $table->dropForeign(['department_id']);
                } catch (\Throwable $e) {}
                $table->dropColumn('department_id');
            }
        });
    }
};
