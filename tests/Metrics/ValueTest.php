<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Metrics;

use Arcanedev\LaravelMetrics\Results\ValueResult;
use Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Value\{
    AveragePostViews, AverageViewsCount, CachedMetric, MaxPostViews, MetricExtendedWithMacro, MinPostViews,
    TotalPosts, TotalPostViews
};
use Arcanedev\LaravelMetrics\Tests\Stubs\Models\Post;
use Carbon\Carbon;
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
        Carbon::setTestNow($now = Carbon::now());
        $this->createPosts($now);

        static::assertIsValueMetric($metric = new TotalPosts);

        $result = $this->calculate($metric);

        static::assertIsValueResult($result);
        static::assertSame(5, $result->value);

        Carbon::setTestNow();
    }

    /** @test */
    public function it_can_calculate_sum()
    {
        Carbon::setTestNow($now = Carbon::now());
        $this->createPosts($now);

        static::assertIsValueMetric($metric = new TotalPostViews);

        $result = $this->calculate($metric);

        static::assertIsValueResult($result);
        static::assertSame(150.0, $result->value);

        Carbon::setTestNow();
    }

    /** @test */
    public function it_can_calculate_average()
    {
        Carbon::setTestNow($now = Carbon::now());
        $this->createPosts($now);

        static::assertIsValueMetric($metric = new AveragePostViews);

        $result = $this->calculate($metric);

        static::assertIsValueResult($result);
        static::assertSame(30.0, $result->value);

        Carbon::setTestNow();
    }

    /** @test */
    public function it_can_calculate_max()
    {
        $this->createPosts($now = Carbon::now());
        Carbon::setTestNow($now);

        static::assertIsValueMetric($metric = new MaxPostViews);

        $result = $this->calculate($metric);

        static::assertIsValueResult($result);
        static::assertSame(50.0, $result->value);

        Carbon::setTestNow();
    }

    /** @test */
    public function it_can_calculate_min()
    {
        $this->createPosts($now = Carbon::now());
        Carbon::setTestNow($now);

        static::assertIsValueMetric($metric = new MinPostViews);

        $result = $this->calculate($metric);

        static::assertIsValueResult($result);
        static::assertSame(10.0, $result->value);

        Carbon::setTestNow();
    }

    /** @test */
    public function it_can_convert_to_array_and_json()
    {
        Carbon::setTestNow($now = Carbon::now());
        $this->createPosts($now);

        $metric = new TotalPosts;

        $expected = [
            'class' => TotalPosts::class,
            'type'  => 'value',
            'title' => 'Total Posts',
        ];

        static::assertEquals($expected, $metric->toArray());

        static::assertJsonStringEqualsJsonString(json_encode($expected), $metric->toJson());
        static::assertJsonStringEqualsJsonString(json_encode($expected), json_encode($metric));

        Carbon::setTestNow();
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

    /** @test */
    public function it_can_get_rounded_value_with_custom_precision()
    {
        Carbon::setTestNow($now = Carbon::now());
        $averageViews = factory(Post::class, 2)->create(['published_at' => $now])->average('views');

        static::assertIsValueMetric($metric = new AverageViewsCount);

        $result = $this->calculate($metric);

        static::assertIsValueResult($result);
        static::assertSame(round($averageViews, 1), $result->value);
    }

    /** @test */
    public function it_can_get_rounded_value_with_default_precision()
    {
        Carbon::setTestNow($now = Carbon::now());
        $averageViews = factory(Post::class, 3)->create(['published_at' => $now])->average('views');

        static::assertIsValueMetric($metric = (new AverageViewsCount)->precision(2));

        $result = $this->calculate($metric);

        static::assertIsValueResult($result);
        static::assertSame(round($averageViews, 2), $result->value);
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
