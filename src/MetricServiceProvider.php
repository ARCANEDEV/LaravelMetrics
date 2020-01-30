<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics;

use Arcanedev\Support\Providers\PackageServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

/**
 * Class     MetricServiceProvider
 *
 * @package  Arcanedev\LaravelMetrics
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class MetricServiceProvider extends PackageServiceProvider implements DeferrableProvider
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Package name.
     *
     * @var string
     */
    protected $package = 'metrics';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        parent::register();

        $this->registerConfig();

        $this->singleton(Contracts\Manager::class, Manager::class);
    }

    /**
     * Boot the service provider.
     */
    public function boot(): void
    {
        $this->publishConfig();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            Contracts\Manager::class,
        ];
    }
}
