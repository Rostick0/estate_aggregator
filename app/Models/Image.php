<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *    schema="ImageSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="type_id", type="number", example=1),
 *       @OA\Property(property="type", type="string", example="photo"),
 *       @OA\Property(property="name", type="string", example="Какое-то фото"),
 *       @OA\Property(property="path", type="number", example="http://site.com/url"),
 *       @OA\Property(property="width", type="number", example=100),
 *       @OA\Property(property="height", type="number", example=100),
 *       @OA\Property(property="created_at", type="string", example="2022-06-28 06:06:17"),
 *       @OA\Property(property="updated_at", type="string", example="2022-06-28 06:06:17"),
 *    )
 * )
 */
class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'type',
        'name',
        'path',
        'width',
        'height',
    ];
}
