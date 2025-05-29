<?php

declare(strict_types=1);

namespace Tilabs\Manet\Providers;

use Illuminate\Support\ServiceProvider;
use Tilabs\Manet\Services\ManetManager;

final class ManetServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->alias(ManetManager::class, 'manet');
        $this->app->singleton(ManetManager::class);

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Manet', \Tilabs\Manet\Facades\Manet::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../tests/Unit/ManetTest.php' => base_path('/tests/Unit/ManetPackageTest.php'),
        ], 'tilabs-manet-tests');
    }
}
