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
        Schema::table('pre_mpdr_forms', function (Blueprint $table) {
            if (!Schema::hasColumn('pre_mpdr_forms', 'last_approver_direct_approval')) {
                $table->boolean('last_approver_direct_approval')->default(false)->after('route_to');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_mpdr_forms', function (Blueprint $table) {
            $table->dropColumn('last_approver_direct_approval');
        });
    }
};
