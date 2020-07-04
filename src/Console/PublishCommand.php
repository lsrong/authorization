<?php

namespace Lson\Authorization\Console;

use Illuminate\Console\Command;
use Lson\Authorization\AuthorizationServiceProvider;

class PublishCommand extends Command
{
    protected $signature = 'auth:publish {--force}';

    protected $description = "re-publish authorization's configuration and migration files. If you want overwrite the existing files, you can add the `--force` option";

    public function handle(): void
    {
        $options = ['--provider' => AuthorizationServiceProvider::class];

        if ($this->option('force') === true) {
            $options['--force'] = true;
        }

        $this->call('vendor:publish', $options);
    }
}
