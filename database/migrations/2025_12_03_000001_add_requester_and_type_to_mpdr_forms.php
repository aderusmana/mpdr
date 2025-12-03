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
        Schema::table('mpdr_forms', function (Blueprint $table) {
            $table->string('type_of_request')->nullable()->after('product_name');
            $table->unsignedBigInteger('requester_id')->nullable()->after('type_of_request');
            $table->foreign('requester_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mpdr_forms', function (Blueprint $table) {
            $table->dropForeign(['requester_id']);
            $table->dropColumn(['requester_id', 'type_of_request']);
        });
    }
};
