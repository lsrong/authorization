<?php


namespace Lson\Authorization\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Lson\Authorization\Helper;

class OperationLog
{
    /**
     *
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     *
     * @author lsrong
     * @datetime 04/07/2020 16:24
     */
    public function handle($request, Closure $next)
    {
        if ($this->needLog($request)) {
            try {
                config('authorization.database.operation_log_model')::query()->create([
                    'user_id' => Helper::guard()->id(),
                    'path'    => substr($request->path(), 0, 255),
                    'method'  => $request->method(),
                    'ip'      => $request->getClientIp(),
                    'input'   => json_encode($request->input()),
                ]);
            } catch (Exception $e) {
                // pass
            }
        }

        return $next($request);
    }

    /**
     * Determine if the request is need to be logged
     *
     * @param Request $request
     * @return bool
     *
     * @author lsrong
     * @datetime 04/07/2020 16:41
     */
    protected function needLog($request):bool
    {
        return config('authorization.operation_log.enable')
            && Helper::guard()->check()
            && ! $this->inExcepts($request)
            && $this->inAllowedMethods($request->method());
    }

    /**
     * Determine if the request in operation log 'excepts'
     *
     * @param Request $request
     * @return bool
     *
     * @author lsrong
     * @datetime 04/07/2020 16:27
     */
    protected function inExcepts($request):bool
    {
        $excepts = config('authorization.operation_log.except');
        if (empty($excepts)) {
            return false;
        }
        foreach ($excepts as $except) {
            $except !== '/'  &&  $except = trim($except, '/');
            $methods = [];
            if (Str::contains($except, ':')) {
                [$methods, $except] = explode(':', $except);
                $methods = array_map('strtoupper', array_filter(explode(',', $methods)));
            }

            if ($request->is($except) && (empty($methods) || in_array($request->method(), $methods, true))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the request method is allowed to be logged
     *
     * @param string $method
     * @return bool
     *
     * @author lsrong
     * @datetime 04/07/2020 16:40
     */
    protected function inAllowedMethods(string $method): bool
    {
        return Collection::make(array_map('strtoupper', config('authorization.allowed_methods'), []))->contains($method);
    }
}
