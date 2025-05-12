<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_number');
            $table->string('email');
            $table->dateTime('check_in');
            $table->dateTime('check_out');
            $table->integer('number_of_kubos');
            $table->boolean('bonfire_usage');
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}; 