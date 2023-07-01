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
        'rating' => 'integer',
        'duration' => 'integer',
    ];
}
