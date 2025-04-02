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
            $table->id();
            $table->date('collection_date');
            $table->unsignedInteger('file_size');
            $table->string('aperture', 10)->nullable();
            // $table->string('tag', 50)->nullable();
            $table->string('location', 100)->nullable();
            $table->string('model', 50)->nullable();
            $table->string('ISO', 10)->nullable();
            $table->string('dimensions', 12)->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('content_photo_id');
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
