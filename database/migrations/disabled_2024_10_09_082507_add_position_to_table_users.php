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
        // Position column and foreign key are already handled in 2024_05_07_032256_add_position_id_in_user_table.php
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Handled in 2024_05_07_032256_add_position_id_in_user_table.php
    }
};
