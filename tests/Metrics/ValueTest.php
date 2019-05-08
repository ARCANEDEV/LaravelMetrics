<?php namespace Arcanedev\LaravelMetrics\Tests\Metrics;

use Arcanedev\LaravelMetrics\Results\ValueResult;
use Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Value\{AveragePostViews, MaxPostViews, MinPostViews, TotalPosts,
    TotalPostViews};
use Arcanedev\LaravelMetrics\Tests\Stubs\Models\Post;
use Arcanedev\LaravelMetrics\Metrics\Value;
use Arcanedev\LaravelMetrics\Tests\TestCase;

/**
 * Class     ValueTest
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Metrics
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ValueTest extends TestCase
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

        factory(Post::class)->create(['views' => 150]);
        factory(Post::class)->create(['views' => 100]);
        factory(Post::class)->create(['views' => 50]);
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_calculate_count()
    {
        $result = $this->calculate(new TotalPosts);

        static::assertIsValueResult($result);
        static::assertEquals(3, $result->value);
    }

    /** @test */
    public function it_can_calculate_sum()
    {
        $result = $this->calculate(new TotalPostViews);

        static::assertIsValueResult($result);
        static::assertEquals(300, $result->value);
    }

    /** @test */
    public function it_can_calculate_average()
    {
        $result = $this->calculate(new AveragePostViews);

        static::assertIsValueResult($result);
        static::assertEquals(100, $result->value);
    }

    /** @test */
    public function it_can_calculate_max()
    {
        $result = $this->calculate(new MaxPostViews);

        static::assertIsValueResult($result);
        static::assertEquals(150, $result->value);
    }

    /** @test */
    public function it_can_calculate_min()
    {
        $result = $this->calculate(new MinPostViews);

        static::assertIsValueResult($result);
        static::assertEquals(50, $result->value);
    }

    /* -----------------------------------------------------------------
     |  Custom Assertions
     | -----------------------------------------------------------------
     */

    /**
     * Calculate the metric.
     *
     * @param  \Arcanedev\LaravelMetrics\Metrics\Value  $metric
     *
     * @return mixed
     */
    protected function calculate(Value $metric)
    {
        static::assertSame('value', $metric->type());

        return $metric->resolve($this->app['request']);
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
