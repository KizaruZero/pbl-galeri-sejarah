<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ContentPhoto;
use App\Models\ContentVideo;
use App\Models\User;

class UserFavorite extends Model
{
    //
    protected $fillable = [
        'user_id',
        'content_photo_id',
        'content_video_id',
    ];


    public function User()
    {
        return $this->belongsTo(User::class);
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
