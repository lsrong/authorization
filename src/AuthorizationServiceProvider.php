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
        Console\MenuCommand::class,
        Console\PublishCommand::class,
        Console\ResetPasswordCommand::class
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

        // Commands
        $this->commands($this->commands);

        // Merge default configuration
        $this->mergeConfigFrom( __DIR__ . '/../config/authorization.php', 'authorization');

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

        return Collection::make(File::glob( $path . '*_create_authorization_tables.php'))
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
        // Load auth configurations
        $this->loadAuthConfig();



    }

    protected function loadAuthConfig():void
    {
        $this->app['config']->set(Arr::dot(config('authorization.auth'), 'auth.'));
    }



}