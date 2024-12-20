<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *    schema="AlertUserSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="alert_id", type="number", example="Новое уведомление"),
 *       @OA\Property(property="recipient_id", type="number", example="1"),
 *       @OA\Property(property="is_read", type="boolean", example="0"),
 *       @OA\Property(property="send_at", type="datetime", example="2022-06-28 06:07:17"),
 *       @OA\Property(property="status", type="enum", enum={"active", "archive", "hidden"}),
 *       @OA\Property(property="created_at", type="datetime", example="2022-06-28 06:06:17"),
 *       @OA\Property(property="updated_at", type="datetime", example="2022-06-28 06:06:17"),  
 *  )
 * )
 */
class AlertUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'alert_id',
        'recipient_id',
        'is_read',
        'send_at',
        'status'
    ];

    public function alert(): BelongsTo
    {
        return $this->belongsTo(Alert::class);
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id', 'id');
    }
}
