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
        Schema::create('metadata_photo', function (Blueprint $table) {
            $table->id(); // Kept as BIGINT for compatibility
            $table->date('collection_date'); // Unchanged - ideal for dates
            $table->unsignedInteger('file_size'); // Changed from BIGINT to INTEGER (files >4GB rare)
            $table->string('aperture', 10)->nullable(); // Reduced from 255 to 10 (e.g., "f/2.8")
            $table->string('tag', 50)->nullable(); // Reduced from 255 to 50 for tags
            $table->string('location', 100)->nullable(); // Reduced from 255 to 100
            $table->string('model', 50)->nullable(); // Reduced from 255 to 50 (camera models)
            $table->string('ISO', 10)->nullable(); // Reduced from 255 to 10 (e.g., "ISO-400")
            $table->string('dimensions', 12)->nullable(); // Reduced to 12 (e.g., "1920x1080")
            $table->timestamps(); // Unchanged
            $table->unsignedBigInteger('content_photo_id'); // Matches content_photo.id
            $table->foreign('content_photo_id')
                ->references('id')
                ->on('content_photo')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metadata_photo');
    }
};
