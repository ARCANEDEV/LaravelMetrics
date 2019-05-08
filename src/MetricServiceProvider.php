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
    }

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        parent::boot();
    }
}
