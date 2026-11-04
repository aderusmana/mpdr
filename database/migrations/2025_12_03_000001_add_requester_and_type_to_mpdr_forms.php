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
            if (!Schema::hasColumn('mpdr_forms', 'type_of_request')) {
                $table->string('type_of_request')->nullable()->after('product_name');
            }
            if (!Schema::hasColumn('mpdr_forms', 'requester_id')) {
                $table->unsignedBigInteger('requester_id')->nullable()->after('type_of_request');
            }
        });

        if (Schema::hasTable('users')) {
            try {
                Schema::table('mpdr_forms', function (Blueprint $table) {
                    $table->foreign('requester_id')->references('id')->on('users')->nullOnDelete();
                });
            } catch (\Throwable $e) {}
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('mpdr_forms', function (Blueprint $table) {
                $table->dropForeign(['requester_id']);
            });
        } catch (\Throwable $e) {}

        Schema::table('mpdr_forms', function (Blueprint $table) {
            if (Schema::hasColumn('mpdr_forms', 'requester_id')) {
                $table->dropColumn('requester_id');
            }
            if (Schema::hasColumn('mpdr_forms', 'type_of_request')) {
                $table->dropColumn('type_of_request');
            }
        });
    }
};
