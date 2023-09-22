<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *    schema="CollectionSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="collection_name", type="string", example="Тип продажи"),
 *       @OA\Property(property="value", type="string", example="Аренда"),
 *    )
 * )
 */
class Collection extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'collection_name',
        'value',
    ];
}
