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
        Schema::table('pre_mpdr_approvers', function (Blueprint $table) {
            $table->integer('level')->after('approver_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_mpdr_approvers', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }
};
