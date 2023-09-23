<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *    schema="BuildingTypeSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="name", type="string", example="Новый дом"),
 *    )
 * )
 */
class BuildingType extends Model
{
    use HasFactory;

    public $timestamps = false;
}
