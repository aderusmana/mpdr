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
        Schema::create('mpdr_approvers', function (Blueprint $table) {
            $table->id();
            $table->string('user_nik');
            $table->string('approver_nik');
            $table->string('approver_name');
            $table->string('approver_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpdr_approvers');
    }
};
