<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'chatsable_type',
        'chatsable_id',
    ];

    public function chatsable(): MorphTo
    {
        return $this->morphTo();
    }

    public function chat_users(): HasMany
    {
        return $this->hasMany(ChatUser::class, 'chat_id', 'id');
    }

    public function last_message(): HasOne {
        return $this->hasOne(Message::class)->latestOfMany();
    }
}
