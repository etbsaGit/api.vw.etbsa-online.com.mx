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
        Schema::create('failed_sales', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('follow_up_id')->nullable();
            $table->foreign('follow_up_id')->references('id')->on('follow_ups')->onDelete('restrict');

            $table->unsignedBigInteger('type_id')->nullable();
            $table->foreign('type_id')->references('id')->on('types')->onDelete('restrict');

            $table->string('comments', 191);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('failed_sales');
    }
};
