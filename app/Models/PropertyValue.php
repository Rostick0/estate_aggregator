<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *    schema="PropertyValueSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="name", type="string", example="У моря"),
 *       @OA\Property(property="property_id", type="number", example=1),
 *    )
 * )
 */
class PropertyValue extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'property_id'
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }
}
