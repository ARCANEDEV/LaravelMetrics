<?php namespace Arcanedev\LaravelMetrics\Tests\Metrics;

use Arcanedev\LaravelMetrics\Metrics\RangedValue;
use Arcanedev\LaravelMetrics\Tests\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

/**
 * Class     TestCase
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Metrics
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrations();
        $this->loadFactories();
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Calculate the metric.
     *
     * @param  \Arcanedev\LaravelMetrics\Metrics\Metric  $metric
     * @param  \Illuminate\Http\Request|null             $request
     *
     * @return \Arcanedev\LaravelMetrics\Results\Result|mixed
     */
    protected function calculate($metric, Request $request = null)
    {
        return $metric->resolve($request ?? $this->app['request']);
    }

    /* -----------------------------------------------------------------
     |  Assertions
     | -----------------------------------------------------------------
     */

    /**
     * Assert the given object is a metric instance.
     *
     * @param  object  $metric
     */
    protected static function assertIsMetric($metric)
    {
        $expectations = [
            \Illuminate\Contracts\Support\Arrayable::class,
            \Illuminate\Contracts\Support\Jsonable::class,
            \Arcanedev\LaravelMetrics\Metrics\Metric::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $metric);
        }
    }
}