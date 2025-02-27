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
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('user_id');
            $table->string('image_url');
            $table->text('description')->nullable();
            $table->string('source');
            $table->string('alt_text')->nullable();
            $table->text('note')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->integer('popularity')->default(0);
            $table->timestamps();
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
