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
            $table->unsignedInteger('id')->autoIncrement()->primary(); // UNSIGNED INT + PK + AI
            $table->string('title', 150);
            $table->string('slug', 160)->unique();
            $table->unsignedInteger('user_id');
            $table->string('image_url', 100);
            $table->string('description', 255)->nullable();
            $table->string('source', 50);
            $table->string('alt_text', 100)->nullable();
            $table->string('tag', 75)->nullable();
            $table->string('note', 50)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->unsignedInteger('total_views')->default(0);
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
