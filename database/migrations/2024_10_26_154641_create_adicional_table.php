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
        Schema::create('additionals', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ejemplo de columna 'nombre'
            $table->text('description')->nullable(); // Columna 'descripcion' opcional
            $table->decimal('price', 12, 2);
            $table->decimal('cost', 12, 2);
            $table->unsignedBigInteger('quote_id')->onDelete('cascade');
            $table->foreign('quote_id')->references('id')->on('quotes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additionals');
    }
};
