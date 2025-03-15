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
            $table->id();

            // Judul Artikel
            $table->string('title');

            // Slug untuk URL
            $table->string('slug')->unique();

            // Konten Artikel
            $table->longText('content');

            // Penulis Artikel (Foreign Key ke tabel users)
            $table->unsignedBigInteger('user_id');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('image_url')->nullable();

            $table->string('thumbnail_url')->nullable();

            // Status Artikel (draft, published, archived)
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');

            // Tanggal Publikasi
            $table->timestamp('published_at')->nullable();

            // Jumlah Views
            $table->integer('total_views')->default(0);

            // Timestamps
            $table->timestamps();
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
