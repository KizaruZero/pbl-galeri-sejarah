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
        Schema::create('content_photo', function (Blueprint $table) {
            $table->id();  // Tetap BIGINT untuk kompatibilitas dengan foreign key
            $table->string('title', 150);  // Judul foto umumnya <150 karakter
            $table->string('slug', 160)->unique();  // Slug biasanya judul + hash (contoh: 150+10 karakter)
            $table->unsignedBigInteger('user_id');  // Tetap BIGINT (match dengan users.id)
            $table->string('image_url', 220);  // URL gambar umumnya <220 karakter (termasuk CDN path)
            $table->text('description')->nullable();  // Tetap TEXT untuk deskripsi panjang
            $table->string('source', 100);  // Sumber gambar (nama platform/organisasi)
            $table->string('alt_text', 120)->nullable();  // Teks alternatif (aksesibilitas)
            $table->text('note')->nullable();  // Tetap TEXT untuk catatan panjang
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');  // Ganti string ke ENUM
            $table->timestamp('approved_at')->nullable();  // Tetap TIMESTAMP
            $table->unsignedInteger('total_views')->default(0);  // Ganti INTEGER ke UNSIGNED (nilai tidak negatif)
            $table->timestamps();  // Tetap TIMESTAMP
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('content_photo');
    }
};
