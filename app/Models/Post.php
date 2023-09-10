<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'main_image_id',
        'user_id',
        'city_id',
        'rubric_id',
        'source',
        'count_view',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'id', 'type_id')->where('type', 'post');
    }

    // public function mainImage(): BelongsTo {
    //     return $this->belongsTo(Image::class, '')
    // }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function city(): BelongsTo {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function rubric(): BelongsTo {
        return $this->belongsTo(Rubric::class, 'rubric_id', 'id');
    }
}
