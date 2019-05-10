<?php namespace Arcanedev\LaravelMetrics\Tests;

use Arcanedev\LaravelMetrics\MetricServiceProvider;

/**
 * Class     MetricServiceProviderTest
 *
 * @package  Arcanedev\LaravelMetrics\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class MetricServiceProviderTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\LaravelMetrics\MetricServiceProvider */
    private $provider;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    protected function setUp(): void
    {
        parent::setUp();

        $this->provider = $this->app->getProvider(MetricServiceProvider::class);
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $expectations = [
            \Illuminate\Support\ServiceProvider::class,
            \Arcanedev\Support\ServiceProvider::class,
            \Arcanedev\Support\PackageServiceProvider::class,
            MetricServiceProvider::class
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->provider);
        }
    }

    /** @test */
    public function it_can_provides()
    {
        $expected = [
            \Arcanedev\LaravelMetrics\Contracts\Manager::class,
        ];

        static::assertSame($expected, $this->provider->provides());
    }
}
