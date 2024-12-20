<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @OA\Schema(
 *    schema="UserSchema",
 *       @OA\Property(property="id", type="number", example=1),
 *       @OA\Property(property="name", type="string", example="Jonh"),
 *       @OA\Property(property="email", type="string", example="john@test.com"),
 *       @OA\Property(property="email_verified_at", type="number", example=0),
 *       @OA\Property(property="image_id", type="number", example="1"),
 *       @OA\Property(property="role", type="string", example="admin"),
 *       @OA\Property(property="phone", type="string", example="799999999"),
 *       @OA\Property(property="country_id", type="number", example="5"),
 *       @OA\Property(property="is_confirm", type="boolean", example="1"),
 *       @OA\Property(property="type_social", type="string", example="telegram"),
 *       @OA\Property(property="raiting_awe", type="float", example="5"),
 *       @OA\Property(property="raiting", type="float", example="0"),
 *       @OA\Property(property="about", type="string", example="Обо мне"),
 *       @OA\Property(property="work_experience", type="float", example="1"),
 *       @OA\Property(property="company_id", type="number", example="1"),
 *       @OA\Property(property="created_at", type="string", example="2022-06-28 06:06:17"),
 *       @OA\Property(property="updated_at", type="string", example="2022-06-28 06:06:17"),
 *    )
 * )
 */
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'phone',
        'role',
        'image_id',
        'country_id',
        'is_confirm',
        'type_social',
        'raiting_awe',
        'raiting',
        'about',
        'work_experience',
        'company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(UserContacts::class, 'user_id', 'id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function alert(): HasMany
    {
        return $this->hasMany(AlertUser::class, 'recipient_id', 'id');
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'image_id', 'id');
    }

    public function flat_owners(): HasMany
    {
        return $this->hasMany(FlatOwner::class, 'user_id', 'id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function collection_relats(): MorphMany
    {
        return $this->morphMany(ColRelat::class, 'col_relatsable');
    }

    public function recruitments(): HasMany
    {
        return $this->hasMany(Recruitment::class, 'user_id', 'id');
    }

    // public function owner(): BelongsTo
    // {
    //     return $this->belongsTo(User::class, 'id', 'company_id')->whereIn('role', ['agency', 'builder']);
    // }

    // public function staffs(): HasMany
    // {
    //     return $this->hasMany(User::class, 'company_id', 'id')->where('role', 'realtor');
    // }
}
