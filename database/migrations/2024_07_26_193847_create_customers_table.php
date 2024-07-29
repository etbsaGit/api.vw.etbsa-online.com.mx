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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->integer('id_customer')->nullable();
            $table->string('name', 191);
            $table->string('rfc', 191)->unique()->nullable();
            $table->string('curp', 191)->nullable()->unique();
            $table->string('phone', 191)->unique();
            $table->string('landline', 191)->unique()->nullable();
            $table->string('email', 191)->unique()->nullable();

            $table->string('street', 191)->nullable();
            $table->string('district', 191)->nullable();
            $table->integer('zip_code')->nullable();

            $table->unsignedBigInteger('municipality_id')->nullable();
            $table->foreign('municipality_id')->references('id')->on('municipalities')->onDelete('restrict');

            $table->unsignedBigInteger('state_id')->nullable();
            $table->foreign('state_id')->references('id')->on('states')->onDelete('restrict');

            $table->unsignedBigInteger('type_id')->onDelete('restrict');
            $table->foreign('type_id')->references('id')->on('types');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
