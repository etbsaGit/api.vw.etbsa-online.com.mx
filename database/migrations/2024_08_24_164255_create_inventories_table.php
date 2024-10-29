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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();

            $table->string('serial_number', 191);
            $table->string('economical_number', 191);
            $table->string('inventory_number', 191);
            $table->string('invoice', 191);
            $table->date('invoice_date', 191);
            $table->integer('year');
            $table->string('p_r', 191)->nullable();
            $table->string('comments', 191)->nullable();

            $table->unsignedBigInteger('status_id')->onDelete('restrict');
            $table->foreign('status_id')->references('id')->on('statuses');

            $table->unsignedBigInteger('type_id')->onDelete('restrict');
            $table->foreign('type_id')->references('id')->on('types');

            $table->unsignedBigInteger('agency_id')->onDelete('restrict');
            $table->foreign('agency_id')->references('id')->on('agencies');

            $table->unsignedBigInteger('vehicle_id')->onDelete('restrict');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
