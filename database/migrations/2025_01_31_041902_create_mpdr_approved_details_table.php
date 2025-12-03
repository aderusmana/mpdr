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
        Schema::create('mpdr_approved_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id');
            $table->string('approver_nik');
            $table->string('approver_name');
            $table->string('status')->nullable();
            $table->text('comment')->nullable();
            $table->text('token')->nullable();
            $table->date('approved_date')->nullable();
            $table->timestamps();
            $table->foreign('form_id')->references('id')->on('mpdr_forms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpdr_approved_details');
    }
};
