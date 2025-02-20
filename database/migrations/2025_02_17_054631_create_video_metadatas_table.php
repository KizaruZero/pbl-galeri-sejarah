<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('metadata_video', function (Blueprint $table) {
            $table->id(); // Kolom id_metadata_video sebagai primary key
            $table->string('location')->nullable(); // Lokasi pengambilan video
            $table->unsignedBigInteger('file_size'); // Ukuran file dalam byte
            $table->string('frame_rate')->nullable(); // Frame rate video (misal: 30fps)
            $table->string('resolution')->nullable(); // Resolusi video (misal: 1920x1080)
            $table->string('duration')->nullable(); // Durasi video (misal: 00:05:30)
            $table->string('format_file')->nullable(); // Format file video (misal: MP4, AVI)
            $table->string('tag')->nullable(); // Tag video
            $table->string('codec_video_audio')->nullable(); // Codec video dan audio (misal: H.264, AAC)
            $table->date('collection_date'); // Tanggal pengambilan video
            $table->timestamps(); // Kolom created_at dan updated_at
            $table->unsignedBigInteger('content_video_id'); // Kolom content_video_id sebagai foreign key

            $table->foreign('content_video_id')->references('id')->on('content_video')->onDelete('cascade'); // Menambahkan foreign key ke kolom content_video_id yang merujuk ke kolom id pada tabel content_video

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
