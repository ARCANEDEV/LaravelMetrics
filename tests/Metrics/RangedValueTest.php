<?php namespace Arcanedev\LaravelMetrics\Tests\Metrics;

use Arcanedev\LaravelMetrics\Results\RangedValueResult;
use Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\RangedValue\{AveragePublishedPostViews, CachedMetric,
    MaxPublishedPostViews, MinPublishedPostViews, TotalPublishedPosts, TotalPublishedPostViews};
use Cake\Chronos\Chronos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Class     RangedValueTest
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Metrics
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RangedValueTest extends TestCase
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

        static::assertIsMetric($metric = new TotalPublishedPosts);

        $result = $this->calculate($metric);

        static::assertIsRangedValueResult($result);
        static::assertEquals(1, $result->value);

        $expectations = [
            3  => ['value' => 2, 'previous' => 0],
            7  => ['value' => 3, 'previous' => 1],
            14 => ['value' => 4, 'previous' => 0],
            30 => ['value' => 5, 'previous' => 0],
        ];

        foreach ($expectations as $range => $expected) {
            $result = $this->calculate(
                $metric,
                Request::create('/', 'GET', compact('range'))
            );

            static::assertIsRangedValueResult($result);
            static::assertSame($expected['value'], $result->value, "Fails the current value on range [{$range}]");
            static::assertSame($expected['previous'], $result->previous['value'], "Fails the previous value on range [{$range}]");
        }

        Chronos::setTestNow();
    }

    /** @test */
    public function it_can_calculate_average()
    {
        $this->createPosts($now = Chronos::now());
        Chronos::setTestNow($now);

        static::assertIsMetric($metric = new AveragePublishedPostViews);

        $result = $this->calculate($metric);

        static::assertIsRangedValueResult($result);
        static::assertEquals(10, $result->value);

        $expectations = [
            3  => ['value' => 15.0, 'previous' => 0.0],
            7  => ['value' => 20.0, 'previous' => 40.0],
            14 => ['value' => 25.0, 'previous' => 0.0],
            30 => ['value' => 30.0, 'previous' => 0.0],
        ];

        foreach ($expectations as $range => $expected) {
            $result = $this->calculate(
                $metric,
                Request::create('/', 'GET', compact('range'))
            );

            static::assertIsRangedValueResult($result);
            static::assertSame($expected['value'], $result->value, "Fails the current value on range [{$range}]");
            static::assertSame($expected['previous'], $result->previous['value'], "Fails the previous value on range [{$range}]");
        }

        Chronos::setTestNow();
    }

    /** @test */
    public function it_can_calculate_sum()
    {
        $this->createPosts($now = Chronos::now());
        Chronos::setTestNow($now);

        static::assertIsMetric($metric = new TotalPublishedPostViews);

        $result = $this->calculate($metric);

        static::assertIsRangedValueResult($result);
        static::assertEquals(10, $result->value);

        $expectations = [
            3  => ['value' => 30.0, 'previous' => 0.0],
            7  => ['value' => 60.0, 'previous' => 40.0],
            14 => ['value' => 100.0, 'previous' => 0.0],
            30 => ['value' => 150.0, 'previous' => 0.0],
        ];

        foreach ($expectations as $range => $expected) {
            $result = $this->calculate(
                $metric,
                Request::create('/', 'GET', compact('range'))
            );

            static::assertIsRangedValueResult($result);
            static::assertSame($expected['value'], $result->value, "Fails the current value on range [{$range}]");
            static::assertSame($expected['previous'], $result->previous['value'], "Fails the previous value on range [{$range}]");
        }

        Chronos::setTestNow();
    }

    /** @test */
    public function it_can_calculate_min()
    {
        $this->createPosts($now = Chronos::now());
        Chronos::setTestNow($now);

        static::assertIsMetric($metric = new MinPublishedPostViews);

        $result = $this->calculate($metric);

        static::assertIsRangedValueResult($result);
        static::assertEquals(10, $result->value);

        $expectations = [
            3  => ['value' => 10.0, 'previous' => 0.0],
            7  => ['value' => 10.0, 'previous' => 40.0],
            14 => ['value' => 10.0, 'previous' => 0.0],
            30 => ['value' => 10.0, 'previous' => 0.0],
        ];

        foreach ($expectations as $range => $expected) {
            $result = $this->calculate(
                $metric,
                Request::create('/', 'GET', compact('range'))
            );

            static::assertIsRangedValueResult($result);
            static::assertSame($expected['value'], $result->value, "Fails the current value on range [{$range}]");
            static::assertSame($expected['previous'], $result->previous['value'], "Fails the previous value on range [{$range}]");
        }

        Chronos::setTestNow();
    }

    /** @test */
    public function it_can_calculate_max()
    {
        $this->createPosts($now = Chronos::now());
        Chronos::setTestNow($now);

        static::assertIsMetric($metric = new MaxPublishedPostViews);

        $result = $this->calculate($metric);

        static::assertIsRangedValueResult($result);
        static::assertEquals(10, $result->value);

        $expectations = [
            3  => ['value' => 20.0, 'previous' => 0.0],
            7  => ['value' => 30.0, 'previous' => 40.0],
            14 => ['value' => 40.0, 'previous' => 0.0],
            30 => ['value' => 50.0, 'previous' => 0.0],
        ];

        foreach ($expectations as $range => $expected) {
            $result = $this->calculate(
                $metric,
                Request::create('/', 'GET', compact('range'))
            );

            static::assertIsRangedValueResult($result);
            static::assertSame($expected['value'], $result->value, "Fails the current value on range [{$range}]");
            static::assertSame($expected['previous'], $result->previous['value'], "Fails the previous value on range [{$range}]");
        }

        Chronos::setTestNow();
    }

    /** @test */
    public function it_can_convert_to_array_and_json()
    {
        $this->createPosts($now = Chronos::now());
        Chronos::setTestNow($now);

        $metric = new TotalPublishedPosts;

        $expected = [
            'metric' => TotalPublishedPosts::class,
            'type'   => 'ranged-value',
            'title'  => 'Total Published Posts',
            'ranges' => [
                [
                    'value' => 3,
                    'label' => '3 Days'
                ],[
                    'value' => 7,
                    'label' => '7 Days',
                ],[
                    'value' => 14,
                    'label' => '14 Days',
                ],[
                    'value' => 30,
                    'label' => '30 Days',
                ],
            ],
        ];

        static::assertEquals($expected, $metric->toArray());

        static::assertJsonStringEqualsJsonString(json_encode($expected), json_encode($metric));
        static::assertJsonStringEqualsJsonString(json_encode($expected), $metric->toJson());

        Chronos::setTestNow();
    }

    /** @test */
    public function it_can_cache_result()
    {
        Cache::shouldReceive('remember');

        static::assertIsValueMetric($metric = new CachedMetric);

        $this->calculate($metric);
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
        static::assertInstanceOf(\Arcanedev\LaravelMetrics\Metrics\RangedValue::class, $metric);
        static::assertSame('ranged-value', $metric->type());
    }

    /**
     * Assert the given object is a value result instance.
     *
     * @param  mixed   $actual
     * @param  string  $message
     */
    protected static function assertIsRangedValueResult($actual, string $message = '')
    {
        static::assertInstanceOf(RangedValueResult::class, $actual, $message);
    }
}
