<?php


namespace Lson\Authorization\Database;


use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Base
{
    /**
     * The attributes that should be file for arrays.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'http_method', 'http_path',
    ];

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('authorization.database.permissions_table'));

        parent::__construct($attributes);
    }

    /**
     * Transform to string when store record
     *
     * @param $http_method
     *
     * @author lsrong
     * @datetime 03/07/2020 14:29
     */
    public function setHttpMethodAttribute($http_method): void
    {
        if (is_array($http_method)) {
            $this->attributes['http_method'] = implode(',', $http_method);
        }
    }

    /**
     * Transform to array when store record
     *
     * @param $http_method
     * @return  array
     *
     * @author lsrong
     * @datetime 03/07/2020 14:30
     */
    public function getHttpMethodAttribute($http_method): array
    {
        if(is_string($http_method)){
            return array_filter(explode(',', $http_method));
        }

        return (array) $http_method;
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
        $pivot_table = config('authorization.database.role_permissions_table');

        $related_model = config('authorization.database.roles_model');

        return $this->belongsToMany($related_model, $pivot_table, 'permission_id', 'role_id');
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