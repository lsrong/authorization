<?php


namespace Lson\Authorization\Database;


use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Base
{

    /**
     * The attributes that should be file for arrays.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug'
    ];

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('authorization.database.roles_table'));

        parent::__construct($attributes);
    }

    /**
     * User relationship
     *
     * @return BelongsToMany
     *
     * @author lsrong
     * @datetime 03/07/2020 14:51
     */
    public function users(): BelongsToMany
    {
        $pivot_table = config('authorization.database.role_users_table');

        $related_model = config('authorization.database.users_model');

        return $this->belongsToMany($related_model, $pivot_table, 'role_id', 'user_id');
    }

    /**
     * Permission relationship
     *
     * @return BelongsToMany
     *
     * @author lsrong
     * @datetime 03/07/2020 14:52
     */
    public function permissions():BelongsToMany
    {
        $pivot_table = config('authorization.database.role_permissions_table');

        $related_model = config('authorization.database.permissions_model');

        return $this->belongsToMany($related_model, $pivot_table, 'role_id', 'permission_id');
    }

    /**
     * Menu relationship
     *
     * @return BelongsToMany
     *
     * @author lsrong
     * @datetime 03/07/2020 14:54
     */
    public function menus():BelongsToMany
    {
        $pivot_table = config('authorization.database.role_menu_table');

        $related_model = config('authorization.database.menu_model');

        return $this->belongsToMany($related_model, $pivot_table, 'role_id', 'menu_id');
    }


    /**
     * Delete users,permission,menu relationship
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
            // users
            $model->users()->detach();

            // permissions
            $model->permissions()->detach();

            // menus
            $model->menus()->detach();
        });
    }


}