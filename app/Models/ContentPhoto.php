<?php
namespace App\Models;

use App\Filament\Resources\ContentPhotoResource;
use App\Traits\CalculatesPopularity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;
use App\Models\Category;
use App\Models\MetadataPhoto;
use App\Models\User;
use App\Models\UserComment;


class ContentPhoto extends Model
{
    use HasFactory, Searchable, CalculatesPopularity;
    protected $searchableFields = ['title', 'slug', 'description', 'note', 'alt_text'];
    protected $table = 'content_photo';
    protected $with = 'metadataPhoto';

    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'user_id',
        'description',
        'source',
        'alt_text',
        'note',
        'image_url',
        'status',
        'approved_at',
        'total_views',
    ];

    protected static function booted()
    {
        static::created(function ($contentPhoto) {
            app()->call([ContentPhotoResource::class, 'getHourlyUploadedContent']);
        });
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function metadataPhoto()
    {
        return $this->hasOne(MetadataPhoto::class, 'content_photo_id');
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
        return $this->hasMany(ContentReaction::class, 'content_photo_id');
    }
    public function userFavorite()
    {
        return $this->hasMany(UserFavorite::class, 'content_photo_id');
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
    public function categoryContents()
    {
        return $this->hasMany(CategoryContent::class, 'content_photo_id');
    }
    public function updateTotalViews()
    {
        $this->total_views++;
        $this->save();
        $this->calculatePopularity();
    }

}
