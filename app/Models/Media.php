<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'medias_video';

    protected $fillable = [
        'file_path',
        'encoded_path',
        'media_status',
        'type',
    ];

    protected $casts = [
        'media_status' => 'integer',
        'type' => 'integer',
    ];

    private function video()
    {
        return $this->belongsTo(Video::class);
    }
}
