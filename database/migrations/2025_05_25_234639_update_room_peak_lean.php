<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->date('lean_season_start')->nullable();
            $table->date('lean_season_end')->nullable();
            $table->date('peak_season_start')->nullable();
            $table->date('peak_season_end')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('lean_season_start');
            $table->dropColumn('lean_season_end');
            $table->dropColumn('peak_season_start');
            $table->dropColumn('peak_season_end');
        });
    }
}; 