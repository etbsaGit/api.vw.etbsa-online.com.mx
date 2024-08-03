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
        Schema::create('sale_dates', function (Blueprint $table) {
            $table->id();

            $table->date('date')->nullable();

            $table->unsignedBigInteger('type_id')->onDelete('restrict');
            $table->foreign('type_id')->references('id')->on('types');

            $table->unsignedBigInteger('sale_id')->onDelete('restrict');
            $table->foreign('sale_id')->references('id')->on('sales');

            $table->string('comments', 191)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_dates');
    }
};
