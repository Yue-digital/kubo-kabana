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
            $table->integer('min_guest')->nullable();
            $table->integer('max_guest')->nullable();
            $table->integer('max_child')->nullable();
            $table->integer('max_child_age')->nullable();
            $table->integer('additional_bedding_cost')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('min_guest');
            $table->dropColumn('max_guest');
            $table->dropColumn('max_child');
            $table->dropColumn('max_child_age');
            $table->dropColumn('additional_bedding_cost');
        });
    }
}; 