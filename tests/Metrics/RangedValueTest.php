<?php namespace Arcanedev\LaravelMetrics\Tests\Metrics;

use Arcanedev\LaravelMetrics\Metrics\RangedValue;
use Arcanedev\LaravelMetrics\Metrics\Value;
use Arcanedev\LaravelMetrics\Results\RangedValueResult;
use Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\RangedValue\TotalPublishedPosts;
use Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\RangedValue\TotalPublishedPostViews;
use Arcanedev\LaravelMetrics\Tests\Stubs\Models\Post;
use Arcanedev\LaravelMetrics\Tests\TestCase;
use Illuminate\Http\Request;

/**
 * Class     RangedValueTest
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Metrics
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RangedValueTest extends TestCase
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

        $this->createPosts();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_calculate_count()
    {
        $result = $this->calculate(new TotalPublishedPosts);

        static::assertIsRangedValueResult($result);
        static::assertEquals(1, $result->value);

        $expectations = [
            3  => 2.0,
            7  => 3.0,
            14 => 4.0,
            30 => 5.0,
        ];

        foreach ($expectations as $range => $expected) {
            $result = $this->calculate(
                new TotalPublishedPosts,
                Request::create('/', 'GET', compact('range'))
            );

            static::assertIsRangedValueResult($result);
            static::assertSame($expected, $result->value, "Result with the range [{$range}] should be: {$expected}");
        }
    }

    /** @test */
    public function it_can_calculate_sum()
    {
        $result = $this->calculate(new TotalPublishedPostViews);

        static::assertIsRangedValueResult($result);
        static::assertEquals(10, $result->value);

        $expectations = [
            3  => 30.0,
            7  => 60.0,
            14 => 100.0,
            30 => 150.0,
        ];

        foreach ($expectations as $range => $expected) {
            $result = $this->calculate(
                new TotalPublishedPostViews,
                Request::create('/', 'GET', compact('range'))
            );

            static::assertIsRangedValueResult($result);
            static::assertSame($expected, $result->value, "Fails on range [{$range}]");
        }
    }
    /* -----------------------------------------------------------------
     |  Custom Assertions
     | -----------------------------------------------------------------
     */

    /**
     * Calculate the metric.
     *
     * @param  \Arcanedev\LaravelMetrics\Metrics\RangedValue  $metric
     * @param  \Illuminate\Http\Request|null                  $request
     *
     * @return mixed
     */
    protected function calculate(RangedValue $metric, Request $request = null)
    {
        static::assertSame('ranged-value', $metric->type());

        return $metric->resolve($request ?? $this->app['request']);
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

    private function createPosts()
    {
        factory(Post::class)->create(['views' => 10, 'published_at' => now()]);
        factory(Post::class)->create(['views' => 20, 'published_at' => now()->subDays(3)]);
        factory(Post::class)->create(['views' => 30, 'published_at' => now()->subDays(7)]);
        factory(Post::class)->create(['views' => 40, 'published_at' => now()->subDays(14)]);
        factory(Post::class)->create(['views' => 50, 'published_at' => now()->subDays(30)]);
    }
}
