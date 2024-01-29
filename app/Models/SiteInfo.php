<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *    schema="SiteInfoSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="title", type="string", example="Заголовок"),
 *       @OA\Property(property="text", type="string", example="Текст"),
 *       @OA\Property(property="key", type="string", example="Ключ"),
 *       @OA\Property(property="type", type="string", example="select"),
 *       @OA\Property(property="deleted_at", type="datetime", example="NULL"),
 *       @OA\Property(property="created_at", type="datetime", example="2022-06-28 06:06:17"),
 *       @OA\Property(property="updated_at", type="datetime", example="2022-06-28 06:06:17"),
 *    )
 * )
 */
class SiteInfo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'text',
        'key',
        'type'
    ];
}
