<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class UserComment extends Model
{
    protected $table = 'user_comments';

    protected $fillable = [
        'content',
        'user_id',
        'content_photo_id',
        'content_video_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contentPhoto()
    {
        return $this->belongsTo(ContentPhoto::class, 'content_photo_id');
    }

    public function contentVideo()
    {
        return $this->belongsTo(ContentVideo::class, 'content_video_id');
    }
}