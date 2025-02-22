<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentPhoto extends Model
{
    use HasFactory;

    protected $table = 'content_photo';

    protected $fillable = [
        'title',
        'category_id',
        'user_id',
        'description',
        'source',
        'alt_text',
        'note',
        'image_url',
        'status',
        'approved_at',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function metadataPhoto()
    {
        return $this->belongsTo(MetadataPhoto::class, 'metadata_photo_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function userComments()
    {
        return $this->hasMany(UserComment::class);
    }

    public function categoryContents()
    {
        return $this->hasMany(CategoryContent::class, 'content_photo_id');
    }
}
