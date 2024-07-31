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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 191);
            $table->string('middle_name', 191)->nullable();
            $table->string('paternal_surname', 191);
            $table->string('maternal_surname', 191);
            $table->string('rfc', 191)->unique();

            $table->unsignedBigInteger('agency_id')->onDelete('restrict');
            $table->foreign('agency_id')->references('id')->on('agencies');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
