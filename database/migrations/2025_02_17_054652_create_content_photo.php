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
        Schema::create('content_photo', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('metadata_photo_id');
            $table->text('description')->nullable();
            $table->string('source');
            $table->string('alt_text')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->foreign('metadata_photo_id')->references('id')->on('metadata_photo')->onDelete('cascade');
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
