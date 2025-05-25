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
        Schema::create('reactions', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->autoIncrement()->primary(); // UNSIGNED TINYINT (0-255)
            $table->string('react_type', 10); // Nama reaksi (e.g., like, dislike, love)
            $table->string('icon', 50); // Icon reaksi (e.g., like, dislike, love)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reactions');
    }
};
