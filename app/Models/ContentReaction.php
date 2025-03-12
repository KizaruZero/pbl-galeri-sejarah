<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentReaction extends Model
{
    //
    protected $table = 'content_reactions';
    protected $with = 'reactionType';
    protected $fillable = [
        'user_id',
        'content_photo_id',
        'content_video_id',
        'reaction_type_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function contentPhoto()
    {
        return $this->belongsTo(ContentPhoto::class, 'content_photo_id');
    }
    public function contentVideo()
    {
        return $this->belongsTo(ContentVideo::class, 'content_video_id');
    }
    public function reactionType()
    {
        return $this->belongsTo(Reaction::class, 'reaction_type_id');
    }

    // protected static function booted()
    // {
    //     static::created(function ($contentReaction) {
    //         if ($contentReaction->contentPhoto) {
    //             $contentReaction->contentPhoto->updatePopularity();
    //         }
    //         if ($contentReaction->contentVideo) {
    //             $contentReaction->contentVideo->updatePopularity();
    //         }
    //     });

    //     static::deleted(function ($contentReaction) {
    //         if ($contentReaction->contentPhoto) {
    //             $contentReaction->contentPhoto->updatePopularity();
    //         }
    //         if ($contentReaction->contentVideo) {
    //             $contentReaction->contentVideo->updatePopularity();
    //         }
    //     });
    // }
}
