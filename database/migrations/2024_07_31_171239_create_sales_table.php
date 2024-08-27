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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('id_sale', 191)->unique();
            $table->string('comments', 191)->nullable();

            $table->unsignedBigInteger('inventory_id')->onDelete('restrict');
            $table->foreign('inventory_id')->references('id')->on('inventories');

            $table->unsignedBigInteger('status_id')->onDelete('restrict');
            $table->foreign('status_id')->references('id')->on('statuses');

            $table->unsignedBigInteger('sales_channel_id')->onDelete('restrict');
            $table->foreign('sales_channel_id')->references('id')->on('types');

            $table->unsignedBigInteger('type_id')->onDelete('restrict');
            $table->foreign('type_id')->references('id')->on('types');

            $table->unsignedBigInteger('agency_id')->onDelete('restrict');
            $table->foreign('agency_id')->references('id')->on('agencies');

            $table->unsignedBigInteger('customer_id')->onDelete('restrict');
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->unsignedBigInteger('employee_id')->onDelete('restrict');
            $table->foreign('employee_id')->references('id')->on('employees');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
