<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlertUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'alert_id',
        'user_id',
        'is_read',
    ];

    public function alert(): BelongsTo
    {
        return $this->belongsTo(Alert::class, 'alert_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
