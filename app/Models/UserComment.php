<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class UserComment extends Model
{
    protected $table = 'user_comments';

    protected $fillable = [
        'content',
        'user_id',
        'content_photo',
        'content_video',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photo()
    {
        return $this->belongsTo(ContentPhoto::class, 'content_photo');
    }

    public function video()
    {
        return $this->belongsTo(ContentVideo::class, 'content_video');
    }
}