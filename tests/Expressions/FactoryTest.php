<?php namespace Arcanedev\LaravelMetrics\Tests\Expressions;

use Arcanedev\LaravelMetrics\Expressions\Factory;
use Arcanedev\LaravelMetrics\Metrics\Trend;
use Arcanedev\LaravelMetrics\Tests\Stubs\Models\Post;
use Arcanedev\LaravelMetrics\Tests\TestCase;

/**
 * Class     FactoryTest
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Expressions
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class FactoryTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_get_if_null_expression()
    {
        $expectations = [
            'mariadb' => 'IF(ISNULL(`activated_at`), 0, 1)',
            'mysql'   => 'IF(ISNULL(`activated_at`), 0, 1)',
            'pgsql'   => 'CASE WHEN activated_at IS NULL THEN 0 ELSE 1 END',
            'sqlite'  => 'CASE WHEN `activated_at` IS NULL THEN 0 ELSE 1 END',
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
    public function it_can_get_date_format_expression_for_trends($driver, $unit, $expected)
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
    public function it_can_get_date_format_expression_for_trends_with_timezones($driver, $unit, array $timezones)
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
    public function it_must_throw_an_exception_on_invalid_unit(string $driver)
    {
        $this->expectException(\Arcanedev\LaravelMetrics\Exceptions\InvalidTrendUnitException::class);
        $this->expectExceptionMessage('Invalid trend unit provided [centuries]');

        Factory::make($driver, 'trend_date_format', 'published_at', ['centuries', Post::query()])->getValue();
    }

    /** @test */
    public function it_must_throw_an_exception_on_invalid_driver()
    {
        $this->expectException(\Arcanedev\LaravelMetrics\Exceptions\ExpressionNotFound::class);
        $this->expectExceptionMessage('Expression `trend_date_format` not found for `nosql` driver');

        Factory::make('nosql', 'trend_date_format', 'created_at');
    }

    /** @test */
    public function it_must_throw_an_exception_on_invalid_name()
    {
        $this->expectException(\Arcanedev\LaravelMetrics\Exceptions\ExpressionNotFound::class);
        $this->expectExceptionMessage('Expression `invalid` not found for `mysql` driver');

        Factory::make('mysql', 'invalid', 'created_at');
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
    public function provideDatabaseDrivers()
    {
        return [
            ['mariadb'],
            ['mysql'],
            ['pgsql'],
            ['sqlite'],
        ];
    }

    /**
     * Get the trend date format for tests.
     *
     * @return array
     */
    public function provideDateFormatForTrendWithoutTimezones()
    {
        return [
            // BY MONTHS
            ['mariadb', Trend::BY_MONTHS, 'date_format("published_at", \'%Y-%m\')'],
            ['mysql', Trend::BY_MONTHS, 'date_format("published_at", \'%Y-%m\')'],
            ['pgsql', Trend::BY_MONTHS, 'to_char("published_at", \'YYYY-MM\')'],
            ['sqlite', Trend::BY_MONTHS, 'strftime(\'%Y-%m\', datetime("published_at", \'+0 hour\'))'],

            // BY WEEKS
            ['mariadb', Trend::BY_WEEKS, 'date_format("published_at", \'%x-%v\')'],
            ['mysql', Trend::BY_WEEKS, 'date_format("published_at", \'%x-%v\')'],
            ['pgsql', Trend::BY_WEEKS, 'to_char("published_at", \'IYYY-IW\')'],
            ['sqlite', Trend::BY_WEEKS, 'strftime(\'%Y-%W\', datetime("published_at", \'+0 hour\'))'],

            // BY DAYS
            ['mariadb', Trend::BY_DAYS, 'date_format("published_at", \'%Y-%m-%d\')'],
            ['mysql', Trend::BY_DAYS, 'date_format("published_at", \'%Y-%m-%d\')'],
            ['pgsql', Trend::BY_DAYS, 'to_char("published_at", \'YYYY-MM-DD\')'],
            ['sqlite', Trend::BY_DAYS, 'strftime(\'%Y-%m-%d\', datetime("published_at", \'+0 hour\'))'],

            // BY HOURS
            ['mariadb', Trend::BY_HOURS, 'date_format("published_at", \'%Y-%m-%d %H:00\')'],
            ['mysql', Trend::BY_HOURS, 'date_format("published_at", \'%Y-%m-%d %H:00\')'],
            ['pgsql', Trend::BY_HOURS, 'to_char("published_at", \'YYYY-MM-DD HH24:00\')'],
            ['sqlite', Trend::BY_HOURS, 'strftime(\'%Y-%m-%d %H:00\', datetime("published_at", \'+0 hour\'))'],

            // BY MINUTES
            ['mariadb', Trend::BY_MINUTES, 'date_format("published_at", \'%Y-%m-%d %H:%i:00\')'],
            ['mysql', Trend::BY_MINUTES, 'date_format("published_at", \'%Y-%m-%d %H:%i:00\')'],
            ['pgsql', Trend::BY_MINUTES, 'to_char("published_at", \'YYYY-MM-DD HH24:mi:00\')'],
            ['sqlite', Trend::BY_MINUTES, 'strftime(\'%Y-%m-%d %H:%M:00\', datetime("published_at", \'+0 hour\'))'],
        ];
    }

    public function provideDateFormatForTrendWithTimezones()
    {
        return [
            // BY MONTHS
            [
                'mariadb', Trend::BY_MONTHS, [
                    'UTC'              => 'date_format("published_at", \'%Y-%m\')',
                    'Asia/Tokyo'       => 'date_format("published_at" + INTERVAL 9 HOUR, \'%Y-%m\')',
                    'America/New_York' => 'date_format("published_at" - INTERVAL 4 HOUR, \'%Y-%m\')',
                ],
            ],
            [
                'mysql', Trend::BY_MONTHS, [
                    'UTC'              => 'date_format("published_at", \'%Y-%m\')',
                    'Asia/Tokyo'       => 'date_format("published_at" + INTERVAL 9 HOUR, \'%Y-%m\')',
                    'America/New_York' => 'date_format("published_at" - INTERVAL 4 HOUR, \'%Y-%m\')',
                ],
            ],
            [
                'pgsql', Trend::BY_MONTHS, [
                    'UTC'              => 'to_char("published_at", \'YYYY-MM\')',
                    'Asia/Tokyo'       => 'to_char("published_at" + interval \'9 hour\', \'YYYY-MM\')',
                    'America/New_York' => 'to_char("published_at" - interval \'4 HOUR\', \'YYYY-MM\')',
                ]
            ],
            [
                'sqlite', Trend::BY_MONTHS, [
                    'UTC'              => 'strftime(\'%Y-%m\', datetime("published_at", \'+0 hour\'))',
                    'Asia/Tokyo'       => 'strftime(\'%Y-%m\', datetime("published_at", \'+9 hour\'))',
                    'America/New_York' => 'strftime(\'%Y-%m\', datetime("published_at", \'-4 hour\'))',
                ],
            ],

            // BY WEEKS
            [
                'mariadb', Trend::BY_WEEKS, [
                    'UTC'              => 'date_format("published_at", \'%x-%v\')',
                    'Asia/Tokyo'       => 'date_format("published_at" + INTERVAL 9 HOUR, \'%x-%v\')',
                    'America/New_York' => 'date_format("published_at" - INTERVAL 4 HOUR, \'%x-%v\')',
                ],
            ],
            [
                'mysql', Trend::BY_WEEKS, [
                    'UTC'              => 'date_format("published_at", \'%x-%v\')',
                    'Asia/Tokyo'       => 'date_format("published_at" + INTERVAL 9 HOUR, \'%x-%v\')',
                    'America/New_York' => 'date_format("published_at" - INTERVAL 4 HOUR, \'%x-%v\')',
                ],
            ],
            [
                'pgsql', Trend::BY_WEEKS, [
                    'UTC'              => 'to_char("published_at", \'IYYY-IW\')',
                    'Asia/Tokyo'       => 'to_char("published_at" + interval \'9 hour\', \'IYYY-IW\')',
                    'America/New_York' => 'to_char("published_at" - interval \'4 HOUR\', \'IYYY-IW\')',
                ],
            ],
            [
                'sqlite', Trend::BY_WEEKS, [
                    'UTC'              => 'strftime(\'%Y-%W\', datetime("published_at", \'+0 hour\'))',
                    'Asia/Tokyo'       => 'strftime(\'%Y-%W\', datetime("published_at", \'+9 hour\'))',
                    'America/New_York' => 'strftime(\'%Y-%W\', datetime("published_at", \'-4 hour\'))',
                ],
            ],

            // BY DAYS
            [
                'mariadb', Trend::BY_DAYS, [
                    'UTC'              => 'date_format("published_at", \'%Y-%m-%d\')',
                    'Asia/Tokyo'       => 'date_format("published_at" + INTERVAL 9 HOUR, \'%Y-%m-%d\')',
                    'America/New_York' => 'date_format("published_at" - INTERVAL 4 HOUR, \'%Y-%m-%d\')',
                ],
            ],
            [
                'mysql', Trend::BY_DAYS, [
                    'UTC'              => 'date_format("published_at", \'%Y-%m-%d\')',
                    'Asia/Tokyo'       => 'date_format("published_at" + INTERVAL 9 HOUR, \'%Y-%m-%d\')',
                    'America/New_York' => 'date_format("published_at" - INTERVAL 4 HOUR, \'%Y-%m-%d\')',
                ],
            ],
            [
                'pgsql', Trend::BY_DAYS, [
                    'UTC'              => 'to_char("published_at", \'YYYY-MM-DD\')',
                    'Asia/Tokyo'       => 'to_char("published_at" + interval \'9 hour\', \'YYYY-MM-DD\')',
                    'America/New_York' => 'to_char("published_at" - interval \'4 HOUR\', \'YYYY-MM-DD\')',
                ],
            ],
            [
                'sqlite', Trend::BY_DAYS, [
                    'UTC'              => 'strftime(\'%Y-%m-%d\', datetime("published_at", \'+0 hour\'))',
                    'Asia/Tokyo'       => 'strftime(\'%Y-%m-%d\', datetime("published_at", \'+9 hour\'))',
                    'America/New_York' => 'strftime(\'%Y-%m-%d\', datetime("published_at", \'-4 hour\'))',
                ],
            ],

            // BY HOURS
            [
                'mariadb', Trend::BY_HOURS, [
                    'UTC'              => 'date_format("published_at", \'%Y-%m-%d %H:00\')',
                    'Asia/Tokyo'       => 'date_format("published_at" + INTERVAL 9 HOUR, \'%Y-%m-%d %H:00\')',
                    'America/New_York' => 'date_format("published_at" - INTERVAL 4 HOUR, \'%Y-%m-%d %H:00\')',
                ],
            ],
            [
                'mysql', Trend::BY_HOURS, [
                    'UTC'              => 'date_format("published_at", \'%Y-%m-%d %H:00\')',
                    'Asia/Tokyo'       => 'date_format("published_at" + INTERVAL 9 HOUR, \'%Y-%m-%d %H:00\')',
                    'America/New_York' => 'date_format("published_at" - INTERVAL 4 HOUR, \'%Y-%m-%d %H:00\')',
                ],
            ],
            [
                'pgsql', Trend::BY_HOURS, [
                    'UTC'              => 'to_char("published_at", \'YYYY-MM-DD HH24:00\')',
                    'Asia/Tokyo'       => 'to_char("published_at" + interval \'9 hour\', \'YYYY-MM-DD HH24:00\')',
                    'America/New_York' => 'to_char("published_at" - interval \'4 HOUR\', \'YYYY-MM-DD HH24:00\')',
                ],
            ],
            [
                'sqlite', Trend::BY_HOURS, [
                    'UTC'              => 'strftime(\'%Y-%m-%d %H:00\', datetime("published_at", \'+0 hour\'))',
                    'Asia/Tokyo'       => 'strftime(\'%Y-%m-%d %H:00\', datetime("published_at", \'+9 hour\'))',
                    'America/New_York' => 'strftime(\'%Y-%m-%d %H:00\', datetime("published_at", \'-4 hour\'))',
                ],
            ],

            // BY MINUTES
            [
                'mariadb', Trend::BY_MINUTES, [
                    'UTC'              => 'date_format("published_at", \'%Y-%m-%d %H:%i:00\')',
                    'Asia/Tokyo'       => 'date_format("published_at" + INTERVAL 9 HOUR, \'%Y-%m-%d %H:%i:00\')',
                    'America/New_York' => 'date_format("published_at" - INTERVAL 4 HOUR, \'%Y-%m-%d %H:%i:00\')',
                ],
            ],
            [
                'mysql', Trend::BY_MINUTES, [
                    'UTC'              => 'date_format("published_at", \'%Y-%m-%d %H:%i:00\')',
                    'Asia/Tokyo'       => 'date_format("published_at" + INTERVAL 9 HOUR, \'%Y-%m-%d %H:%i:00\')',
                    'America/New_York' => 'date_format("published_at" - INTERVAL 4 HOUR, \'%Y-%m-%d %H:%i:00\')',
                ],
            ],
            [
                'pgsql', Trend::BY_MINUTES, [
                    'UTC'              => 'to_char("published_at", \'YYYY-MM-DD HH24:mi:00\')',
                    'Asia/Tokyo'       => 'to_char("published_at" + interval \'9 hour\', \'YYYY-MM-DD HH24:mi:00\')',
                    'America/New_York' => 'to_char("published_at" - interval \'4 HOUR\', \'YYYY-MM-DD HH24:mi:00\')',
                ],
            ],
            [
                'sqlite', Trend::BY_MINUTES, [
                    'UTC'              => 'strftime(\'%Y-%m-%d %H:%M:00\', datetime("published_at", \'+0 hour\'))',
                    'Asia/Tokyo'       => 'strftime(\'%Y-%m-%d %H:%M:00\', datetime("published_at", \'+9 hour\'))',
                    'America/New_York' => 'strftime(\'%Y-%m-%d %H:%M:00\', datetime("published_at", \'-4 hour\'))',
                ],
            ],
        ];
    }
}
