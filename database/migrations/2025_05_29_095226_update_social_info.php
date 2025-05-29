<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home', function (Blueprint $table) {
            $table->string('youtube')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('landline')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('home', function (Blueprint $table) {
            $table->dropColumn('youtube');
            $table->dropColumn('facebook');
            $table->dropColumn('instagram');
            $table->dropColumn('tiktok');
            $table->dropColumn('email');
            $table->dropColumn('phone');
            $table->dropColumn('landline');
        });
    }
};