<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bg_home_1',
        'bg_home_2',
        'bg_home_3',
        'bg_events_1',
        'bg_events_2',
        'bg_events_3',
        'bg_gallery_1',
        'bg_gallery_2',
        'bg_gallery_3',
        'bg_article_1',
        'bg_article_2',
        'bg_article_3',
        'logo',
        'cms_name',
        'cms_email',
        'cms_phone',
        'cms_address',
        'events_text',
        'gallery_text',
        'article_text',
    ];

    /**
     * Get the URL for the home background image.
     */
    public function getBgHomeUrlAttribute()
    {
        return $this->bg_home ? asset('storage/' . $this->bg_home) : null;
    }

    /**
     * Get the URL for the events background image.
     */
    public function getBgEventsUrlAttribute()
    {
        return $this->bg_events ? asset('storage/' . $this->bg_events) : null;
    }

    /**
     * Get the URL for the gallery background image.
     */
    public function getBgGalleryUrlAttribute()
    {
        return $this->bg_gallery ? asset('storage/' . $this->bg_gallery) : null;
    }

    /**
     * Get the URL for the article background image.
     */
    public function getBgArticleUrlAttribute()
    {
        return $this->bg_article ? asset('storage/' . $this->bg_article) : null;
    }

    /**
     * Get the URL for the logo.
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'bg_home_url',
        'bg_events_url',
        'bg_gallery_url',
        'bg_article_url',
        'logo_url',
    ];
}
