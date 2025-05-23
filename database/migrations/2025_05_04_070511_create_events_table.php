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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('description');
            $table->datetime('date_start');
            $table->datetime('date_end');
            $table->string('instagram_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('website_url')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('location');
            $table->string('google_maps_url')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
