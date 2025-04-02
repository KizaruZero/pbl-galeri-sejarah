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
        Schema::create('content_video', function (Blueprint $table) {
            $table->id();
            $table->string('title', 120);
            $table->string('slug', 130)->unique();
            $table->string('video_url', 220)->nullable();
            $table->string('thumbnail', 220);
            $table->string('link_youtube', 50)->nullable();
            $table->text('description')->nullable();
            $table->text('note')->nullable();
            $table->string('source', 50);
            $table->string('tag', 50)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->unsignedInteger('total_views')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_video');
    }
};
