<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'tag',
        'location',
        'model',
        'ISO',
        'dimensions',
        'content_photo_id',
    ];

    public function contentPhoto ()
    {
        return $this->belongsTo(ContentPhoto::class);
    }
}