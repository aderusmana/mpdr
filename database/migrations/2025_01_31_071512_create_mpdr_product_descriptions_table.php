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
        Schema::create('mpdr_product_descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id');
            $table->text('product_description');
            $table->text('usage_description');
            $table->string('storage_temperature');
            $table->string('delivery_temperature');
            $table->timestamps();
            $table->foreign('form_id')->references('id')->on('mpdr_forms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mpdr_product_descriptions');
    }
};
