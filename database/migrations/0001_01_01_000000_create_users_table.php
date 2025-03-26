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
        Schema::create('users', function (Blueprint $table) {
            $table->id();  // Tetap gunakan `BIGINT` (untuk kompatibilitas Laravel)
            $table->string('google_id', 30)->nullable();  // Google ID biasanya 21-30 karakter
            $table->string('name', 100);  // Nama umumnya <100 karakter
            $table->string('email', 120)->unique();  // Email maksimal 120 karakter (cukup untuk "nama@domain.xxx")
            $table->timestamp('email_verified_at')->nullable();  // Tetap pakai `timestamp`
            $table->string('password', 60);  // Hash password (bcrypt) selalu 60 karakter
            $table->string('role')->default('member');
            $table->string('photo_profile', 200)->nullable();  // URL foto biasanya <200 karakter
            $table->rememberToken();  // Tetap `VARCHAR(100)` (default Laravel)
            $table->timestamps();  // Tetap pakai `timestamp`
        });



        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
