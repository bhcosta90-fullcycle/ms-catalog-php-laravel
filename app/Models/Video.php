<?php

namespace App\Models;

use App\Enums\ImageTypes;
use App\Enums\MediaTypes;
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
            ->where('type', MediaTypes::VIDEO->value);
    }

    public function trailer()
    {
        return $this->hasOne(Media::class)
            ->where('type', MediaTypes::TRAILER->value);
    }

    public function banner()
    {
        return $this->hasOne(Image::class)
            ->where('type', ImageTypes::BANNER->value);
    }

    public function thumb()
    {
        return $this->hasOne(Image::class)
            ->where('type', ImageTypes::THUMB->value);
    }

    public function half()
    {
        return $this->hasOne(Image::class)
            ->where('type', ImageTypes::THUMB_HALF->value);
    }
}
