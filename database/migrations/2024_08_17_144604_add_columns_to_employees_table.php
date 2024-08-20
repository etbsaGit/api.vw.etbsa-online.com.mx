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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('sales_key', 191)->unique()->nullable();
            $table->string('phone', 191)->unique();
            $table->string('picture', 191)->unique()->nullable();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');

            $table->unsignedBigInteger('type_id')->nullable();
            $table->foreign('type_id')->references('id')->on('types')->onDelete('restrict');

            $table->unsignedBigInteger('position_id')->nullable();
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            $table->dropForeign(['type_id']);
            $table->dropColumn('type_id');

            $table->dropForeign(['position_id']);
            $table->dropColumn('position_id');

            $table->dropUnique(['sales_key']);
            $table->dropColumn('sales_key');

            $table->dropUnique(['phone']);
            $table->dropColumn('phone');

            $table->dropUnique(['picture']);
            $table->dropColumn('picture');
        });
    }

};
