<?php
namespace Lson\Authorization;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class AuthorizationServiceProvider extends ServiceProvider
{
    /**
     * @var array Register commands
     */
    protected $commands = [
        Console\PublishCommand::class,
    ];

    /**
     * Route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'authorization.auth'    => Middleware\Authenticate::class,
        'authorization.log'        => Middleware\OperationLog::class,
        'authorization.permission' => Middleware\Permission::class,
    ];


    /**
     * Authorization service provider boot
     *
     * @author lsrong
     * @datetime 02/07/2020 19:36
     */
    public function boot(): void
    {
        // Publishing
        $this->publishing();

        // Merge default configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/authorization.php', 'authorization');
    }
    /**
     * Publish config migration files
     *
     * @author lsrong
     * @datetime 02/07/2020 19:37
     */
    protected function publishing():void
    {
        // config
        $this->publishes([ __DIR__ . '/../config/authorization.php' => $this->app->configPath('authorization.php'),], 'config');

        // migration
        $this->publishes([__DIR__.'/../database/migrations/create_permission_tables.php' => $this->migration(),], 'migrations');
    }

    /**
     * Migration file name
     *
     * @return string
     *
     * @author lsrong
     * @datetime 02/07/2020 18:07
     */
    protected function migration(): string
    {
        $path = $this->app->databasePath('migrations' . DIRECTORY_SEPARATOR);

        return Collection::make(File::glob($path . '*_create_authorization_tables.php'))
            ->push($path . date('Y_m_d_His') . '_create_authorization_tables.php')
            ->first();
    }


    /**
     * Authorization service provider register
     *
     * @author lsrong
     * @datetime 02/07/2020 19:39
     */
    public function register():void
    {
        // Load authorization configurations
        $this->loadAuthorizationConfiguration();

        // Load commands
        $this->commands($this->commands);

        // Load route middleware
        $this->loadRouteMiddleware();
    }

    /**
     * Load authorization configuration
     *
     * @author lsrong
     * @datetime 04/07/2020 16:54
     */
    protected function loadAuthorizationConfiguration():void
    {
        $this->app['config']->set(Arr::dot(config('authorization.auth'), 'auth.'));
    }

    /**
     * Register route middleware
     *
     * @author lsrong
     * @datetime 04/07/2020 17:08
     */
    protected function loadRouteMiddleware():void
    {
        // Register route middleware.
        foreach ($this->routeMiddleware as $name => $middleware) {
            $this->app['router']->aliasMiddleware($name, $middleware);
        }

        // Register route middleware groups
        $this->app['router']->middlewareGroup(config('authorization.route_middleware'), array_keys($this->routeMiddleware));
    }
}
