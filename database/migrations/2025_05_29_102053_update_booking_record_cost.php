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
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('additional_adults')->default(0);
            $table->integer('additional_children')->default(0);
            $table->integer('additional_pets')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('additional_adults');
            $table->dropColumn('additional_children');
            $table->dropColumn('additional_pets');
        });
    }
};
