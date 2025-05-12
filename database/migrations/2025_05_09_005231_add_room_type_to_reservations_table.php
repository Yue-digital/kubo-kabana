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
        Schema::table('rooms', function (Blueprint $table) {
            $table->integer('lean_weekday_price')->default(0);
            $table->integer('lean_weekend_price')->default(0);
            $table->integer('peak_weekday_price')->default(0);
            $table->integer('peak_weekend_price')->default(0);
            $table->dropColumn('price');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('lean_weekday_price');
            $table->dropColumn('lean_weekend_price');
            $table->dropColumn('peak_weekday_price');
            $table->dropColumn('peak_weekend_price');
        });
    }
};
