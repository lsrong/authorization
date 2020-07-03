<?php
namespace Lson\Authorization\Database;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticateContract;
use Illuminate\Auth\Authenticatable;

class User extends Base implements JWTSubject,AuthenticateContract
{
    use Authenticatable;

    /**
     * The attributes that should be file for arrays.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'name', 'avatar', 'remember_token',
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('authorization.database.users_table'));

        parent::__construct($attributes);
    }

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
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * Roles relationship
     *
     * @return BelongsToMany
     *
     * @author lsrong
     * @datetime 03/07/2020 17:52
     */
    public function roles(): BelongsToMany
    {
        $pivot_table = config('authorization.database.role_users_table');

        $related_model = config('authorization.database.roles_model');

        return $this->belongsToMany($related_model, $pivot_table, 'user_id', 'role_id');
    }

    public function permissions()
    {
        $pivot_table = config('authorization.database.user_permissions_table');

        $related_model = config('authorization.database.permissions_model');

        return $this->belongsToMany($related_model, $pivot_table, 'user_id', 'permission_id');
    }

    public function menus()
    {
        $pivot_table = config('authorization.database.role_menu_table');

        $related_model = config('authorization.database.menu_model');

        return $this->belongsToMany($related_model, $pivot_table, 'user_id', 'menu_id');
    }

    /**
     * Delete role,permission,menu relationship
     *
     * @author lsrong
     * @datetime 03/07/2020 14:36
     */
    public static function boot():void
    {
        parent::boot();

        static::deleting(static function ($model) {
            /**
             * @var $model self
             */
            // roles
            $model->roles()->detach();

            // permissions
            $model->permissions()->detach();

            // menus
            $model->menus()->detach();
        });
    }
}