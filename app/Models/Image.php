<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *    schema="ImageSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="name", type="string", example="Какое-то фото"),
 *       @OA\Property(property="width", type="string", example="100"),
 *       @OA\Property(property="height", type="string", example="100"),
 *       @OA\Property(property="path", type="string", example="http://site.com/url"),
 *       @OA\Property(property="type", type="string", example="png"),
 *       @OA\Property(property="user_id", type="number", example="1"),
 *       @OA\Property(property="created_at", type="datetime", example="2022-06-28 06:06:17"),
 *       @OA\Property(property="updated_at", type="datetime", example="2022-06-28 06:06:17"),
 *    )
 * )
 */
class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'width',
        'height',
        'path',
        'type',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
