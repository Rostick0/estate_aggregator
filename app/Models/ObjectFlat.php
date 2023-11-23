<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *    schema="ObjectFlatSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="name", type="string", example="апартаменты"),
 *       @OA\Property(property="type", type="string", example="квартира"),
 *       @OA\Property(property="created_at", type="datetime", example="2022-06-28 06:06:17"),
 *       @OA\Property(property="updated_at", type="datetime", example="2022-06-28 06:06:17"),
 *    )
 * )
 */
class ObjectFlat extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'type'
    ];
}
