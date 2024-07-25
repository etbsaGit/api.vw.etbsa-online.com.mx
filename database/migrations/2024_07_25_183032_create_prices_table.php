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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();

            $table->decimal('price', 12, 2);

            $table->unsignedBigInteger('vehicle_id')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');

            $table->unsignedBigInteger('type_id')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('types');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
