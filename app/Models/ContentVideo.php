<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;


class ContentVideo extends Model
{
    use HasFactory, Searchable;

    protected $table = 'content_video';
    protected $with = 'metadataVideo';
    protected $searchableFields = ['title', 'description', 'note'];
    // protected $with = ['metadataVideo', 'userComments'];


    protected $fillable = [
        'title',
        'description',
        'note',
        'source',
        'category_id',
        'user_id',
        'video_url',
        'status',
        'approved_at',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function categoryContents()
    {
        return $this->hasMany(CategoryContent::class, 'content_video_id');
    }

    public function metadataVideo()
    {
        return $this->hasOne(MetadataVideo::class, 'content_video_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function userComments()
    {
        return $this->hasMany(UserComment::class, 'content_photo_id');
    }
    public function contentReactions()
    {
        return $this->hasMany(ContentReaction::class, 'content_video_id');
    }
    public function getTotalReactionsAttribute()
    {
        return $this->contentReactions()->count();
    }
    public function getTotalCommentsAttribute()
    {
        return $this->userComments()->count();
    }
}
