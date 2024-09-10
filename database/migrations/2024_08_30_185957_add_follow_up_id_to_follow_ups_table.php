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
        Schema::table('follow_ups', function (Blueprint $table) {
            $table->unsignedBigInteger('follow_up_id')->nullable();
            $table->foreign('follow_up_id')->references('id')->on('follow_ups')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('follow_ups', function (Blueprint $table) {
            $table->dropForeign(['follow_up_id']);
            $table->dropColumn('follow_up_id');
        });
    }
};
