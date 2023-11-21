<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *    schema="CompanySchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="banner", type="number", example="1"),
 *       @OA\Property(property="is_reliable", type="boolean", example="1"),
 *       @OA\Property(property="created_at", type="string", example="2022-06-28 06:06:17"),
 *       @OA\Property(property="updated_at", type="string", example="2022-06-28 06:06:17"),
 *    )
 * )
 */
class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'banner',
        'is_reliable',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'company_id')->whereIn('role', ['agency', 'builder']);
    }

    public function staffs(): HasMany
    {
        return $this->hasMany(User::class, 'company_id', 'id')->where('role', 'realtor');
    }
}
