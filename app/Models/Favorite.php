<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *    schema="FavoriteSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="flat_id", type="string", example=1),
 *       @OA\Property(property="user_id", type="number", example=1),
 *       @OA\Property(property="created_at", type="string", example="2022-06-28 06:06:17"),
 *       @OA\Property(property="updated_at", type="string", example="2022-06-28 06:06:17"),
 *    )
 * )
 */
class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'flat_id',
        'user_id',
    ];

    public function flat(): BelongsTo
    {
        return $this->belongsTo(Flat::class, 'flat_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
