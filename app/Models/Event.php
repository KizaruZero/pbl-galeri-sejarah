<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class Event extends Model
{
    //
    protected $fillable = [
        'title',
        'slug',
        'description',
        'date_start',
        'date_end',
        'instagram_url',
        'youtube_url',
        'website_url',
        'contact_person',
        'location',
        'google_maps_url',
        'image_url',
    ];

    protected static function booted()
    {
        static::created(function ($event) {
            // Generate sitemap when new event is created
            Artisan::call('sitemap:generate');
        });

        static::updated(function ($event) {
            // Generate sitemap when event is updated
            Artisan::call('sitemap:generate');
        });

        static::deleted(function ($event) {
            // Generate sitemap when event is deleted
            Artisan::call('sitemap:generate');
        });
    }
}
