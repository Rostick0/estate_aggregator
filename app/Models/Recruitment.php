<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @OA\Schema(
 *    schema="RecruitmentSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="name", type="string", example="Jonh"),
 *       @OA\Property(property="user_id", type="number", example=1),
 *       @OA\Property(property="key", type="string", example="3c35gd5gbm5"),
 *       @OA\Property(property="created_at", type="string", example="2022-06-28 06:06:17"),
 *       @OA\Property(property="updated_at", type="string", example="2022-06-28 06:06:17"),
 *    )
 * )
 */
class Recruitment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'key',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function recruitment_flats(): HasMany
    {
        return $this->hasMany(RecruitmentFlat::class, 'recruitment_id', 'id');
    }

    public function chat(): MorphMany
    {
        return $this->morphMany(Chat::class, 'chatsable');
    }
}
