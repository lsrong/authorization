<?php


namespace Lson\Authorization\Middleware;

use Illuminate\Http\Response;
use Lson\Authorization\Helper;
use Lson\Authorization\Traits\ShouldPassThrough;

class Permission
{
    use ShouldPassThrough;

    public function handle($request, \Closure $nest)
    {
        // No need to check permission
        if (! config('authorization.check_permission')) {
            return $nest($request);
        }

        // Should pass through
        if ($this->isThrough($request)) {
            return $nest($request);
        }

        /**
         * @var $user \Lson\Authorization\Database\User
         */
        $user = Helper::guard()->user();
        // Pass if not user
        if (! $user) {
            return $nest($request);
        }

        // Pass if the user is super administrator
        if ($user->isSuperAdministrator()) {
            return $nest($request);
        }

        // Check user permission
        if (Helper::guard()->user()->allPermissions()->first(static function ($permission) use ($request) {
            return $permission->isThrough($request);
        })) {
            // Pass permission
            return $nest($request);
        }

        // Permission denied, Can be customized
        $this->accessDenied();
    }

    /**
     *  Access denied
     *
     * @author lsrong
     * @datetime 04/07/2020 10:57
     */
    protected function accessDenied(): void
    {
        abort(Response::HTTP_FORBIDDEN, 'Permission denied');
    }
}
