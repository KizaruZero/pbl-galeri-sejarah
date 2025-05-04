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
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('bg_home')->nullable()->comment('Background image for home section');
            $table->string('bg_events')->nullable()->comment('Background image for events section');
            $table->string('bg_gallery')->nullable()->comment('Background image for gallery section');
            $table->string('bg_article')->nullable()->comment('Background image for article section');
            $table->string('logo')->nullable()->comment('Company logo');
            $table->string('cms_name')->nullable()->comment('CMS name displayed in hero section');
            $table->text('events_text')->nullable()->comment('Text for events section');
            $table->text('gallery_text')->nullable()->comment('Text for gallery section');
            $table->text('article_text')->nullable()->comment('Text for article section');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_profiles');
    }
};
