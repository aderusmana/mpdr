<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pre_mpdr_market_updates', function (Blueprint $table) {
            $table->text('potential_volume')->change();
        });

        Schema::table('pre_mpdr_approved_details', function (Blueprint $table) {
            $table->string('approver_position')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('pre_mpdr_approved_details', function (Blueprint $table) {
            $table->dropColumn('approver_position');
        });

        Schema::table('pre_mpdr_market_updates', function (Blueprint $table) {
            $table->float('potential_volume')->change();
        });
    }
};
