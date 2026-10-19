<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mpdr_forms', function (Blueprint $table) {
            $table->boolean('gm_direct_approval')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('mpdr_forms', function (Blueprint $table) {
            $table->dropColumn('gm_direct_approval');
        });
    }
};
