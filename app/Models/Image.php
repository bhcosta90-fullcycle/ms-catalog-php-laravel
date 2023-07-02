<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'images_video';

    protected $fillable = [
        'path',
        'type',
    ];

    protected $casts = [
        'type' => 'integer',
    ];

    private function video()
    {
        return $this->belongsTo(Video::class);
    }
}
