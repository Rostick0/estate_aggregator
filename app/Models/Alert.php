<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *    schema="AlertSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="title", type="string", example="Новое уведомление"),
 *       @OA\Property(property="description", type="string", example="Описание"),
 *       @OA\Property(property="country_id", type="string", example="5"),
 *       @OA\Property(property="role", type="string", example="client"),
 *       @OA\Property(property="type", type="string", example="null"),
 *    )
 * )
 */
class Alert extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'country_id',
        'role',
        'type',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
}
