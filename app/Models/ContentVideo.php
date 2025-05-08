<?php
namespace App\Models;

use App\Filament\Resources\ContentVideoResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;
use App\Traits\CalculatesPopularity;


class ContentVideo extends Model
{
    use HasFactory, Searchable, CalculatesPopularity;

    protected $table = 'content_video';
    protected $with = 'metadataVideo';
    protected $searchableFields = ['title', 'slug', 'description', 'note'];
    // protected $with = ['metadataVideo', 'userComments'];


    protected $fillable = [
        'title',
        'slug',
        'description',
        'note',
        'source',
        'tag',
        'category_id',
        'user_id',
        'video_url',
        'link_youtube',
        'thumbnail',
        'status',
        'approved_at',
        'total_views',
    ];

    protected static function booted()
    {
        static::created(function ($contentPhoto) {
            app()->call([ContentVideoResource::class, 'getHourlyUploadedContent']);
        });

        static::saved(function ($contentVideo) {
            if (request()->has('categories')) {
                // Delete existing category relationships
                $contentVideo->categoryContents()->delete();
                
                // Create new category relationships
                foreach (request()->input('categories') as $categoryId) {
                    $contentVideo->categoryContents()->create([
                        'category_id' => $categoryId,
                        'content_photo_id' => $contentVideo->id
                    ]);
                }
            }
        });
    }
    protected $attributes = [
        'video_url' => '', // default empty string instead of null
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
        return $this->hasMany(UserComment::class, 'content_photo_id')
            ->where('status', 'published');
    }
    public function contentReactions()
    {
        return $this->hasMany(ContentReaction::class, 'content_video_id');
    }
    public function userFavorite()
    {
        return $this->hasMany(UserFavorite::class, 'content_video_id');
    }
    public function getTotalReactionsAttribute()
    {
        return $this->contentReactions()->count();
    }
    public function getTotalCommentsAttribute()
    {
        return $this->userComments()->count();
    }
    public function getUserFavoritesAttribute()
    {
        return $this->userFavorite()->count();
    }

    public function updateTotalViews()
    {
        $this->total_views++;
        $this->save();
        $this->calculatePopularity();
    }
}
