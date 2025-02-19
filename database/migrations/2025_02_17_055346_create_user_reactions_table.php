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
        Schema::create('user_reactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comment_id'); // Komentar yang direaksi
            $table->unsignedBigInteger('reaction_type_id'); // Jenis reaksi
            $table->timestamps();

            // Foreign keys
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
