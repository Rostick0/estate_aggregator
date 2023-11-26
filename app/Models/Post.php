<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *    schema="PostSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="title", type="string", example="Почему надо продавать"),
 *       @OA\Property(property="content", type="string", example="Да потому что это принесет вам прибыль"),
 *       @OA\Property(property="user_id", type="number", example=1),
 *       @OA\Property(property="district_id", type="number", example=1702),
 *       @OA\Property(property="rubric_id", type="number", example=1),
 *       @OA\Property(property="source", type="string", example="<a href="">источник<a>"),
 *       @OA\Property(property="count_view", type="number", example=100),
 *       @OA\Property(property="created_at", type="string", example="2022-06-28 06:06:17"),
 *       @OA\Property(property="updated_at", type="string", example="2022-06-28 06:06:17"),
 *    )
 * )
 */
class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'main_image_id',
        'user_id',
        'district_id',
        'rubric_id',
        'source',
        'count_view',
    ];

    public function files(): MorphMany
    {
        return $this->morphMany(FileRelationship::class, 'file_relable');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(ImageRelat::class, 'image_relatsable');
    }

    public function main_image(): BelongsTo
    {
        return $this->belongsTo(ImageRelat::class, 'main_image_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function rubric(): BelongsTo
    {
        return $this->belongsTo(Rubric::class, 'rubric_id', 'id');
    }
}
