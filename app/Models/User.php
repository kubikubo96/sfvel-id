<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property int id
 * @property string name
 * @property string email
 * @property Carbon email_verified_at
 * @property string avatar
 * @property string password
 * @property bool status
 * @property string remember_token
 * @property int branch_id
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property $getRoleNameAttribute
 * @property $getAbilityAttribute
 * @property $branch
 */
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'username', 'password', 'admin', 'active', 'email', 'phone_number', 'first_name', 'last_name', 'remember_token',
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
        'active' => 'boolean',
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
        return [
            'exp' => time() + 30 * 86400, // 1 tháng
        ];
    }

    /**
     * Get Ability.
     */
    protected function Ability(): Attribute
    {
        $ability = [];
        $permissions = $this->getAllPermissions();
        if (! $permissions->isEmpty()) {
            $names = $permissions->pluck('name');
            if ($names) {
                foreach ($names as $name) {
                    $nameExplode = explode(' ', $name);
                    if (count($nameExplode) == 2) {
                        $ability[] = [
                            'action' => strtolower($nameExplode[0]),
                            'subject' => strtolower($nameExplode[1]),
                        ];
                    }
                }
            }
        }

        return Attribute::make(get: fn () => $ability);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }
}
