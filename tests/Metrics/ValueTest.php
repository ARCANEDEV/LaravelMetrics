<?php namespace Arcanedev\LaravelMetrics\Tests\Metrics;

use Arcanedev\LaravelMetrics\Metrics\Value;
use Arcanedev\LaravelMetrics\Results\ValueResult;
use Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Value\{AveragePostViews, MaxPostViews, MinPostViews, TotalPosts,
    TotalPostViews};

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
        static::assertIsValueMetric($metric = new TotalPosts);

        $result = $this->calculate($metric);

        static::assertIsValueResult($result);
        static::assertEquals(5, $result->value);
    }

    /** @test */
    public function it_can_calculate_sum()
    {
        static::assertIsValueMetric($metric = new TotalPostViews);

        $result = $this->calculate($metric);

        static::assertIsValueResult($result);
        static::assertEquals(150, $result->value);
    }

    /** @test */
    public function it_can_calculate_average()
    {
        static::assertIsValueMetric($metric = new AveragePostViews);

        $result = $this->calculate($metric);

        static::assertIsValueResult($result);
        static::assertEquals(30, $result->value);
    }

    /** @test */
    public function it_can_calculate_max()
    {
        static::assertIsValueMetric($metric = new MaxPostViews);

        $result = $this->calculate($metric);

        static::assertIsValueResult($result);
        static::assertEquals(50, $result->value);
    }

    /** @test */
    public function it_can_calculate_min()
    {
        static::assertIsValueMetric($metric = new MinPostViews);

        $result = $this->calculate($metric);

        static::assertIsValueResult($result);
        static::assertEquals(10, $result->value);
    }

    /** @test */
    public function it_can_convert_to_array_and_json()
    {
        $metric = new TotalPosts;

        $expected = [
            'metric' => TotalPosts::class,
            'type'   => 'value',
            'title'  => 'Total Posts',
        ];

        static::assertEquals($expected, $metric->toArray());

        static::assertJsonStringEqualsJsonString(json_encode($expected), $metric->toJson());
        static::assertJsonStringEqualsJsonString(json_encode($expected), json_encode($metric));
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
