<?php namespace Arcanedev\LaravelMetrics;

use Arcanedev\Support\PackageServiceProvider;

/**
 * Class     MetricServiceProvider
 *
 * @package  Arcanedev\LaravelMetrics
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class MetricServiceProvider extends PackageServiceProvider
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
    public function register()
    {
        parent::register();

        $this->registerConfig();

        $this->singleton(Contracts\Manager::class, Manager::class);
    }

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        parent::boot();

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
