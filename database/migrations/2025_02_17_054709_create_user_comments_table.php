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
        Schema::create('user_comments', function (Blueprint $table) {
            $table->id();
            $table->text('content'); // Isi komentar
            $table->unsignedBigInteger('user_id'); // User yang membuat komentar
            $table->unsignedBigInteger('content_photo_id')->nullable(); // ID dari model yang dikomentari
            $table->unsignedBigInteger('content_video_id')->nullable(); // Tipe model yang dikomentari
            $table->string('status')->default('published');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('content_photo_id')->references('id')->on('content_photo')->onDelete('cascade');
            $table->foreign('content_video_id')->references('id')->on('content_video')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_comments');
    }
};
