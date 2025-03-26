<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            // Primary Key
            $table->id(); // Kept as BIGINT for compatibility
            // Article Identification
            $table->string('title', 120); // Reduced from 255 to 120 (most titles <100 chars)
            $table->string('slug', 130)->unique(); // Reduced from 255 to 130 (title + 10 char hash)
            // Content
            $table->longText('content'); // Kept as LONGTEXT for full articles
            // Relationships
            $table->unsignedBigInteger('user_id'); // Matches users.id
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            // Media URLs
            $table->string('image_url', 220)->nullable(); // Reduced from 255 to 220
            $table->string('thumbnail_url', 220)->nullable(); // Reduced from 255 to 220
            // Publication Status
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            // Timestamps
            $table->timestamp('published_at')->nullable()->index(); // Added index for sorting
            $table->unsignedInteger('total_views')->default(0); // Changed to UNSIGNED
            $table->timestamps();
            // Additional Indexes
            $table->index('status');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
