<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryContent extends Model
{
    protected $table = 'category_content';

    protected $fillable = [
        'category_id',
        'content_photo_id',
        'content_video_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function contentPhoto()
    {
        return $this->belongsTo(ContentPhoto::class);
    }

    public function contentVideo()
    {
        return $this->belongsTo(ContentVideo::class);
    }
}