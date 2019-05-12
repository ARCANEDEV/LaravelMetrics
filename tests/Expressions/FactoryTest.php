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
     * @dataProvider provideDateFormatForTrend
     *
     * @param  string  $driver
     * @param  string  $unit
     * @param  mixed   $timezone
     * @param  string  $expected
     */
    public function it_can_get_date_format_expression_for_trends($driver, $unit, $timezone, $expected)
    {
        static::assertSame(
            $expected,
            Factory::make($driver, 'trend_date_format', 'published_at', [$unit, Post::query(), $timezone])->getValue(),
            "Fails on driver [{$driver}], unit [{$unit}]"
        );
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
     * Get the trend date format for tests.
     *
     * @return array
     */
    public function provideDateFormatForTrend()
    {
        return [
            // BY MONTHS WITHOUT TIMEZONE
            ['mariadb', Trend::BY_MONTHS, null, 'date_format("published_at", \'%Y-%m\')'],
            ['mysql', Trend::BY_MONTHS, null, 'date_format("published_at", \'%Y-%m\')'],
            ['pgsql', Trend::BY_MONTHS, null, 'to_char("published_at", \'YYYY-MM\')'],
            ['sqlite', Trend::BY_MONTHS, null, 'strftime(\'%Y-%m\', datetime("published_at", \'+0 hour\'))'],

            // BY MONTHS WITH TIMEZONE
            ['mariadb', Trend::BY_MONTHS, 'UTC', 'date_format("published_at", \'%Y-%m\')'],
            ['mysql', Trend::BY_MONTHS, 'Europe/Paris', 'date_format("published_at" + INTERVAL 2 HOUR, \'%Y-%m\')'],
            ['pgsql', Trend::BY_MONTHS, 'Asia/Tokyo', 'to_char("published_at" + interval \'9 hour\', \'YYYY-MM\')'],
            ['sqlite', Trend::BY_MONTHS, 'America/New_York', 'strftime(\'%Y-%m\', datetime("published_at", \'-4 hour\'))'],

            // BY WEEKS WITHOUT TIMEZONE
            ['mariadb', Trend::BY_WEEKS, null, 'date_format("published_at", \'%x-%v\')'],
            ['mysql', Trend::BY_WEEKS, null, 'date_format("published_at", \'%x-%v\')'],
            ['pgsql', Trend::BY_WEEKS, null, 'to_char("published_at", \'IYYY-IW\')'],
            ['sqlite', Trend::BY_WEEKS, null, 'strftime(\'%Y-%W\', datetime("published_at", \'+0 hour\'))'],

            // BY WEEKS WITH TIMEZONE
            ['mariadb', Trend::BY_WEEKS, 'UTC', 'date_format("published_at", \'%x-%v\')'],
            ['mysql', Trend::BY_WEEKS, 'Europe/Paris', 'date_format("published_at" + INTERVAL 2 HOUR, \'%x-%v\')'],
            ['pgsql', Trend::BY_WEEKS, 'Asia/Tokyo', 'to_char("published_at" + interval \'9 hour\', \'IYYY-IW\')'],
            ['sqlite', Trend::BY_WEEKS, 'America/New_York', 'strftime(\'%Y-%W\', datetime("published_at", \'-4 hour\'))'],

            // BY DAYS WITHOUT TIMEZONE
            ['mariadb', Trend::BY_DAYS, null, 'date_format("published_at", \'%Y-%m-%d\')'],
            ['mysql', Trend::BY_DAYS, null, 'date_format("published_at", \'%Y-%m-%d\')'],
            ['pgsql', Trend::BY_DAYS, null, 'to_char("published_at", \'YYYY-MM-DD\')'],
            ['sqlite', Trend::BY_DAYS, null, 'strftime(\'%Y-%m-%d\', datetime("published_at", \'+0 hour\'))'],

            // BY DAYS WITH TIMEZONE
            ['mariadb', Trend::BY_DAYS, 'UTC', 'date_format("published_at", \'%Y-%m-%d\')'],
            ['mysql', Trend::BY_DAYS, 'Europe/Paris', 'date_format("published_at" + INTERVAL 2 HOUR, \'%Y-%m-%d\')'],
            ['pgsql', Trend::BY_DAYS, 'Asia/Tokyo', 'to_char("published_at" + interval \'9 hour\', \'YYYY-MM-DD\')'],
            ['sqlite', Trend::BY_DAYS, 'America/New_York', 'strftime(\'%Y-%m-%d\', datetime("published_at", \'-4 hour\'))'],

            // BY HOURS WITHOUT TIMEZONE
            ['mariadb', Trend::BY_HOURS, null, 'date_format("published_at", \'%Y-%m-%d %H:00\')'],
            ['mysql', Trend::BY_HOURS, null, 'date_format("published_at", \'%Y-%m-%d %H:00\')'],
            ['pgsql', Trend::BY_HOURS, null, 'to_char("published_at", \'YYYY-MM-DD HH24:00\')'],
            ['sqlite', Trend::BY_HOURS, null, 'strftime(\'%Y-%m-%d %H:00\', datetime("published_at", \'+0 hour\'))'],

            // BY HOURS WITH TIMEZONE
            ['mariadb', Trend::BY_HOURS, 'UTC', 'date_format("published_at", \'%Y-%m-%d %H:00\')'],
            ['mysql', Trend::BY_HOURS, 'Europe/Paris', 'date_format("published_at" + INTERVAL 2 HOUR, \'%Y-%m-%d %H:00\')'],
            ['pgsql', Trend::BY_HOURS, 'Asia/Tokyo', 'to_char("published_at" + interval \'9 hour\', \'YYYY-MM-DD HH24:00\')'],
            ['sqlite', Trend::BY_HOURS, 'America/New_York', 'strftime(\'%Y-%m-%d %H:00\', datetime("published_at", \'-4 hour\'))'],

            // BY MINUTES WITH TIMEZONE
            ['mariadb', Trend::BY_MINUTES, null, 'date_format("published_at", \'%Y-%m-%d %H:%i:00\')'],
            ['mysql', Trend::BY_MINUTES, null, 'date_format("published_at", \'%Y-%m-%d %H:%i:00\')'],
            ['pgsql', Trend::BY_MINUTES, null, 'to_char("published_at", \'YYYY-MM-DD HH24:mi:00\')'],
            ['sqlite', Trend::BY_MINUTES, null, 'strftime(\'%Y-%m-%d %H:%M:00\', datetime("published_at", \'+0 hour\'))'],

            // BY MINUTES WITHOUT TIMEZONE
            ['mariadb', Trend::BY_MINUTES, 'UTC', 'date_format("published_at", \'%Y-%m-%d %H:%i:00\')'],
            ['mysql', Trend::BY_MINUTES, 'Europe/Paris', 'date_format("published_at" + INTERVAL 2 HOUR, \'%Y-%m-%d %H:%i:00\')'],
            ['pgsql', Trend::BY_MINUTES, 'Asia/Tokyo', 'to_char("published_at" + interval \'9 hour\', \'YYYY-MM-DD HH24:mi:00\')'],
            ['sqlite', Trend::BY_MINUTES, 'America/New_York', 'strftime(\'%Y-%m-%d %H:%M:00\', datetime("published_at", \'-4 hour\'))'],
        ];
    }
}
