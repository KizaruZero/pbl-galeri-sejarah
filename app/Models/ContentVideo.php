<?php  
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentVideo extends Model
{
    use HasFactory;

    protected $table = 'content_video';

    protected $fillable = [
        'title',
        'description',
        'note',
        'source',
        'category_id',
        'user_id',
        'video_url',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function metadataVideo()
    {
        return $this->belongsTo(MetadataVideo::class, 'metadata_video_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
