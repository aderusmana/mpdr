<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mpdr_market_updates', function (Blueprint $table) {
            $table->text('potential_volume')->change();
        });
    }

    public function down(): void
    {
        Schema::table('mpdr_market_updates', function (Blueprint $table) {
            $table->float('potential_volume')->change();
        });
    }
};
