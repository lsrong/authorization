<?php


namespace Lson\Authorization\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait ShouldPassThrough
{
    /**
     * Determine if the request should pass through
     *
     * @param Request $request
     * @return bool
     *
     * @author lsrong
     * @datetime 03/07/2020 11:37
     */
    protected function isThrough($request):bool
    {
        $excepts = array_merge(config('authorization.auth.excepts', []), [
            'api/login',
            'api/logout'
        ]);

        $prefix = trim(config('authorization.prefix', ''), '/');
        $prefix = $prefix ? '/' . $prefix . '/' : '/';

        return Collection::make($excepts)
            ->map(static function($path) use($prefix){
                return $prefix . trim($path, '/');
            })
            ->contains(static function ($path) use($request){
                return $request->is($path);
            });
    }
}