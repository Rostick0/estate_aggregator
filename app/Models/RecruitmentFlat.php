<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *    schema="RecruitmentFlatSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="recruitment_id", type="number", example="1"),
 *       @OA\Property(property="flat_id", type="number", example="1"),
 *       @OA\Property(property="created_at", type="string", example="2022-06-28 06:06:17"),
 *       @OA\Property(property="updated_at", type="string", example="2022-06-28 06:06:17"),
 *    )
 * )
 */
class RecruitmentFlat extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruitment_id',
        'flat_id',
    ];

    public function recruitment(): BelongsTo
    {
        return $this->belongsTo(Recruitment::class, 'recruitment_id', 'id');
    }

    public function flat(): BelongsTo
    {
        return $this->belongsTo(Flat::class, 'flat_id', 'id');
    }
}
