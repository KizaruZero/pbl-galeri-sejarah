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
        Schema::create('categories', function (Blueprint $table) {
            $table->unsignedSmallInteger('id')->autoIncrement()->primary(); // UNSIGNED TINYINT (0-255)
            $table->string('category_name', 50);
            $table->string('slug', 50)->unique();
            $table->string('category_description', 255)->nullable();
            $table->string('category_image', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
