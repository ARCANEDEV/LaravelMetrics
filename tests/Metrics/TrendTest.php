<?php namespace Arcanedev\LaravelMetrics\Tests\Metrics;

use Arcanedev\LaravelMetrics\Metrics\Trend;
use Arcanedev\LaravelMetrics\Results\TrendResult;
use Arcanedev\LaravelMetrics\Tests\Stubs\Models\User;
use Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Trend\{CountPublishedPostsByDays, CountPublishedPostsByHours,
    CountPublishedPostsByMinutes, CountPublishedPostsByMonths, CountPublishedPostsByWeeks};
use Arcanedev\LaravelMetrics\Tests\Stubs\Models\Post;
use Cake\Chronos\Chronos;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**
 * Class     TrendTest
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Metrics
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TrendTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Count Tests
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
    public function it_can_calculate_count_by_weeks()
    {
        Chronos::setTestNow($now = Chronos::create(2019, 5, 5, 0, 0, 0));

        factory(Post::class)->create(['views' => 10, 'published_at' => $now]);
        factory(Post::class)->create(['views' => 50, 'published_at' => $now->subWeeks(1)]);
        factory(Post::class)->create(['views' => 40, 'published_at' => $now->subWeeks(1)]);
        factory(Post::class)->create(['views' => 30, 'published_at' => $now->subWeeks(2)]);
        factory(Post::class)->create(['views' => 20, 'published_at' => $now->subWeeks(2)]);

        $metric = new CountPublishedPostsByWeeks;

        static::assertIsTrendMetric($metric);

        $result = $this->calculate($metric);

        static::assertIsTrendResult($result);

        $expected = [
            '2019-04-29 2019-05-05' => [
                'label' => 'April 29 - May 5',
                'value' => 1,
            ],
        ];
        static::assertEquals($expected, $result->trend);

        $result = $this->calculate($metric, Request::create('/', 'GET', ['range' => 3]));

        $expected = [
            '2019-04-15 2019-04-21' => [
                'label' => 'April 15 - April 21',
                'value' => 2,
            ],
            '2019-04-22 2019-04-28' => [
                'label' => 'April 22 - April 28',
                'value' => 2,
            ],
            '2019-04-29 2019-05-05' => [
                'label' => 'April 29 - May 5',
                'value' => 1,
            ],
        ];
        static::assertEquals($expected, $result->trend);

        Chronos::setTestNow();
    }

    /** @test */
    public function it_can_calculate_count_by_days()
    {
        Chronos::setTestNow($now = Chronos::create(2019, 5, 1, 0, 0, 0));

        factory(Post::class)->create(['views' => 10, 'published_at' => $now]);
        factory(Post::class)->create(['views' => 50, 'published_at' => $now->subDays(1)]);
        factory(Post::class)->create(['views' => 40, 'published_at' => $now->subDays(1)]);
        factory(Post::class)->create(['views' => 30, 'published_at' => $now->subDays(2)]);
        factory(Post::class)->create(['views' => 20, 'published_at' => $now->subDays(2)]);

        static::assertIsTrendMetric($metric = new CountPublishedPostsByDays);

        $result = $this->calculate($metric);

        static::assertIsTrendResult($result);

        $expected = [
            '2019-05-01' => [
                'label' => 'May 1, 2019',
                'value' => 1,
            ],
        ];

        static::assertEquals($expected, $result->trend);

        $result = $this->calculate($metric, Request::create('/', 'GET', ['range' => 3]));

        $expected = [
            '2019-04-29' => [
                'label' => 'April 29, 2019',
                'value' => 2,
            ],
            '2019-04-30' => [
                'label' => 'April 30, 2019',
                'value' => 2,
            ],
            '2019-05-01' => [
                'label' => 'May 1, 2019',
                'value' => 1,
            ],
        ];
        static::assertEquals($expected, $result->trend);

        Chronos::setTestNow();
    }

    /** @test */
    public function it_can_calculate_count_by_hours()
    {
        Chronos::setTestNow($now = Chronos::create(2019, 5, 1, 0, 0, 0));

        factory(Post::class)->create(['views' => 10, 'published_at' => $now]);
        factory(Post::class)->create(['views' => 50, 'published_at' => $now->subHours(1)]);
        factory(Post::class)->create(['views' => 40, 'published_at' => $now->subHours(1)]);
        factory(Post::class)->create(['views' => 30, 'published_at' => $now->subHours(2)]);
        factory(Post::class)->create(['views' => 20, 'published_at' => $now->subHours(2)]);

        static::assertIsTrendMetric($metric = new CountPublishedPostsByHours);

        $result = $this->calculate($metric);

        static::assertIsTrendResult($result);

        $expected = [
            '2019-05-01 00:00' => [
                'label' => 'May 1 - 0:00',
                'value' => 1,
            ],
        ];
        static::assertEquals($expected, $result->trend);

        $result = $this->calculate($metric, Request::create('/', 'GET', ['range' => 3]));

        $expected = [
            '2019-04-30 22:00' => [
                'label' => 'April 30 - 22:00',
                'value' => 2,
            ],
            '2019-04-30 23:00' => [
                'label' => 'April 30 - 23:00',
                'value' => 2,
            ],
            '2019-05-01 00:00' => [
                'label' => 'May 1 - 0:00',
                'value' => 1,
            ],
        ];
        static::assertEquals($expected, $result->trend);

        Chronos::setTestNow();
    }

    /** @test */
    public function it_can_calculate_count_by_minutes()
    {
        Chronos::setTestNow($now = Chronos::create(2019, 5, 1, 0, 0, 0));

        factory(Post::class)->create(['views' => 10, 'published_at' => $now]);
        factory(Post::class)->create(['views' => 50, 'published_at' => $now->subMinutes(1)]);
        factory(Post::class)->create(['views' => 40, 'published_at' => $now->subMinutes(1)]);
        factory(Post::class)->create(['views' => 30, 'published_at' => $now->subMinutes(2)]);
        factory(Post::class)->create(['views' => 20, 'published_at' => $now->subMinutes(2)]);

        static::assertIsTrendMetric($metric = new CountPublishedPostsByMinutes);

        $result = $this->calculate($metric);

        static::assertIsTrendResult($result);

        $expected = [
            '2019-05-01 00:00' => [
                'label' => 'May 1 - 0:00',
                'value' => 1,
            ],
        ];
        static::assertEquals($expected, $result->trend);

        $result = $this->calculate($metric, Request::create('/', 'GET', ['range' => 3]));

        $expected = [
            '2019-04-30 23:58' => [
                'label' => 'April 30 - 23:58',
                'value' => 2,
            ],
            '2019-04-30 23:59' => [
                'label' => 'April 30 - 23:59',
                'value' => 2,
            ],
            '2019-05-01 00:00' => [
                'label' => 'May 1 - 0:00',
                'value' => 1,
            ],
        ];
        static::assertEquals($expected, $result->trend);

        Chronos::setTestNow();
    }

    /** @test */
    public function it_can_convert_metric_to_array()
    {
        $metric   = new CountPublishedPostsByHours;
        $expected = [
            'class'  => CountPublishedPostsByHours::class,
            'type'   => 'trend',
            'title'  => 'Count Published Posts By Hours',
            'ranges' => [],
        ];

        static::assertIsTrendMetric($metric);
        static::assertEquals($expected, $metric->toArray());
    }

    /** @test */
    public function it_can_calculate_using_default_timezone()
    {
        Chronos::setTestNow(Chronos::parse('Dec 14 2019', 'UTC'));

        $now        = Chronos::parse('Nov 1 2019 6:30 AM', 'UTC');
        $nowCentral = Chronos::parse('Nov 2 2019 12 AM', 'UTC');

        factory(User::class, 2)->create(['created_at' => $now]);
        factory(User::class, 5)->create(['created_at' => $nowCentral]);

        $metric = new class extends Trend {
            public function calculate(Request $request)
            {
                return $this->countByMonths(User::class);
            }
        };

        $result = $this->calculate($metric, Request::create('/?range=2', 'GET', ['timezone' => 'America/Chicago']));
        static::assertEquals([0, 7], Arr::pluck($result->trend, 'value'));

        $result = $this->calculate($metric, Request::create('/?range=2', 'GET', ['timezone' => 'America/Los_Angeles']));
        static::assertEquals([2, 5], Arr::pluck($result->trend, 'value'));

        Chronos::setTestNow();
    }

    /** @test */
    public function it_can_calculate_using_custom_timezone()
    {
        Chronos::setTestNow(Chronos::parse('Dec 14 2019', 'UTC'));

        $now        = Chronos::parse('Nov 1 2019 6:30 AM', 'UTC');
        $nowCentral = Chronos::parse('Nov 2 2019 12 AM', 'UTC');

        factory(User::class, 2)->create(['created_at' => $now]);
        factory(User::class, 5)->create(['created_at' => $nowCentral]);

        $metric = new class extends Trend {
            public function calculate(Request $request)
            {
                return $this->countByMonths(User::class);
            }

            protected function getCurrentTimezone(Request $request)
            {
                return 'UTC';
            }
        };

        $result = $this->calculate($metric, Request::create('/?range=2', 'GET', ['timezone' => 'America/Chicago']));
        static::assertEquals([7, 0], Arr::pluck($result->trend, 'value'));

        $result = $this->calculate($metric, Request::create('/?range=2', 'GET', ['timezone' => 'America/Los_Angeles']));
        static::assertEquals([7, 0], Arr::pluck($result->trend, 'value'));

        Chronos::setTestNow();
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
