<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserComment extends Model
{
    use HasFactory;
    protected $table = 'user_comments';

    protected $fillable = [
        'content',
        'user_id',
        'content_photo_id',
        'content_video_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contentPhoto()
    {
        return $this->belongsTo(ContentPhoto::class, 'content_photo_id');
    }

    public function metadataPhoto()
    {
        return $this->belongsTo(MetadataPhoto::class);
    }
    public function metadataVideo()
    {
        return $this->belongsTo(MetadataVideo::class);
    }
    public function contentVideo()
    {
        return $this->belongsTo(ContentVideo::class, 'content_video_id');
    }

    public function userReactions()
    {
        return $this->hasMany(UserReaction::class, 'comment_id');
    }
    public static function getTotalComment($content_id, $content_type)
    {
        return UserComment::where($content_type . '_id', $content_id)->count();
    }
}