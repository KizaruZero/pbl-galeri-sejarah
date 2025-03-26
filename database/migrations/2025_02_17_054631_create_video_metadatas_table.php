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
        Schema::create('metadata_video', function (Blueprint $table) {
            $table->id(); // Kept as BIGINT for compatibility
            $table->string('location', 100)->nullable(); // Reduced from 255 to 100 (specific locations)
            $table->unsignedInteger('file_size'); // Changed from BIGINT to INTEGER (4 bytes, supports up to 4GB files)
            $table->string('frame_rate', 10)->nullable(); // Reduced to 10 (e.g., "30fps", "59.94fps")
            $table->string('resolution', 12)->nullable(); // Reduced to 12 (e.g., "1920x1080", "3840x2160")
            $table->string('duration', 8)->nullable(); // Reduced to 8 (HH:MM:SS format)
            $table->string('format_file', 10)->nullable(); // Reduced to 10 (e.g., "MP4", "MOV", "AVCHD")
            $table->string('tag', 50)->nullable(); // Reduced from 255 to 50 for tags
            $table->string('codec_video_audio', 30)->nullable(); // Reduced to 30 (e.g., "H.264/AAC", "HEVC/Opus")
            $table->date('collection_date'); // Unchanged (optimal for dates)
            $table->timestamps(); // Standard Laravel timestamps
            $table->unsignedBigInteger('content_video_id'); // Matches content_video.id
            $table->foreign('content_video_id')
                ->references('id')
                ->on('content_video')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metadata_video');
    }
};
