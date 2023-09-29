<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @OA\Schema(
 *    schema="FlatSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="object_id", type="number", example=1),
 *       @OA\Property(property="type_id", type="number", example=1),
 *       @OA\Property(property="country_id", type="number", example=5),
 *       @OA\Property(property="district_id", type="number", example=1702),
 *       @OA\Property(property="district", type="string", example="Редкий город"),
 *       @OA\Property(property="address", type="string", example="Пушкин 1"),
 *       @OA\Property(property="longitude", type="string", example="31.31"),
 *       @OA\Property(property="latitude", type="string", example="79.01"),
 *       @OA\Property(property="currency_id", type="number", example=1),
 *       @OA\Property(property="price", type="number", example=100000),
 *       @OA\Property(property="price_per_meter", type="number", example=1),
 *       @OA\Property(property="price_day", type="number", example=3000),
 *       @OA\Property(property="price_week", type="number", example=10000),
 *       @OA\Property(property="price_month", type="number", example=30000),
 *       @OA\Property(property="not_show_price", type="boolean", example=0),
 *       @OA\Property(property="rooms", type="number", example=2),
 *       @OA\Property(property="bedrooms", type="number", example=1),
 *       @OA\Property(property="bathrooms", type="number", example=1),
 *       @OA\Property(property="square", type="number", example=10),
 *       @OA\Property(property="square_land", type="number", example=0),
 *       @OA\Property(property="square_land_unit", type="number", example=1),
 *       @OA\Property(property="floor", type="number", example=10),
 *       @OA\Property(property="total_floor", type="number", example=10),
 *       @OA\Property(property="building_type", type="number", example=1),
 *       @OA\Property(property="building_date", type="string", example="15.09.2023"),
 *       @OA\Property(property="contact_id", type="number", example=1),
 *       @OA\Property(property="specialtxt", type="string", example="Я сео текст походу"),
 *       @OA\Property(property="description", type="string", example="Описание, которые мы заслужили"),
 *       @OA\Property(property="filename", type="string", example="https://www.youtube.com/watch?v=4KZ2GeRWs1g"),
 *       @OA\Property(property="tour_link", type="string", example="https://my.matterport.com/show/?m=aPjao3BZ25x"),
 *       @OA\Property(property="created_at", type="string", example="2022-06-28 06:06:17"),
 *       @OA\Property(property="updated_at", type="string", example="2022-06-28 06:06:17"),
 *    )
 * )
 */
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
        return $this->hasMany(FlatProperty::class, 'flat_id', 'id');
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contact_id', 'id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'type_id', 'id')->where('type', 'flat');
    }

    public function files(): MorphMany
    {
        return $this->morphMany(FileRelationship::class, 'file_relable');
    }
}
