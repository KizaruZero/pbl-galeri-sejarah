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
            $table->string('bg_home_1')->nullable()->comment('Background image for home section');
            $table->string('bg_home_2')->nullable()->comment('Background image for home section');
            $table->string('bg_home_3')->nullable()->comment('Background image for home section');
            $table->string('bg_events_1')->nullable()->comment('Background image for events section');
            $table->string('bg_events_2')->nullable()->comment('Background image for events section');
            $table->string('bg_events_3')->nullable()->comment('Background image for events section');
            $table->string('bg_gallery_1')->nullable()->comment('Background image for gallery section');
            $table->string('bg_gallery_2')->nullable()->comment('Background image for gallery section');
            $table->string('bg_gallery_3')->nullable()->comment('Background image for gallery section');
            $table->string('bg_article_1')->nullable()->comment('Background image for article section');
            $table->string('bg_article_2')->nullable()->comment('Background image for article section');
            $table->string('bg_article_3')->nullable()->comment('Background image for article section');
            $table->string('logo')->nullable()->comment('Company logo');
            $table->string('cms_name')->nullable()->comment('CMS name displayed in hero section');
            $table->string('cms_email')->nullable()->comment('CMS email displayed in hero section');
            $table->string('cms_phone')->nullable()->comment('CMS phone displayed in hero section');
            $table->string('cms_address')->nullable()->comment('CMS address displayed in hero section');
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
