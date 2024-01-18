<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @OA\Schema(
 *    schema="TicketSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="email", type="string", example="email@mail.ru"),
 *       @OA\Property(property="phone", type="string", example="+79999999"),
 *       @OA\Property(property="full_name", type="string", example="Олег"),
 *       @OA\Property(property="text", type="string", example="Есть вопрос по поводу осмотра"),
 *       @OA\Property(property="communiction_method", type="string", example="telegram"),
 *       @OA\Property(property="purpose", type="записаться на просмотр", example="flat"),
 *       @OA\Property(property="link_from", type="string", example="http://92.63.179.235:3002/"),
 *       @OA\Property(property="ticket_type_cid", type="number", example="8"),
 *       @OA\Property(property="status_cid", type="number", example="4"),
 *       @OA\Property(property="created_at", type="datetime", example="2022-06-28 06:06:17"),
 *       @OA\Property(property="updated_at", type="datetime", example="2022-06-28 06:06:17"),
 *    )
 * )
 */
class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'phone',
        'full_name',
        'text',
        'communiction_method',
        'purpose',
        'link_from',
        'ticket_type_cid',
        'status_cid',
        'flat_id',
    ];

    public function ticket_type(): BelongsTo
    {
        return $this->belongsTo(Collection::class, 'ticket_type_cid', 'id')->where('type', 'ticket_types');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Collection::class, 'status_cid', 'id')->where('type', 'ticket_statuses');
    }

    public function flat(): BelongsTo
    {
        return $this->belongsTo(Flat::class);
    }
}
