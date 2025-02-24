<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Searchable;


class ContentVideo extends Model
{
    use HasFactory, Searchable;

    protected $table = 'content_video';
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
        return $this->hasMany(UserComment::class);
    }

    // public static function searchWithMetadata($searchTerm)
    // {
    //     return static::with(['category', 'metadataVideo', 'user'])
    //         ->where(function ($query) use ($searchTerm) {
    //             $query->search($searchTerm)
    //                 ->orWhereHas('metadataVideo', function ($q) use ($searchTerm) {
    //                     $q->whereRaw('MATCH(location, tag) AGAINST(? IN BOOLEAN MODE)', [$searchTerm]);
    //                 });
    //         });
    // }
}
