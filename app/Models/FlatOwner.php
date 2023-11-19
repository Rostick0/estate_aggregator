<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlatOwner extends Model
{
    use HasFactory;

    protected $fillable = [
        'flat_id',
        'user_id',
    ];

    public function flat(): BelongsTo
    {
        return $this->belongsTo(Flat::class, 'flat_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
