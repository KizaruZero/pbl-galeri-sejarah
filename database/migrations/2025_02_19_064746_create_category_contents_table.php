<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('category_content', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement()->primary(); // UNSIGNED INT
            $table->unsignedSmallInteger('category_id');
            $table->unsignedInteger('content_photo_id')->nullable();
            $table->unsignedInteger('content_video_id')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('content_photo_id')->references('id')->on('content_photo')->onDelete('cascade');
            $table->foreign('content_video_id')->references('id')->on('content_video')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_contents');
    }
};
