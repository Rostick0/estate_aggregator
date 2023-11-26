<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *    schema="ChatUserSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="chat_id", type="number", example="2"),
 *       @OA\Property(property="user_id", type="number", example="5"),
 *       @OA\Property(property="is_favorite", type="boolean", example="false"),
 *       @OA\Property(property="created_at", type="datetime", example="2022-06-28 06:06:17"),
 *       @OA\Property(property="updated_at", type="datetime", example="2022-06-28 06:06:17"),
 *    )
 * )
 */
class ChatUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'user_id',
        'is_favorite',
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'chat_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
