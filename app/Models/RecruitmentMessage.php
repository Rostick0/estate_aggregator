<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecruitmentMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruitment_chat_id',
        'user_id',
        'content'
    ];

    public function recruitment_chat(): BelongsTo
    {
        return $this->belongsTo(RecruitmentChat::class, 'recruitment_chat_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
