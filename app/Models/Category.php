<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Artisan;

class Category extends Model
{
    use HasFactory;
    //
    protected $table = 'categories';
    protected $fillable = [
        'category_name',
        'category_description',
        'category_image',
        'slug',
    ];

    protected static function booted()
    {
        static::created(function ($category) {
            // Generate sitemap when new category is created
            Artisan::call('sitemap:generate');
        });

        static::updated(function ($category) {
            // Generate sitemap when category is updated
            Artisan::call('sitemap:generate');
        });

        static::deleted(function ($category) {
            // Generate sitemap when category is deleted
            Artisan::call('sitemap:generate');
        });
    }

    public function categoryContents()
    {
        return $this->hasMany(CategoryContent::class);
    }

    public function contentPhotos()
    {
        return $this->hasMany(ContentPhoto::class);
    }

    public function contentVideos()
    {
        return $this->hasMany(ContentVideo::class);
    }
}
