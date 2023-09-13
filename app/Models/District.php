<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 *    schema="DistrictSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="name", type="string", example="Москва"),
 *       @OA\Property(property="importance", type="number", example=0),
 *       @OA\Property(property="region_id", type="number", example=1),
 *       @OA\Property(property="created_at", type="string", example="2022-06-28 06:06:17"),
 *       @OA\Property(property="updated_at", type="string", example="2022-06-28 06:06:17"),
 *    )
 * )
 */
class District extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'importance',
        'region_id'
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'id', 'region_id');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }
}
