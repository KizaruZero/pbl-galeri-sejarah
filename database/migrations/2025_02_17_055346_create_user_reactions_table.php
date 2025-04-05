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
        Schema::create('user_reactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id'); // Pengguna yang memberikan reaksi
            $table->unsignedInteger('comment_id'); // Komentar yang direaksi
            $table->unsignedTinyInteger('reaction_type_id'); // Jenis reaksi
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('comment_id')->references('id')->on('user_comments')->onDelete('cascade');
            $table->foreign('reaction_type_id')->references('id')->on('reactions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_reactions');
    }
};
