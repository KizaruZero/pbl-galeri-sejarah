<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetadataVideo extends Model
{
    use HasFactory;

    // Nama tabel yang terkait dengan model
    protected $table = 'metadata_video';

    // Kolom yang dapat diisi (fillable)
    protected $fillable = [
        'location',
        'file_size',
        'frame_rate',
        'resolution',
        'duration',
        'format_file',
        'tag',
        'codec_video_audio',
        'collection_date',
        'content_video_id',
    ];

    // Kolom yang harus di-cast ke tipe data tertentu
    protected $casts = [
        'collection_date' => 'date', // Cast kolom collection_date ke tipe date
    ];

    public function contentVideo()
    {
        return $this->belongsTo(ContentVideo::class);
    }

    public function userComments()
    {
        return $this->hasMany(UserComment::class);
    }
}