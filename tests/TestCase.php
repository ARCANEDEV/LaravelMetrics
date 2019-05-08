<?php namespace Arcanedev\LaravelMetrics\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * Class     TestCase
 *
 * @package  Arcanedev\LaravelMetrics\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Arcanedev\LaravelMetrics\MetricServiceProvider::class,
        ];
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Load the migrations.
     */
    protected function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ .'/fixtures/migrations');
    }

    /**
     * Load the factories.
     */
    protected function loadFactories()
    {
        $this->withFactories(__DIR__.'/fixtures/factories');
    }
}
