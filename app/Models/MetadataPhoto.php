<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ContentPhoto;
use App\Models\UserComment;

class MetadataPhoto extends Model
{
    use HasFactory;

    // Nama tabel yang terkait dengan model
    protected $table = 'metadata_photo';

    // Kolom yang dapat diisi (fillable)
    protected $fillable = [
        'collection_date',
        'file_size',
        'aperture',
        'location',
        'model',
        'ISO',
        'dimensions',
        'content_photo_id',
    ];

    public function contentPhoto()
    {
        return $this->belongsTo(ContentPhoto::class);
    }

    public function userComments()
    {
        return $this->hasMany(UserComment::class);
    }
}