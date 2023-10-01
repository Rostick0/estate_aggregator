<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlatProperty extends Model
{
    use HasFactory;

    protected $fillable = [
        'value_enum',
        'value',
        'flat_id',
        'property_value_id',
    ];

    public function property_value(): BelongsTo
    {
        return $this->belongsTo(PropertyValue::class, 'property_value_id', 'id');
    }
}
