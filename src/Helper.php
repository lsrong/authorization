<?php
namespace Lson\Authorization;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;

class Helper
{
    /**
     * Authorization guard
     *
     * @return Guard|StatefulGuard|mixed
     *
     * @author lsrong
     * @datetime 03/07/2020 11:27
     */
    public static function guard()
    {
        $guard = config('authorization.auth.guard') ?: null;

        return Auth::guard($guard);
    }
}
