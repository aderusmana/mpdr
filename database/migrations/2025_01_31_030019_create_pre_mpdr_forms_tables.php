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
        Schema::create('pre_mpdr_forms', function (Blueprint $table) {
            $table->id();
            $table->string('no');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('revision_id');
            $table->string('project_name');
            $table->string('brand_name');
            $table->string('level_priority');
            $table->string('status');
            $table->string('route_to');
            $table->timestamps();
            $table->foreign('revision_id')->references('id')->on('pre_mpdr_revisions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_mpdr_forms');
    }
};
