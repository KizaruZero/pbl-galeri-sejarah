<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $table = 'categories';
    protected $fillable = [
        'category_name',
        'category_description',
        'category_image',
        'slug',
    ];

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
