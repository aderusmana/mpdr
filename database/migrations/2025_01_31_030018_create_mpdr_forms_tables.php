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
        Schema::create('mpdr_forms', function (Blueprint $table) {
            $table->id();
            $table->string('no');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('revision_id');
            $table->string('product_name');
            $table->string('level_priority');
            $table->string('initiator');
            $table->string('status');
            $table->timestamps();
            $table->foreign('revision_id')->references('id')->on('mpdr_revisions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpdr_forms');
    }
};
