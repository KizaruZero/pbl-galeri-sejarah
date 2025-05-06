<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
