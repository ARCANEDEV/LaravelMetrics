<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Helpers;

use Arcanedev\LaravelMetrics\Exceptions\InvalidTrendUnitException;
use Arcanedev\LaravelMetrics\Helpers\TrendDatePeriod;
use Arcanedev\LaravelMetrics\Metrics\Trend;
use Arcanedev\LaravelMetrics\Tests\TestCase;
use Cake\Chronos\Chronos;
use Illuminate\Support\Collection;

/**
 * Class     TrendDatePeriodTest
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Helpers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TrendDatePeriodTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_make_range_by_months()
    {
        Chronos::setTestNow($now = Chronos::create(2019, 05, 1, 0, 0, 0));
        $unit  = Trend::BY_MONTHS;
        $start = TrendDatePeriod::getStartingDate($unit, 3);
        $end   = $now;

        // WITHOUT TIMEZONE
        $dates = TrendDatePeriod::make($start, $end, $unit);

        static::assertSame('2019-03-01 00:00:00', $start->toDateTimeString());
        static::assertSame('2019-05-01 00:00:00', $end->toDateTimeString());
        static::assertDatePeriodEquals([
            '2019-03-01 00:00:00',
            '2019-04-01 00:00:00',
            '2019-05-01 00:00:00',
        ], $dates);

        // WITH TIMEZONE
        $dates = TrendDatePeriod::make($start, $end, $unit, 'America/New_York');

        static::assertSame('2019-03-01 00:00:00', $start->toDateTimeString());
        static::assertSame('2019-05-01 00:00:00', $end->toDateTimeString());
        static::assertDatePeriodEquals([
            '2019-02-28 19:00:00',
            '2019-03-28 19:00:00',
            '2019-04-28 19:00:00',
        ], $dates);

        Chronos::setTestNow();
    }

    /** @test */
    public function it_can_make_range_by_weeks()
    {
        Chronos::setTestNow($now = Chronos::create(2019, 05, 1, 0, 0, 0));

        $unit = Trend::BY_WEEKS;
        $start = TrendDatePeriod::getStartingDate($unit, 3);
        $end   = $now;

        // WITHOUT TIMEZONE
        $dates = TrendDatePeriod::make($start, $end, $unit);

        static::assertSame('2019-04-15 00:00:00', $start->toDateTimeString());
        static::assertSame('2019-05-01 00:00:00', $end->toDateTimeString());
        static::assertDatePeriodEquals([
            '2019-04-15 00:00:00',
            '2019-04-22 00:00:00',
            '2019-04-29 00:00:00',
        ], $dates);

        // WITH TIMEZONE
        $dates = TrendDatePeriod::make($start, $end, $unit, 'America/New_York');

        static::assertSame('2019-04-15 00:00:00', $start->toDateTimeString());
        static::assertSame('2019-05-01 00:00:00', $end->toDateTimeString());
        static::assertDatePeriodEquals([
            '2019-04-14 20:00:00',
            '2019-04-21 20:00:00',
            '2019-04-28 20:00:00',
        ], $dates);

        Chronos::setTestNow();
    }

    /** @test */
    public function it_can_make_range_by_days()
    {
        Chronos::setTestNow($now = Chronos::create(2019, 05, 1, 0, 0, 0));

        $unit  = Trend::BY_DAYS;
        $start = TrendDatePeriod::getStartingDate($unit, 7);
        $end   = $now;

        // WITHOUT TIMEZONE
        $dates = TrendDatePeriod::make($start, $end, $unit);

        static::assertSame('2019-04-25 00:00:00', $start->toDateTimeString());
        static::assertSame('2019-05-01 00:00:00', $end->toDateTimeString());
        static::assertDatePeriodEquals([
            '2019-04-25 00:00:00',
            '2019-04-26 00:00:00',
            '2019-04-27 00:00:00',
            '2019-04-28 00:00:00',
            '2019-04-29 00:00:00',
            '2019-04-30 00:00:00',
            '2019-05-01 00:00:00',
        ], $dates);

        // WITH TIMEZONE
        $dates = TrendDatePeriod::make($start, $end, $unit, 'America/New_York');

        static::assertSame('2019-04-25 00:00:00', $start->toDateTimeString());
        static::assertSame('2019-05-01 00:00:00', $end->toDateTimeString());
        static::assertDatePeriodEquals([
            '2019-04-24 20:00:00',
            '2019-04-25 20:00:00',
            '2019-04-26 20:00:00',
            '2019-04-27 20:00:00',
            '2019-04-28 20:00:00',
            '2019-04-29 20:00:00',
            '2019-04-30 20:00:00',
        ], $dates);

        Chronos::setTestNow();
    }

    /** @test */
    public function it_can_make_range_by_hours()
    {
        Chronos::setTestNow($now = Chronos::create(2019, 05, 1, 0, 0, 0));

        $unit  = Trend::BY_HOURS;
        $start = TrendDatePeriod::getStartingDate($unit, 6);
        $end   = $now;

        // WITHOUT TIMEZONE
        $dates = TrendDatePeriod::make($start, $end, $unit);

        static::assertSame('2019-04-30 19:00:00', $start->toDateTimeString());
        static::assertSame('2019-05-01 00:00:00', $end->toDateTimeString());
        static::assertDatePeriodEquals([
            '2019-04-30 19:00:00',
            '2019-04-30 20:00:00',
            '2019-04-30 21:00:00',
            '2019-04-30 22:00:00',
            '2019-04-30 23:00:00',
            '2019-05-01 00:00:00',
        ], $dates);

        // WITH TIMEZONE
        $dates = TrendDatePeriod::make($start, $end, $unit, 'America/New_York');

        static::assertSame('2019-04-30 19:00:00', $start->toDateTimeString());
        static::assertSame('2019-05-01 00:00:00', $end->toDateTimeString());
        static::assertDatePeriodEquals([
            '2019-04-30 15:00:00',
            '2019-04-30 16:00:00',
            '2019-04-30 17:00:00',
            '2019-04-30 18:00:00',
            '2019-04-30 19:00:00',
            '2019-04-30 20:00:00',
        ], $dates);

        Chronos::setTestNow();
    }

    /** @test */
    public function it_can_make_range_by_minutes()
    {
        Chronos::setTestNow($now = Chronos::create(2019, 05, 1, 0, 0, 0));

        $unit  = Trend::BY_MINUTES;
        $start = TrendDatePeriod::getStartingDate($unit, 5);
        $end   = $now;

        // WITHOUT TIMEZONE
        $dates = TrendDatePeriod::make($start, $end, $unit);

        static::assertSame('2019-04-30 23:56:00', $start->toDateTimeString());
        static::assertSame('2019-05-01 00:00:00', $end->toDateTimeString());
        static::assertDatePeriodEquals([
            '2019-04-30 23:56:00',
            '2019-04-30 23:57:00',
            '2019-04-30 23:58:00',
            '2019-04-30 23:59:00',
            '2019-05-01 00:00:00',
        ], $dates);

        // WITH TIMEZONE
        $dates = TrendDatePeriod::make($start, $end, $unit, 'America/New_York');

        static::assertSame('2019-04-30 23:56:00', $start->toDateTimeString());
        static::assertSame('2019-05-01 00:00:00', $end->toDateTimeString());
        static::assertDatePeriodEquals([
            '2019-04-30 19:56:00',
            '2019-04-30 19:57:00',
            '2019-04-30 19:58:00',
            '2019-04-30 19:59:00',
            '2019-04-30 20:00:00',
        ], $dates);

        Chronos::setTestNow();
    }

    /** @test */
    public function it_must_throw_an_invalid_unit_exception_on_make()
    {
        $this->expectException(InvalidTrendUnitException::class);
        $this->expectExceptionMessage('Invalid trend unit provided [centuries]');

        TrendDatePeriod::make(Chronos::now()->subYears(100), Chronos::now(), 'centuries');
    }

    /** @test */
    public function it_must_throw_an_invalid_unit_exception_on_get_starting_date()
    {
        $this->expectException(InvalidTrendUnitException::class);
        $this->expectExceptionMessage('Invalid trend unit provided [centuries]');

        TrendDatePeriod::getStartingDate('centuries');
    }

    /* -----------------------------------------------------------------
     |  Assertions
     | -----------------------------------------------------------------
     */

    /**
     * Assert date period equals to the actual collection.
     *
     * @param  array                           $expected
     * @param  \Illuminate\Support\Collection  $actual
     */
    protected static function assertDatePeriodEquals(array $expected, $actual)
    {
        static::assertInstanceOf(Collection::class, $actual);
        static::assertEquals($expected, $actual->map(function (Chronos $date) {
            return $date->toDateTimeString();
        })->toArray());
    }
}
