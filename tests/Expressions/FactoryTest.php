<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Expressions;

use Arcanedev\LaravelMetrics\Exceptions\{ExpressionNotFound, InvalidTrendUnitException};
use Arcanedev\LaravelMetrics\Expressions\Factory;
use Arcanedev\LaravelMetrics\Metrics\Trend;
use Arcanedev\LaravelMetrics\Tests\Stubs\Models\Post;
use Arcanedev\LaravelMetrics\Tests\TestCase;

/**
 * Class     FactoryTest
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class FactoryTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_get_if_null_expression(): void
    {
        $expectations = [
            'mariadb' => 'IF(ISNULL(`activated_at`), 0, 1)',
            'mysql'   => 'IF(ISNULL(`activated_at`), 0, 1)',
            'pgsql'   => 'CASE WHEN activated_at IS NULL THEN 0 ELSE 1 END',
            'sqlite'  => 'CASE WHEN `activated_at` IS NULL THEN 0 ELSE 1 END',
            'sqlsrv'  => 'CASE WHEN activated_at IS NULL THEN 0 ELSE 1 END',
        ];

        foreach ($expectations as $driver => $expected) {
            static::assertSame(
                $expected,
                Factory::make($driver, 'if_null', 'activated_at')->getValue(),
                "Fails on driver: {$driver}"
            );
        }
    }

    /**
     * @test
     *
     * @dataProvider provideDateFormatForTrendWithoutTimezones
     *
     * @param  string  $driver
     * @param  string  $unit
     * @param  string  $expected
     */
    public function it_can_get_date_format_expression_for_trends($driver, $unit, $expected): void
    {
        static::assertSame(
            $expected,
            Factory::make($driver, 'trend_date_format', 'published_at', [$unit, Post::query()])->getValue(),
            "Fails on driver [{$driver}], unit [{$unit}]"
        );
    }

    /**
     * @test
     *
     * @dataProvider provideDateFormatForTrendWithTimezones
     *
     * @param  string  $driver
     * @param  string  $unit
     * @param  array   $timezones
     */
    public function it_can_get_date_format_expression_for_trends_with_timezones($driver, $unit, array $timezones): void
    {
        foreach ($timezones as $timezone => $expected) {
            static::assertSame(
                $expected,
                Factory::make($driver, 'trend_date_format', 'published_at', [$unit, Post::query(), $timezone])->getValue(),
                "Fails on driver [{$driver}], unit [{$unit}], timezone [{$timezone}]"
            );
        }
    }

    /**
     * @test
     *
     * @dataProvider provideDatabaseDrivers
     *
     * @param  string  $driver
     */
    public function it_must_throw_an_exception_on_invalid_unit(string $driver): void
    {
        $this->expectException(InvalidTrendUnitException::class);
        $this->expectExceptionMessage('Invalid trend unit provided [centuries]');

        Factory::make($driver, 'trend_date_format', 'published_at', ['centuries', Post::query()])->getValue();
    }

    /** @test */
    public function it_must_throw_an_exception_on_invalid_driver(): void
    {
        $this->expectException(ExpressionNotFound::class);
        $this->expectExceptionMessage('Expression `trend_date_format` not found for `nosql` driver');

        Factory::make('nosql', 'trend_date_format', 'created_at');
    }

    /** @test */
    public function it_must_throw_an_exception_on_invalid_name(): void
    {
        $this->expectException(ExpressionNotFound::class);
        $this->expectExceptionMessage('Expression `invalid` not found for `mysql` driver');

        Factory::make('mysql', 'invalid', 'created_at');
    }

    /** @test */
    public function it_can_register_a_custom_expression(): void
    {
        Factory::macro('mysql', function ($name, $value, $params) {
            FactoryTest::assertSame('mysql', $name);
            FactoryTest::assertSame('value', $value);
            FactoryTest::assertEquals(['foo' => 'bar'], $params);

            return 'custom expression';
        });

        $query = (new Post)->setConnection('mysql')->newQuery();

        static::assertSame(
            'custom expression',
            Factory::resolveExpression($query, 'mysql', 'value', ['foo' => 'bar'])
        );
    }

    /* -----------------------------------------------------------------
     |  Data Providers
     | -----------------------------------------------------------------
     */

    /**
     * Get the supported drivers for trend date format.
     *
     * @return array
     */
    public function provideDatabaseDrivers(): array
    {
        return [
            ['mariadb'],
            ['mysql'],
            ['pgsql'],
            ['sqlite'],
            ['sqlsrv'],
        ];
    }

    /**
     * Get the trend date format for tests.
     *
     * @return array
     */
    public function provideDateFormatForTrendWithoutTimezones(): array
    {
        return [
            // BY MONTHS
            ['mariadb', Trend::BY_MONTHS, 'date_format("published_at", \'%Y-%m\')'],
            ['mysql', Trend::BY_MONTHS, 'date_format("published_at", \'%Y-%m\')'],
            ['pgsql', Trend::BY_MONTHS, 'to_char("published_at", \'YYYY-MM\')'],
            ['sqlite', Trend::BY_MONTHS, 'strftime(\'%Y-%m\', datetime("published_at", \'+0 hour\'))'],
            ['sqlsrv', Trend::BY_MONTHS, 'FORMAT(DATEADD(hour, 0, "published_at"), \'yyyy-MM\')'],

            // BY WEEKS
            ['mariadb', Trend::BY_WEEKS, 'date_format("published_at", \'%x-%v\')'],
            ['mysql', Trend::BY_WEEKS, 'date_format("published_at", \'%x-%v\')'],
            ['pgsql', Trend::BY_WEEKS, 'to_char("published_at", \'IYYY-IW\')'],
            ['sqlite', Trend::BY_WEEKS, 'strftime(\'%Y\', datetime("published_at", \'+0 hour\')) || \'-\' || (strftime(\'%W\', datetime("published_at", \'+0 hour\')) + (1 - strftime(\'%W\', strftime(\'%Y\', datetime("published_at")) || \'-01-04\')))'],
            ['sqlsrv', Trend::BY_WEEKS, 'concat(YEAR(DATEADD(hour, 0, "published_at")), \'-\', datepart(ISO_WEEK, DATEADD(hour, 0, "published_at")))'],

            // BY DAYS
            ['mariadb', Trend::BY_DAYS, 'date_format("published_at", \'%Y-%m-%d\')'],
            ['mysql', Trend::BY_DAYS, 'date_format("published_at", \'%Y-%m-%d\')'],
            ['pgsql', Trend::BY_DAYS, 'to_char("published_at", \'YYYY-MM-DD\')'],
            ['sqlite', Trend::BY_DAYS, 'strftime(\'%Y-%m-%d\', datetime("published_at", \'+0 hour\'))'],
            ['sqlsrv', Trend::BY_DAYS, 'FORMAT(DATEADD(hour, 0, "published_at"), \'yyyy-MM-dd\')'],

            // BY HOURS
            ['mariadb', Trend::BY_HOURS, 'date_format("published_at", \'%Y-%m-%d %H:00\')'],
            ['mysql', Trend::BY_HOURS, 'date_format("published_at", \'%Y-%m-%d %H:00\')'],
            ['pgsql', Trend::BY_HOURS, 'to_char("published_at", \'YYYY-MM-DD HH24:00\')'],
            ['sqlite', Trend::BY_HOURS, 'strftime(\'%Y-%m-%d %H:00\', datetime("published_at", \'+0 hour\'))'],
            ['sqlsrv', Trend::BY_HOURS, 'FORMAT(DATEADD(hour, 0, "published_at"), \'yyyy-MM-dd HH:00\')'],

            // BY MINUTES
            ['mariadb', Trend::BY_MINUTES, 'date_format("published_at", \'%Y-%m-%d %H:%i:00\')'],
            ['mysql', Trend::BY_MINUTES, 'date_format("published_at", \'%Y-%m-%d %H:%i:00\')'],
            ['pgsql', Trend::BY_MINUTES, 'to_char("published_at", \'YYYY-MM-DD HH24:mi:00\')'],
            ['sqlite', Trend::BY_MINUTES, 'strftime(\'%Y-%m-%d %H:%M:00\', datetime("published_at", \'+0 hour\'))'],
            ['sqlsrv', Trend::BY_MINUTES, 'FORMAT(DATEADD(hour, 0, "published_at"), \'yyyy-MM-dd HH:mm:00\')'],
        ];
    }

    /**
     * @return array
     */
    public function provideDateFormatForTrendWithTimezones(): array
    {
        return [
            // BY MONTHS
            [
                'mariadb', Trend::BY_MONTHS, [
                    'UTC'              => 'date_format("published_at", \'%Y-%m\')',
                    'Asia/Tokyo'       => 'date_format("published_at" + INTERVAL 9 HOUR, \'%Y-%m\')',
                    'Pacific/Honolulu' => 'date_format("published_at" - INTERVAL 10 HOUR, \'%Y-%m\')',
                ],
            ],
            [
                'mysql', Trend::BY_MONTHS, [
                    'UTC'              => 'date_format("published_at", \'%Y-%m\')',
                    'Asia/Tokyo'       => 'date_format("published_at" + INTERVAL 9 HOUR, \'%Y-%m\')',
                    'Pacific/Honolulu' => 'date_format("published_at" - INTERVAL 10 HOUR, \'%Y-%m\')',
                ],
            ],
            [
                'pgsql', Trend::BY_MONTHS, [
                    'UTC'              => 'to_char("published_at", \'YYYY-MM\')',
                    'Asia/Tokyo'       => 'to_char("published_at" + interval \'9 hour\', \'YYYY-MM\')',
                    'Pacific/Honolulu' => 'to_char("published_at" - interval \'10 HOUR\', \'YYYY-MM\')',
                ]
            ],
            [
                'sqlite', Trend::BY_MONTHS, [
                    'UTC'              => 'strftime(\'%Y-%m\', datetime("published_at", \'+0 hour\'))',
                    'Asia/Tokyo'       => 'strftime(\'%Y-%m\', datetime("published_at", \'+9 hour\'))',
                    'Pacific/Honolulu' => 'strftime(\'%Y-%m\', datetime("published_at", \'-10 hour\'))',
                ],
            ],
            [
                'sqlsrv', Trend::BY_MONTHS, [
                    'UTC'              => 'FORMAT(DATEADD(hour, 0, "published_at"), \'yyyy-MM\')',
                    'Asia/Tokyo'       => 'FORMAT(DATEADD(hour, 9, "published_at"), \'yyyy-MM\')',
                    'Pacific/Honolulu' => 'FORMAT(DATEADD(hour, -10, "published_at"), \'yyyy-MM\')',
                ],
            ],

            // BY WEEKS
            [
                'mariadb', Trend::BY_WEEKS, [
                    'UTC'              => 'date_format("published_at", \'%x-%v\')',
                    'Asia/Tokyo'       => 'date_format("published_at" + INTERVAL 9 HOUR, \'%x-%v\')',
                    'Pacific/Honolulu' => 'date_format("published_at" - INTERVAL 10 HOUR, \'%x-%v\')',
                ],
            ],
            [
                'mysql', Trend::BY_WEEKS, [
                    'UTC'              => 'date_format("published_at", \'%x-%v\')',
                    'Asia/Tokyo'       => 'date_format("published_at" + INTERVAL 9 HOUR, \'%x-%v\')',
                    'Pacific/Honolulu' => 'date_format("published_at" - INTERVAL 10 HOUR, \'%x-%v\')',
                ],
            ],
            [
                'pgsql', Trend::BY_WEEKS, [
                    'UTC'              => 'to_char("published_at", \'IYYY-IW\')',
                    'Asia/Tokyo'       => 'to_char("published_at" + interval \'9 hour\', \'IYYY-IW\')',
                    'Pacific/Honolulu' => 'to_char("published_at" - interval \'10 HOUR\', \'IYYY-IW\')',
                ],
            ],
            [
                'sqlite', Trend::BY_WEEKS, [
                    'UTC'              => 'strftime(\'%Y\', datetime("published_at", \'+0 hour\')) || \'-\' || (strftime(\'%W\', datetime("published_at", \'+0 hour\')) + (1 - strftime(\'%W\', strftime(\'%Y\', datetime("published_at")) || \'-01-04\')))',
                    'Asia/Tokyo'       => 'strftime(\'%Y\', datetime("published_at", \'+9 hour\')) || \'-\' || (strftime(\'%W\', datetime("published_at", \'+9 hour\')) + (1 - strftime(\'%W\', strftime(\'%Y\', datetime("published_at")) || \'-01-04\')))',
                    'Pacific/Honolulu' => 'strftime(\'%Y\', datetime("published_at", \'-10 hour\')) || \'-\' || (strftime(\'%W\', datetime("published_at", \'-10 hour\')) + (1 - strftime(\'%W\', strftime(\'%Y\', datetime("published_at")) || \'-01-04\')))',
                ],
            ],

            [
                'sqlsrv', Trend::BY_WEEKS, [
                    'UTC'              => 'concat(YEAR(DATEADD(hour, 0, "published_at")), \'-\', datepart(ISO_WEEK, DATEADD(hour, 0, "published_at")))',
                    'Asia/Tokyo'       => 'concat(YEAR(DATEADD(hour, 9, "published_at")), \'-\', datepart(ISO_WEEK, DATEADD(hour, 9, "published_at")))',
                    'Pacific/Honolulu' => 'concat(YEAR(DATEADD(hour, -10, "published_at")), \'-\', datepart(ISO_WEEK, DATEADD(hour, -10, "published_at")))',
                ],
            ],

            // BY DAYS
            [
                'mariadb', Trend::BY_DAYS, [
                    'UTC'              => 'date_format("published_at", \'%Y-%m-%d\')',
                    'Asia/Tokyo'       => 'date_format("published_at" + INTERVAL 9 HOUR, \'%Y-%m-%d\')',
                    'Pacific/Honolulu' => 'date_format("published_at" - INTERVAL 10 HOUR, \'%Y-%m-%d\')',
                ],
            ],
            [
                'mysql', Trend::BY_DAYS, [
                    'UTC'              => 'date_format("published_at", \'%Y-%m-%d\')',
                    'Asia/Tokyo'       => 'date_format("published_at" + INTERVAL 9 HOUR, \'%Y-%m-%d\')',
                    'Pacific/Honolulu' => 'date_format("published_at" - INTERVAL 10 HOUR, \'%Y-%m-%d\')',
                ],
            ],
            [
                'pgsql', Trend::BY_DAYS, [
                    'UTC'              => 'to_char("published_at", \'YYYY-MM-DD\')',
                    'Asia/Tokyo'       => 'to_char("published_at" + interval \'9 hour\', \'YYYY-MM-DD\')',
                    'Pacific/Honolulu' => 'to_char("published_at" - interval \'10 HOUR\', \'YYYY-MM-DD\')',
                ],
            ],
            [
                'sqlite', Trend::BY_DAYS, [
                    'UTC'              => 'strftime(\'%Y-%m-%d\', datetime("published_at", \'+0 hour\'))',
                    'Asia/Tokyo'       => 'strftime(\'%Y-%m-%d\', datetime("published_at", \'+9 hour\'))',
                    'Pacific/Honolulu' => 'strftime(\'%Y-%m-%d\', datetime("published_at", \'-10 hour\'))',
                ],
            ],
            [
                'sqlsrv', Trend::BY_DAYS, [
                    'UTC'              => 'FORMAT(DATEADD(hour, 0, "published_at"), \'yyyy-MM-dd\')',
                    'Asia/Tokyo'       => 'FORMAT(DATEADD(hour, 9, "published_at"), \'yyyy-MM-dd\')',
                    'Pacific/Honolulu' => 'FORMAT(DATEADD(hour, -10, "published_at"), \'yyyy-MM-dd\')',
                ],
            ],

            // BY HOURS
            [
                'mariadb', Trend::BY_HOURS, [
                    'UTC'              => 'date_format("published_at", \'%Y-%m-%d %H:00\')',
                    'Asia/Tokyo'       => 'date_format("published_at" + INTERVAL 9 HOUR, \'%Y-%m-%d %H:00\')',
                    'Pacific/Honolulu' => 'date_format("published_at" - INTERVAL 10 HOUR, \'%Y-%m-%d %H:00\')',
                ],
            ],
            [
                'mysql', Trend::BY_HOURS, [
                    'UTC'              => 'date_format("published_at", \'%Y-%m-%d %H:00\')',
                    'Asia/Tokyo'       => 'date_format("published_at" + INTERVAL 9 HOUR, \'%Y-%m-%d %H:00\')',
                    'Pacific/Honolulu' => 'date_format("published_at" - INTERVAL 10 HOUR, \'%Y-%m-%d %H:00\')',
                ],
            ],
            [
                'pgsql', Trend::BY_HOURS, [
                    'UTC'              => 'to_char("published_at", \'YYYY-MM-DD HH24:00\')',
                    'Asia/Tokyo'       => 'to_char("published_at" + interval \'9 hour\', \'YYYY-MM-DD HH24:00\')',
                    'Pacific/Honolulu' => 'to_char("published_at" - interval \'10 HOUR\', \'YYYY-MM-DD HH24:00\')',
                ],
            ],
            [
                'sqlite', Trend::BY_HOURS, [
                    'UTC'              => 'strftime(\'%Y-%m-%d %H:00\', datetime("published_at", \'+0 hour\'))',
                    'Asia/Tokyo'       => 'strftime(\'%Y-%m-%d %H:00\', datetime("published_at", \'+9 hour\'))',
                    'Pacific/Honolulu' => 'strftime(\'%Y-%m-%d %H:00\', datetime("published_at", \'-10 hour\'))',
                ],
            ],
            [
                'sqlsrv', Trend::BY_HOURS, [
                    'UTC'              => 'FORMAT(DATEADD(hour, 0, "published_at"), \'yyyy-MM-dd HH:00\')',
                    'Asia/Tokyo'       => 'FORMAT(DATEADD(hour, 9, "published_at"), \'yyyy-MM-dd HH:00\')',
                    'Pacific/Honolulu' => 'FORMAT(DATEADD(hour, -10, "published_at"), \'yyyy-MM-dd HH:00\')',
                ],
            ],

            // BY MINUTES
            [
                'mariadb', Trend::BY_MINUTES, [
                    'UTC'              => 'date_format("published_at", \'%Y-%m-%d %H:%i:00\')',
                    'Asia/Tokyo'       => 'date_format("published_at" + INTERVAL 9 HOUR, \'%Y-%m-%d %H:%i:00\')',
                    'Pacific/Honolulu' => 'date_format("published_at" - INTERVAL 10 HOUR, \'%Y-%m-%d %H:%i:00\')',
                ],
            ],
            [
                'mysql', Trend::BY_MINUTES, [
                    'UTC'              => 'date_format("published_at", \'%Y-%m-%d %H:%i:00\')',
                    'Asia/Tokyo'       => 'date_format("published_at" + INTERVAL 9 HOUR, \'%Y-%m-%d %H:%i:00\')',
                    'Pacific/Honolulu' => 'date_format("published_at" - INTERVAL 10 HOUR, \'%Y-%m-%d %H:%i:00\')',
                ],
            ],
            [
                'pgsql', Trend::BY_MINUTES, [
                    'UTC'              => 'to_char("published_at", \'YYYY-MM-DD HH24:mi:00\')',
                    'Asia/Tokyo'       => 'to_char("published_at" + interval \'9 hour\', \'YYYY-MM-DD HH24:mi:00\')',
                    'Pacific/Honolulu' => 'to_char("published_at" - interval \'10 HOUR\', \'YYYY-MM-DD HH24:mi:00\')',
                ],
            ],
            [
                'sqlite', Trend::BY_MINUTES, [
                    'UTC'              => 'strftime(\'%Y-%m-%d %H:%M:00\', datetime("published_at", \'+0 hour\'))',
                    'Asia/Tokyo'       => 'strftime(\'%Y-%m-%d %H:%M:00\', datetime("published_at", \'+9 hour\'))',
                    'Pacific/Honolulu' => 'strftime(\'%Y-%m-%d %H:%M:00\', datetime("published_at", \'-10 hour\'))',
                ],
            ],
            [
                'sqlsrv', Trend::BY_MINUTES, [
                    'UTC'              => 'FORMAT(DATEADD(hour, 0, "published_at"), \'yyyy-MM-dd HH:mm:00\')',
                    'Asia/Tokyo'       => 'FORMAT(DATEADD(hour, 9, "published_at"), \'yyyy-MM-dd HH:mm:00\')',
                    'Pacific/Honolulu' => 'FORMAT(DATEADD(hour, -10, "published_at"), \'yyyy-MM-dd HH:mm:00\')',
                ],
            ],
        ];
    }
}
