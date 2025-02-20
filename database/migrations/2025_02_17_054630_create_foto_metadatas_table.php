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
        Schema::create('metadata_photo', function (Blueprint $table) {
            $table->id(); // Kolom id otomatis
            $table->date('collection_date'); // Tanggal pengambilan foto
            $table->unsignedBigInteger('file_size'); // Ukuran file dalam byte
            $table->string('aperture')->nullable(); // Aperture (bukaan diafragma)
            $table->string('tag')->nullable(); // Tag foto
            $table->string('location')->nullable(); // Lokasi pengambilan foto
            $table->string('model')->nullable(); // Model kamera
            $table->string('ISO')->nullable(); // ISO
            $table->string('dimensions')->nullable(); // Dimensi foto (misal: 1920x1080)
            $table->timestamps(); // Kolom created_at dan updated_at
            $table->unsignedBigInteger('content_photo_id'); // Kolom  sebagai foreign key

            $table->foreign('content_photo_id')->references('id')->on('content_photo')->onDelete('cascade'); // Menambahkan foreign key ke kolom content_photo_id yang merujuk ke kolom id pada tabel content_photo

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
