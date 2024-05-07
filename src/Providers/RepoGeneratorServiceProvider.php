<?php

declare(strict_types=1);

namespace Pavelmgn\RepoGenerator\Providers;

use Illuminate\Support\ServiceProvider;
use Pavelmgn\RepoGenerator\Console\Commands\RepoGenerateCommand;

final class RepoGeneratorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/repogenerator.php' => config_path('repogenerator.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                RepoGenerateCommand::class
            ]);
        }
    }
}
