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
        Schema::create('content_video', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('video_url');
            $table->text('description')->nullable();
            $table->text('note')->nullable();
            $table->string('source');
            $table->string('status')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_video');
    }
};
