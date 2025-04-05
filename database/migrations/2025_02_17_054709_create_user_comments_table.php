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
            $table->unsignedInteger('id')->autoIncrement()->primary(); // UNSIGNED INT + PK + AI
            $table->text('content');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('content_photo_id')->nullable();
            $table->unsignedInteger('content_video_id')->nullable();
            $table->enum('status', ['published', 'hidden', 'deleted'])->default('published');
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('content_photo_id')
                ->references('id')
                ->on('content_photo')
                ->onDelete('cascade');
            $table->foreign('content_video_id')
                ->references('id')
                ->on('content_video')
                ->onDelete('cascade');
            $table->index(['content_photo_id', 'content_video_id']);
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
