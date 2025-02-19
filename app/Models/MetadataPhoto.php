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
        'photo',
        'aperture',
        'tag',
        'location',
        'model',
        'ISO',
        'dimensions',
    ];
}