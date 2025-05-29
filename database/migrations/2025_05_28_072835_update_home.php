<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home', function (Blueprint $table) {
            $table->longText('feature_content')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('home', function (Blueprint $table) {
            $table->dropColumn('featur_content');
        });
    }
};