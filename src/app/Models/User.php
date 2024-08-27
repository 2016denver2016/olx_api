<?php

namespace App\Models;

use App\Helpers\SoftDeletesHelper;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Laravel\Lumen\Auth\Authorizable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property int              $id
 * @property int              $status
 * @property int              $country_id
 * @property string           $email
 * @property string           $email_verification_code
 * @property string           $email_verified_at
 * @property string           $password
 * @property string           $password_recovery_token
 * @property string           $password_recovery_token_created_at
 * @property string           $created_at
 * @property string           $updated_at
 * @property string           $deleted_at
 * @property int              $created_by
 * @property int              $updated_by
 * @property int              $deleted_by
 *

 */
class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory, HasRoles, SoftDeletesHelper;

    public const USERS_REGISTER_LIMIT    = 200;

    public const STATUS_WAITING_APPROVAL = 0;
    public const STATUS_ACTIVE           = 1;
    public const STATUS_BLOCKED          = 10;

    public const ROLE_ADMIN     = 'admin';
    public const ROLE_MODERATOR = 'moderator';
    public const ROLE_USER      = 'user';
    public const ROLE_NOT_USER  = self::ROLE_ADMIN . '|' . self::ROLE_MODERATOR;

    public const PERMISSION_ALL         = 'all';
    public const PERMISSION_CREATE_USER = 'create user';
    public const PERMISSION_UPDATE_USER = 'update user';
    public const PERMISSION_DELETE_USER = 'delete user';
    public const PERMISSION_MODIFY_SELF = 'modify own entity'; // owner can modify any of his entity
    public const PERMISSION_CHAT        = 'chat';
    public const PERMISSION_MAKE_VIDEO  = 'make video';
    public const PERMISSION_FLOWK_VIDEO = 'flowk video';
    public const PERMISSION_FOLLOW      = 'follow';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'email_verification_code',
        'email_verified_at',
        'password',
        'password_recovery_token',
        'password_recovery_token_created_at',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'password_recovery_token',
        'password_recovery_token_created_at',
    ];

    public static function getStatuses(): array
    {
        return [
            self::STATUS_WAITING_APPROVAL => 'Waiting Approval',
            self::STATUS_ACTIVE           => 'Active',
            self::STATUS_BLOCKED          => 'Blocked',
        ];
    }

    public static function getRoles(): array
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_MODERATOR,
            self::ROLE_USER,
        ];
    }

    public static function getPermissions(): array
    {
        return [
            self::PERMISSION_ALL,
            self::PERMISSION_CREATE_USER,
            self::PERMISSION_UPDATE_USER,
            self::PERMISSION_DELETE_USER,
            self::PERMISSION_MODIFY_SELF,
            self::PERMISSION_CHAT,
            self::PERMISSION_MAKE_VIDEO,
            self::PERMISSION_FLOWK_VIDEO,
            self::PERMISSION_FOLLOW,
        ];
    }

    public static function getRolesPermissions(?string $role = null): array
    {
        $rolesPermissions = [
            self::ROLE_ADMIN     => self::getPermissions(),
            self::ROLE_MODERATOR => [
                self::PERMISSION_CREATE_USER,
                self::PERMISSION_UPDATE_USER,
                self::PERMISSION_DELETE_USER,
                self::PERMISSION_MODIFY_SELF,
                self::PERMISSION_CHAT,
                self::PERMISSION_MAKE_VIDEO,
                self::PERMISSION_FLOWK_VIDEO,
                self::PERMISSION_FOLLOW,
            ],
            self::ROLE_USER      => [
                self::PERMISSION_MODIFY_SELF,
                self::PERMISSION_CHAT,
                self::PERMISSION_MAKE_VIDEO,
                self::PERMISSION_FLOWK_VIDEO,
                self::PERMISSION_FOLLOW,
            ],
        ];

        return $role && array_key_exists($role, $rolesPermissions)
            ? $rolesPermissions[$role]
            : $rolesPermissions;
    }

    // Required Method from JWTSubject Interface
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // Required Method from JWTSubject Interface
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function generatePasswordResetToken(): string
    {
        return Str::random(32);
    }

    public function adverts(): HasMany
    {
        return $this->hasMany(Olx::class, 'user_id', 'id');
    }


}
