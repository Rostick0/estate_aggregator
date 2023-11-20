<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ColRelat extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id'
    ];

    public function collect_relatsable(): MorphTo
    {
        return $this->morphTo();
    }

    public function collection(): BelongsTo {
        return $this->belongsTo(Collection::class, 'collection_id', 'id');
    }
}
