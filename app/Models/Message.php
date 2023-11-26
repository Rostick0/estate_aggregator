<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @OA\Schema(
 *    schema="MessageSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="content", type="string", example="Гена лучший"),
 *       @OA\Property(property="is_read", type="boolean", example="false"),
 *       @OA\Property(property="chat_id", type="number", example="1"),
 *       @OA\Property(property="user_id", type="number", example="5"),
 *       @OA\Property(property="created_at", type="datetime", example="2022-06-28 06:06:17"),
 *       @OA\Property(property="updated_at", type="datetime", example="2022-06-28 06:06:17"),
 *    )
 * )
 */
class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'is_read',
        'chat_id',
        'user_id',
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'chat_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(ImageRelat::class, 'image_relatsable');
    }

    public function files(): MorphMany
    {
        return $this->morphMany(FileRelationship::class, 'file_relable');
    }
}
