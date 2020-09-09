<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Metrics;

use Arcanedev\LaravelMetrics\Metrics\Trend;
use Arcanedev\LaravelMetrics\Results\TrendResult;
use Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Trend\{
    CountPublishedPostsByDays, CountPublishedPostsByHours, CountPublishedPostsByMinutes, CountPublishedPostsByMonths,
    CountPublishedPostsByWeeks
};
use Arcanedev\LaravelMetrics\Tests\Stubs\Database\Factories\{PostFactory, UserFactory};
use Arcanedev\LaravelMetrics\Tests\Stubs\Models\{Post, User};
use Cake\Chronos\Chronos;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**
 * Class     TrendTest
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TrendTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Count Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_calculate_count_by_months(): void
    {
        Chronos::setTestNow($now = Chronos::create(2019, 5, 1, 0, 0, 0));

        PostFactory::new(['views' => 10, 'published_at' => $now])->create();
        PostFactory::new(['views' => 50, 'published_at' => $now->subMonths(1)])->create();
        PostFactory::new(['views' => 40, 'published_at' => $now->subMonths(1)])->create();
        PostFactory::new(['views' => 30, 'published_at' => $now->subMonths(2)])->create();
        PostFactory::new(['views' => 20, 'published_at' => $now->subMonths(2)])->create();

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
    public function it_can_calculate_count_by_weeks(): void
    {
        Chronos::setTestNow($now = Chronos::create(2019, 5, 5, 0, 0, 0));

        PostFactory::new(['views' => 10, 'published_at' => $now])->create();
        PostFactory::new(['views' => 50, 'published_at' => $now->subWeeks(1)])->create();
        PostFactory::new(['views' => 40, 'published_at' => $now->subWeeks(1)])->create();
        PostFactory::new(['views' => 30, 'published_at' => $now->subWeeks(2)])->create();
        PostFactory::new(['views' => 20, 'published_at' => $now->subWeeks(2)])->create();

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
    public function it_can_calculate_count_by_days(): void
    {
        Chronos::setTestNow($now = Chronos::create(2019, 5, 1, 0, 0, 0));

        PostFactory::new(['views' => 10, 'published_at' => $now])->create();
        PostFactory::new(['views' => 50, 'published_at' => $now->subDays(1)])->create();
        PostFactory::new(['views' => 40, 'published_at' => $now->subDays(1)])->create();
        PostFactory::new(['views' => 30, 'published_at' => $now->subDays(2)])->create();
        PostFactory::new(['views' => 20, 'published_at' => $now->subDays(2)])->create();

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
    public function it_can_calculate_count_by_hours(): void
    {
        Chronos::setTestNow($now = Chronos::create(2019, 5, 1, 0, 0, 0));

        PostFactory::new(['views' => 10, 'published_at' => $now])->create();
        PostFactory::new(['views' => 50, 'published_at' => $now->subHours(1)])->create();
        PostFactory::new(['views' => 40, 'published_at' => $now->subHours(1)])->create();
        PostFactory::new(['views' => 30, 'published_at' => $now->subHours(2)])->create();
        PostFactory::new(['views' => 20, 'published_at' => $now->subHours(2)])->create();

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
    public function it_can_calculate_count_by_minutes(): void
    {
        Chronos::setTestNow($now = Chronos::create(2019, 5, 1, 0, 0, 0));

        PostFactory::new(['views' => 10, 'published_at' => $now])->create();
        PostFactory::new(['views' => 50, 'published_at' => $now->subMinutes(1)])->create();
        PostFactory::new(['views' => 40, 'published_at' => $now->subMinutes(1)])->create();
        PostFactory::new(['views' => 30, 'published_at' => $now->subMinutes(2)])->create();
        PostFactory::new(['views' => 20, 'published_at' => $now->subMinutes(2)])->create();

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
    public function it_can_convert_metric_to_array(): void
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
    public function it_can_calculate_using_default_timezone(): void
    {
        Chronos::setTestNow(Chronos::parse('Dec 14 2019', 'UTC'));

        $now        = Chronos::parse('Nov 1 2019 6:30 AM', 'UTC');
        $nowCentral = Chronos::parse('Nov 2 2019 12 AM', 'UTC');

        UserFactory::new(['created_at' => $now])->count(2)->create();
        UserFactory::new(['created_at' => $nowCentral])->count(5)->create();

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
    public function it_can_calculate_using_custom_timezone(): void
    {
        Chronos::setTestNow(Chronos::parse('Dec 14 2019', 'UTC'));

        $now        = Chronos::parse('Nov 1 2019 6:30 AM', 'UTC');
        $nowCentral = Chronos::parse('Nov 2 2019 12 AM', 'UTC');

        UserFactory::new(['created_at' => $now])->count(2)->create();
        UserFactory::new(['created_at' => $nowCentral])->count(5)->create();

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

    /** @test */
    public function it_can_calculate_using_default_rounding_precision(): void
    {
        PostFactory::new(['views' => 5.4321])->count(2)->create();

        $result = $this->calculate(
            new class extends Trend {
                public function calculate(Request $request)
                {
                    return $this->average(Trend::BY_MONTHS, Post::class, 'views');
                }
            },
            Request::create('/', 'GET', ['range' => 1])
        );

        static::assertEquals(5, Arr::first($result->trend)['value']);
    }

    /** @test */
    public function it_can_calculate_using_custom_rounding_precision(): void
    {
        PostFactory::new(['views' => 5.4321])->count(2)->create();

        $result = $this->calculate(
            new class extends Trend {
                public $roundingPrecision = 2;

                public function calculate(Request $request)
                {
                    return $this->average(Trend::BY_MONTHS, Post::class, 'views');
                }
            },
            Request::create('/', 'GET', ['range' => 1])
        );

        static::assertSame(5.43, Arr::first($result->trend)['value']);
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
    private static function assertIsTrendMetric($metric): void
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
    protected static function assertIsTrendResult($actual, string $message = ''): void
    {
        static::assertInstanceOf(TrendResult::class, $actual, $message);
    }
}
