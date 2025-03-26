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
            $table->id();  // Kept as BIGINT for compatibility
            $table->string('title', 120);  // Reduced from 255 to 120
            $table->string('slug', 130)->unique();  // Reduced from 255 to 130
            $table->string('video_url', 220)->nullable();  // Reduced from 255 to 220
            $table->string('thumbnail', 220);  // Reduced from 255 to 220
            $table->string('link_youtube', 50)->nullable();  // YouTube links need only 43 chars
            $table->text('description')->nullable();  // Kept as TEXT
            $table->text('note')->nullable();  // Kept as TEXT
            $table->string('source', 50);  // Reduced from 255 to 50
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');  // Changed to ENUM
            $table->timestamp('approved_at')->nullable();  // Unchanged
            $table->unsignedInteger('total_views')->default(0);  // Changed to UNSIGNED
            $table->unsignedBigInteger('user_id');  // Unchanged
            $table->timestamps();  // Unchanged
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
