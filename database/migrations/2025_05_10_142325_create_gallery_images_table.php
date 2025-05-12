<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalleryImagesTable extends Migration
{
    public function up()
    {
        Schema::create('gallery_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id')->nullable(); // If you want to relate images to rooms
            $table->string('image_path');
            $table->string('thumbnail_path')->nullable();
            $table->string('caption')->nullable();
            $table->timestamps();

            // If you have a rooms table and want to relate images to rooms:
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('gallery_images');
    }
}