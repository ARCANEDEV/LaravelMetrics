<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Metrics;

use Arcanedev\LaravelMetrics\Results\ValueResult;
use Arcanedev\LaravelMetrics\Tests\Stubs\Database\Factories\PostFactory;
use Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Value\{
    AveragePostViews, AverageViewsCount, CachedMetric, MaxPostViews, MetricExtendedWithMacro, MinPostViews,
    TotalPosts, TotalPostViews
};
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * Class     ValueTest
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ValueTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_calculate_count(): void
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
    public function it_can_calculate_sum(): void
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
    public function it_can_calculate_average(): void
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
    public function it_can_calculate_max(): void
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
    public function it_can_calculate_min(): void
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
    public function it_can_convert_to_array_and_json(): void
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
    public function it_can_cache_result(): void
    {
        Cache::shouldReceive('remember');

        static::assertIsValueMetric($metric = new CachedMetric);

        $this->calculate($metric);
    }

    /** @test */
    public function it_can_extend_with_custom_macros(): void
    {
        MetricExtendedWithMacro::macro('authorizedToSee', function ($role) {
            return $this->authorize($role);
        });

        $metric = new MetricExtendedWithMacro;

        static::assertTrue($metric->authorizedToSee('admin'));
        static::assertFalse($metric->authorizedToSee('client'));
    }

    /** @test */
    public function it_can_get_rounded_value_with_custom_precision(): void
    {
        Carbon::setTestNow($now = Carbon::now());
        $averageViews = PostFactory::new(['published_at' => $now])->count(2)->create()->average('views');

        static::assertIsValueMetric($metric = new AverageViewsCount);

        $result = $this->calculate($metric);

        static::assertIsValueResult($result);
        static::assertSame(round($averageViews, 1), $result->value);
    }

    /** @test */
    public function it_can_get_rounded_value_with_default_precision(): void
    {
        Carbon::setTestNow($now = Carbon::now());
        $averageViews = PostFactory::new(['published_at' => $now])->count(3)->create()->average('views');

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
    protected static function assertIsValueMetric($metric): void
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
    protected static function assertIsValueResult($actual, string $message = ''): void
    {
        static::assertInstanceOf(ValueResult::class, $actual, $message);
    }
}
