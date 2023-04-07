<?php

namespace Users;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Permissions\Permission;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const USER_ROLE_ADMIN     = 'admin';
    const USER_ROLE_MEMBER    = 'member';

    const USER_ROLES = [
        self::USER_ROLE_ADMIN,
        self::USER_ROLE_MEMBER,
    ];

    const STATUS_PENDING_PASSWORD = 'pending_password';
    const STATUS_ACTIVE           = 'active';
    const STATUS_BLOCKED          = 'blocked';

    const USER_STATUS = [
        self::STATUS_PENDING_PASSWORD,
        self::STATUS_ACTIVE,
        self::STATUS_BLOCKED,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'permission_id',
        'name',
        'last_name',
        'email',
        'phone',
        'password',
        'role',
        'status',
        'document',
        'registration',
        'zipcode',
        'state',
        'city',
        'neighborhood',
        'street',
        'number',
        'complement',
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
    ];

    /**
     * @param $roleToCheck
     * @return bool
     */
    public function checkRole($roleToCheck)
    {
        return $this->attributes['role'] === $roleToCheck;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
