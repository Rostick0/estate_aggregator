<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *    schema="ApplicationUserSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="role", type="enum", example="realtor"),
 *       @OA\Property(property="user_id", type="number", example=5),
 *       @OA\Property(property="created_at", type="datetime", example="2022-06-28 06:06:17"),
 *       @OA\Property(property="updated_at", type="datetime", example="2022-06-28 06:06:17"),
 *    )
 * )
 */
class ApplicationUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'role',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
