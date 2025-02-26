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
        Schema::create('content_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('content_photo_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('content_video_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('reaction_type_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->foreign('content_photo_id')->references('id')->on('content_photo')->onDelete('cascade');
            $table->foreign('content_video_id')->references('id')->on('content_video')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reaction_type_id')->references('id')->on('reactions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_reactions');
    }
};
