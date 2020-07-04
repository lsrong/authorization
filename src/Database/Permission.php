<?php


namespace Lson\Authorization\Database;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
        if (is_string($http_method)) {
            return array_filter(explode(',', $http_method));
        }

        return (array) $http_method;
    }

    /**
     * filter \r.
     *
     * @param string $path
     *
     * @return mixed
     */
    public function getHttpPathAttribute($path)
    {
        return str_replace("\r\n", "\n", $path);
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
     * Check user permission
     *
     * @param Request $request
     * @return bool
     *
     * @author lsrong
     * @datetime 04/07/2020 11:29
     */
    public function isThrough($request):bool
    {
        if (empty($this->http_method) && empty($this->http_path)) {
            return false;
        }

        // Build match permission http collect
        $method = array_filter($this->http_method);
        $prefix = trim(config('authorization.prefix'), '/');
        $prefix = $prefix ? '/' . $prefix . '/' : '/';
        $matches = Collection::make(explode("\n", $this->http_path))
            ->filter()
            ->map(static function ($path) use ($method, $prefix) {
                $path = $prefix . trim($path, '/');

                return compact('method', 'path');
            });

        if ($matches->isEmpty()) {
            return false;
        }

        // Match request
        foreach ($matches as $match) {
            if ($this->matchRequest($match, $request)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Match request
     *
     * @param array $match  ['path', 'match']
     * @param Request $request
     * @return bool
     *
     * @author lsrong
     * @datetime 04/07/2020 12:18
     */
    protected function matchRequest($match, $request): bool
    {
        $path = $match['path'] !== '/' ? trim($match['path'], '/') : '/';

        if (!$request->is($path)) {
            return false;
        }

        if (empty($match['method'])) {
            return true;
        }

        return Collection::make($match['method'])->map(static function ($method) {
            return Str::upper($method);
        })->contains($request->method());
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
