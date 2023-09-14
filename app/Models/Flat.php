<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function flat_properties(): HasMany
    {
        return $this->hasMany(FlatProperty::class, 'id', 'flat_id');
    }

    public function object(): BelongsTo
    {
        return $this->belongsTo(ObjectFlat::class, 'object_id', 'id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(FlatType::class, 'type_id', 'id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    public function square_land_unit(): BelongsTo
    {
        return $this->belongsTo(SquareLandUnit::class, 'square_land_unit', 'id');
    }

    public function building_type(): BelongsTo
    {
        return $this->belongsTo(BuildingType::class, 'building_type', 'id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'contact_id', 'id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'id', 'type_id')->where('type', 'flat');
    }

    public function main_image(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'main_image_id', 'id');
    }

    // return $this->belongsTo(Region::class, 'region_id', 'id');
}
