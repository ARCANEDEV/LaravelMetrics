<?php namespace Arcanedev\LaravelMetrics\Tests\Metrics;

use Arcanedev\LaravelMetrics\Results\TrendResult;
use Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Trend\CountPublishedPostsByDays;
use Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Trend\CountPublishedPostsByMonths;
use Arcanedev\LaravelMetrics\Tests\Stubs\Models\Post;
use Cake\Chronos\Chronos;
use Illuminate\Http\Request;

/**
 * Class     TrendTest
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Metrics
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TrendTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_calculate_count_by_months()
    {
        Chronos::setTestNow($now = Chronos::create(2019, 5, 1, 0, 0, 0));

        factory(Post::class)->create(['views' => 10, 'published_at' => $now]);
        factory(Post::class)->create(['views' => 50, 'published_at' => $now->subMonths(1)]);
        factory(Post::class)->create(['views' => 40, 'published_at' => $now->subMonths(1)]);
        factory(Post::class)->create(['views' => 30, 'published_at' => $now->subMonths(2)]);
        factory(Post::class)->create(['views' => 20, 'published_at' => $now->subMonths(2)]);

        static::assertIsTrendMetric($metric = new CountPublishedPostsByMonths);

        $result = $this->calculate($metric);

        static::assertIsTrendResult($result);

        $expected = [
            '2019-05' => [
                'label' => 'May 2019',
                'value' => 1,
            ]
        ];

        static::assertEquals($expected, $result->trend);

        $result = $this->calculate($metric, Request::create('/', 'GET', ['range' => 3]));

        $expected = [
            '2019-03' => [
                'label' => 'March 2019',
                'value' => 2,
            ],
            '2019-04' => [
                'label' => 'April 2019',
                'value' => 2,
            ],
            '2019-05' => [
                'label' => 'May 2019',
                'value' => 1,
            ],
        ];

        static::assertEquals($expected, $result->trend);
    }

    /** @test */
    public function it_can_calculate_count_by_days()
    {
        $this->createPosts($now = Chronos::now());
        Chronos::setTestNow($now);

        static::assertIsTrendMetric($metric = new CountPublishedPostsByDays);

        $result = $this->calculate($metric);

        static::assertIsTrendResult($result);

        static::assertCount(1, $result->trend);
        static::assertSame(1, last($result->trend)['value']);

        $result = $this->calculate($metric, Request::create('/', 'GET', ['range' => 3]));

        static::assertCount(3, $result->trend);
        static::assertSame(1, last($result->trend)['value']);

        Chronos::setTestNow();
    }

    /** @test */
    public function it_can_calculate_count_by_hours()
    {
        $this->createPosts($now = Chronos::now());
        Chronos::setTestNow($now);

        static::assertIsTrendMetric($metric = new CountPublishedPostsByDays);

        $result = $this->calculate($metric);

        static::assertIsTrendResult($result);

        static::assertCount(1, $result->trend);
        static::assertSame(1, last($result->trend)['value']);

        Chronos::setTestNow();

        $result = $this->calculate($metric, Request::create('/', 'GET', ['range' => 3]));

        static::assertCount(3, $result->trend);
        static::assertSame(1, last($result->trend)['value']);
    }

    /* -----------------------------------------------------------------
     |  Assertions
     | -----------------------------------------------------------------
     */

    /**
     * Assert the given object is a trend metric instance.
     *
     * @param  object  $metric
     */
    private static function assertIsTrendMetric($metric)
    {
        static::assertIsMetric($metric);
        static::assertInstanceOf(\Arcanedev\LaravelMetrics\Metrics\Trend::class, $metric);
        static::assertSame('trend', $metric->type());
    }

    /**
     * Assert the given object is a value result instance.
     *
     * @param  mixed   $actual
     * @param  string  $message
     */
    protected static function assertIsTrendResult($actual, string $message = '')
    {
        static::assertInstanceOf(TrendResult::class, $actual, $message);
    }
}
