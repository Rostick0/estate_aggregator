<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *    schema="FileSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="name", type="string", example="Какое-то фото"),
 *       @OA\Property(property="path", type="number", example="http://site.com/url"),
 *       @OA\Property(property="type", type="string", example="image/png"),
 *       @OA\Property(property="created_at", type="string", example="2022-06-28 06:06:17"),
 *       @OA\Property(property="updated_at", type="string", example="2022-06-28 06:06:17"),
 *    )
 * )
 */
class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'type',
        'user_id',
    ];
}
