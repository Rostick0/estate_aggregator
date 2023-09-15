<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *    schema="ApplicationFlatSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="flat_id", type="number", example=1),
 *       @OA\Property(property="is_information", type="boolean", example="0"),
 *       @OA\Property(property="is_viewing", type="boolean", example="1"),
 *       @OA\Property(property="name", type="string", example="Олег"),
 *       @OA\Property(property="phone", type="string", example="+79999999"),
 *       @OA\Property(property="email", type="string", example="email@mail.ru"),
 *       @OA\Property(property="text", type="string", example="Есть вопрос по поводу осмотра"),
 *       @OA\Property(property="messager_type", type="string", example="telegram"),
 *       @OA\Property(property="created_at", type="string", example="2022-06-28 06:06:17"),
 *       @OA\Property(property="updated_at", type="string", example="2022-06-28 06:06:17"),
 *    )
 * )
 */
class ApplicationFlat extends Model
{
    use HasFactory;

    protected $fillable = [
        'flat_id',
        'is_information',
        'is_viewing',
        'name',
        'phone',
        'email',
        'text',
        'messager_type',
    ];

    public function flat(): BelongsTo {
        return $this->belongsTo(Flat::class, 'flat_id', 'id');
    }
}
