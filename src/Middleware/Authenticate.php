<?php

namespace Lson\Authorization\Middleware;

use Closure;
use Illuminate\Http\Request;
use Lson\Authorization\Exception\UnauthenticatedException;
use Lson\Authorization\Helper;
use Lson\Authorization\Traits\ShouldPassThrough;

class Authenticate
{
    use ShouldPassThrough;

    /**
     * Authenticate handle
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws UnauthenticatedException
     *
     * @author lsrong
     * @datetime 02/07/2020 20:18
     */
    public function handle($request, Closure $next)
    {
        // Authenticated
        if(Helper::guard()->check()){
            return $next($request);
        }

        // Should pass through
        if($this->isThrough($request)) {
            return $next($request);
        }

        $this->unauthenticated();
    }

    /**
     * Unauthenticated handle
     *
     * @throws UnauthenticatedException
     *
     * @author lsrong
     * @datetime 03/07/2020 12:15
     */
    protected function unauthenticated(): void
    {
        throw new UnauthenticatedException('Unauthenticated.');
    }

}
