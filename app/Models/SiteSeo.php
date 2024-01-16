<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *    schema="SiteInfoSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="title", type="string", example="Купить или арендовать недвижимость"),
 *       @OA\Property(property="text", type="string", example="Если вы хотите купить недвижимость за рубежом или арендовать её — в каталоге LOGO более 170 тысяч объявлений со всего мира."),
 *       @OA\Property(property="created_at", type="datetime", example="2022-06-28 06:06:17"),
 *       @OA\Property(property="updated_at", type="datetime", example="2022-06-28 06:06:17"),
 *    )
 * )
 */
class SiteSeo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'text',
    ];
}
