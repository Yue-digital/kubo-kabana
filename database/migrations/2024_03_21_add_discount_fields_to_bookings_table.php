<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('original_amount', 10, 2)->nullable()->after('total');
            $table->decimal('discount_amount', 10, 2)->nullable()->after('original_amount');
            $table->string('discount_code')->nullable()->after('discount_amount');
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['original_amount', 'discount_amount', 'discount_code']);
        });
    }
}; 