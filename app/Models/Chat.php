<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @OA\Schema(
 *    schema="ChatSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="chatsable_type", type="string", example="App\Models\Recruitment"),
 *       @OA\Property(property="chatsable_id", type="number", example="1"),
 *       @OA\Property(property="created_at", type="datetime", example="2022-06-28 06:06:17"),
 *       @OA\Property(property="updated_at", type="datetime", example="2022-06-28 06:06:17"),
 *    )
 * )
 */
class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'chatsable_type',
        'chatsable_id',
        'last_message_created_at'
    ];

    public function chatsable(): MorphTo
    {
        return $this->morphTo();
    }

    public function flat() {
        if ($this->first()->chatsable_type === 'App\Models\Flat') {
            return $this->belongsTo(Flat::class, 'chatsable_id', 'id');
        }

        return $this->belongsTo(Flat::class, 'chatsable_id', 'id')->where('id', -1);
    }

    public function recruitment() {
        if ($this->value('chatsable_type') === 'App\Models\Recruitment'){
            return $this->belongsTo(Recruitment::class, 'chatsable_id', 'id');
        }

        return $this->belongsTo(Recruitment::class, 'chatsable_id', 'id')->where('id', -1);
    }

    public function chat_users(): HasMany
    {
        return $this->hasMany(ChatUser::class);
    }

    public function interlocutor()
    {
        return $this->hasOne(ChatUser::class, 'chat_id', 'id')->where('user_id', '!=', auth()->id());
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }

    public function last_message(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }
}
