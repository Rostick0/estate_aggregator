<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
