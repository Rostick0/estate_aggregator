<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecruitmentFlat extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruitment_id',
        'flat_id',
    ];

    public function recruitment(): BelongsTo
    {
        return $this->belongsTo(Recruitment::class, 'recruitment_id', 'id');
    }

    public function flat(): BelongsTo
    {
        return $this->belongsTo(Flat::class, 'flat_id', 'id');
    }
}
