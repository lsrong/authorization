<?php


namespace Lson\Authorization\Database;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Menu extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['parent_id', 'order', 'title', 'icon', 'uri'];


    public function __construct(array $attributes = [])
    {
        $this->setTable(config('authorization.database.menu_table'));

        parent::__construct($attributes);
    }

    /**
     * Roles relationship
     *
     * @return BelongsToMany
     *
     * @author lsrong
     * @datetime 03/07/2020 14:35
     */
    public function roles(): BelongsToMany
    {
        $pivot_table = config('authorization.database.role_menu_table');

        $related_model = config('authorization.database.roles_model');

        return $this->belongsToMany($related_model, $pivot_table, 'menu_id', 'role_id');
    }

    /**
     * Delete roles relationship
     *
     * @author lsrong
     * @datetime 03/07/2020 14:36
     */
    public static function boot():void
    {
        parent::boot();

        static::deleting(static function ($model) {
            /** @var $model self*/
            $model->roles()->detach();
        });
    }
}
