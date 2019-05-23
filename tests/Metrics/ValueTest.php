<?php namespace Arcanedev\LaravelMetrics\Tests\Metrics;

use Arcanedev\LaravelMetrics\Metrics\Value;
use Arcanedev\LaravelMetrics\Results\ValueResult;
use Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Value\{AveragePostViews, CachedMetric, MaxPostViews,
    MetricExtendedWithMacro, MinPostViews, TotalPosts, TotalPostViews};
use Cake\Chronos\Chronos;
use Illuminate\Support\Facades\Cache;

/**
 * Class     ValueTest
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Metrics
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ValueTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_calculate_count()
    {
        $this->createPosts($now = Chronos::now());
        Chronos::setTestNow($now);

        static::assertIsValueMetric($metric = new TotalPosts);

        $result = $this->calculate($metric);

        static::assertIsValueResult($result);
        static::assertSame(5, $result->value);

        Chronos::setTestNow();
    }

    /** @test */
    public function it_can_calculate_sum()
    {
        $this->createPosts($now = Chronos::now());
        Chronos::setTestNow($now);

        static::assertIsValueMetric($metric = new TotalPostViews);

        $result = $this->calculate($metric);

        static::assertIsValueResult($result);
        static::assertSame(150.0, $result->value);

        Chronos::setTestNow();
    }

    /** @test */
    public function it_can_calculate_average()
    {
        $this->createPosts($now = Chronos::now());
        Chronos::setTestNow($now);

        static::assertIsValueMetric($metric = new AveragePostViews);

        $result = $this->calculate($metric);

        static::assertIsValueResult($result);
        static::assertSame(30.0, $result->value);

        Chronos::setTestNow();
    }

    /** @test */
    public function it_can_calculate_max()
    {
        $this->createPosts($now = Chronos::now());
        Chronos::setTestNow($now);

        static::assertIsValueMetric($metric = new MaxPostViews);

        $result = $this->calculate($metric);

        static::assertIsValueResult($result);
        static::assertSame(50.0, $result->value);

        Chronos::setTestNow();
    }

    /** @test */
    public function it_can_calculate_min()
    {
        $this->createPosts($now = Chronos::now());
        Chronos::setTestNow($now);

        static::assertIsValueMetric($metric = new MinPostViews);

        $result = $this->calculate($metric);

        static::assertIsValueResult($result);
        static::assertSame(10.0, $result->value);

        Chronos::setTestNow();
    }

    /** @test */
    public function it_can_convert_to_array_and_json()
    {
        $this->createPosts($now = Chronos::now());
        Chronos::setTestNow($now);

        $metric = new TotalPosts;

        $expected = [
            'class' => TotalPosts::class,
            'type'  => 'value',
            'title' => 'Total Posts',
        ];

        static::assertEquals($expected, $metric->toArray());

        static::assertJsonStringEqualsJsonString(json_encode($expected), $metric->toJson());
        static::assertJsonStringEqualsJsonString(json_encode($expected), json_encode($metric));

        Chronos::setTestNow();
    }

    /** @test */
    public function it_can_cache_result()
    {
        Cache::shouldReceive('remember');

        static::assertIsValueMetric($metric = new CachedMetric);

        $this->calculate($metric);
    }

    /** @test */
    public function it_can_extend_with_custom_macros()
    {
        MetricExtendedWithMacro::macro('authorizedToSee', function ($role) {
            return $this->authorize($role);
        });

        $metric = new MetricExtendedWithMacro;

        static::assertTrue($metric->authorizedToSee('admin'));
        static::assertFalse($metric->authorizedToSee('client'));
    }

    /* -----------------------------------------------------------------
     |  Custom Assertions
     | -----------------------------------------------------------------
     */

    /**
     * Assert the given object is a value metric instance.
     *
     * @param  object  $metric
     */
    protected static function assertIsValueMetric($metric)
    {
        static::assertIsMetric($metric);
        static::assertInstanceOf(\Arcanedev\LaravelMetrics\Metrics\Value::class, $metric);
        static::assertSame('value', $metric->type());
    }

    /**
     * Assert the given object is a value result instance.
     *
     * @param  mixed   $actual
     * @param  string  $message
     */
    protected static function assertIsValueResult($actual, string $message = '')
    {
        static::assertInstanceOf(ValueResult::class, $actual, $message);
    }
}
