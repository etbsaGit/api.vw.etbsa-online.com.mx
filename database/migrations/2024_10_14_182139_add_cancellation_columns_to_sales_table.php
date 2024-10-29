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
        Schema::table('sales', function (Blueprint $table) {
            $table->string('cancellation_reason')->nullable(); // Cambiado de cancel_feedback a cancellation_reason
            $table->boolean('cancel')->default(false);
            $table->string('cancellation_folio')->nullable();
            $table->date('cancellation_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('cancellation_reason'); // Eliminar la columna cancellation_reason
            $table->dropColumn('cancel');
            $table->dropColumn('cancellation_folio');
            $table->dropColumn('cancellation_date');
        });
    }
};
