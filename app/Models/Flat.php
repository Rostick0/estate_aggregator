<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Flat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'object_id',
        'type_id',
        'country_id',
        'district_id',
        'district',
        'address',
        'longitude',
        'latitude',
        'currency_id',
        'price',
        'price_per_meter',
        'price_day',
        'price_week',
        'price_month',
        'not_show_price',
        'rooms',
        'bedrooms',
        'bathrooms',
        'square',
        'square_land',
        'square_land_unit',
        'floor',
        'total_floor',
        'building_type',
        'building_date',
        'contact_id',
        'specialtxt',
        'description',
        'filename',
        'tour_link',
    ];

    public function flat_properties(): HasMany {
        return $this->hasMany(FlatProperty::class, 'id', 'flat_id');
    }
}
