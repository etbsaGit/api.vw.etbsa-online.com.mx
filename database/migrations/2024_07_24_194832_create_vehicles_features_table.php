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
        Schema::create('vehicles_features', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('vehicle_id')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');

            $table->unsignedBigInteger('feature_id')->onDelete('cascade');
            $table->foreign('feature_id')->references('id')->on('features');

            $table->string('value', 191);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles_features');
    }
};
