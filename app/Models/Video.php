<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use HasFactory, SoftDeletes, Traits\UuidTrait;

    protected $fillable = [
        'id',
        'title',
        'description',
        'year_launched',
        'opened',
        'rating',
        'duration',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'year_launched' => 'integer',
        'opened' => 'boolean',
        'duration' => 'integer',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function castMember()
    {
        return $this->belongsToMany(CastMember::class);
    }

    public function video()
    {
        return $this->hasOne(Media::class)
            ->where('type', (string) MediaTypes::VIDEO->value);
    }

    public function trailer()
    {
        return $this->hasOne(Media::class)
            ->where('type', (string) MediaTypes::TRAILER->value);
    }

    public function banner()
    {
        return $this->hasOne(ImageVideo::class)
            ->where('type', (string) ImageTypes::BANNER->value);
    }

    public function thumb()
    {
        return $this->hasOne(ImageVideo::class)
            ->where('type', (string) ImageTypes::THUMB->value);
    }

    public function half()
    {
        return $this->hasOne(ImageVideo::class)
            ->where('type', (string) ImageTypes::THUMB_HALF->value);
    }
}
