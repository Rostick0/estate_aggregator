<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *    schema="ApplicationCompanySchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="user_id", type="number", example=1),
 *       @OA\Property(property="phone", type="number", example=2),
 *       @OA\Property(property="status", type="enum", enum={"confirm", "pending", "hidden"}),
 *       @OA\Property(property="created_at", type="datetime", example="2022-06-28 06:06:17"),
 *       @OA\Property(property="updated_at", type="datetime", example="2022-06-28 06:06:17"),
 *    )
 * )
 */
class ApplicationCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
