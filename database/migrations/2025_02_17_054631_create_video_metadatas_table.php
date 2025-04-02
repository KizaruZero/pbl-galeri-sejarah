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
            $table->id();
            $table->string('location', 100)->nullable();
            $table->unsignedInteger('file_size');
            $table->string('frame_rate', 10)->nullable();
            $table->string('resolution', 12)->nullable();
            $table->string('duration', 8)->nullable();
            $table->string('format_file', 10)->nullable();
            // $table->string('tag', 50)->nullable();
            $table->string('codec_video_audio', 30)->nullable();
            $table->date('collection_date');
            $table->timestamps();
            $table->unsignedBigInteger('content_video_id');
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
