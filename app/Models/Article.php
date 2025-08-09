<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'user_id',
        'image_url',
        'status',
        'published_at',
        'total_views',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::created(function ($article) {
            // Generate sitemap when new article is created
            Artisan::call('sitemap:generate');
        });

        static::updated(function ($article) {
            // Generate sitemap when article is updated
            Artisan::call('sitemap:generate');
        });

        static::deleted(function ($article) {
            // Generate sitemap when article is deleted
            Artisan::call('sitemap:generate');
        });
    }

    /**
     * Get the author that owns the article.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function updateTotalViews()
    {
        $this->total_views++;
        $this->save();
    }



}
