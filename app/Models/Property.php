<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 *    schema="PropertySchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="name", type="string", example="Расположение"),
 *       @OA\Property(property="status", type="string", example="active"),
 *    )
 * )
 */
class Property extends Model
{
    use HasFactory;
    public $timestamps = false;


    protected $fillable = [
        'name',
        'type',
        'is_active'
    ];

    public function property_values(): HasMany {
        return $this->hasMany(PropertyValue::class, 'property_id', 'id');
    }
}
