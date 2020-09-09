<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Helpers;

use Arcanedev\LaravelMetrics\Exceptions\InvalidTrendUnitException;
use Arcanedev\LaravelMetrics\Metrics\Trend;
use Cake\Chronos\Chronos;
use Illuminate\Support\Collection;

/**
 * Class     TrendDatePeriod
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TrendDatePeriod
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Make date period (range) for the trend metric.
     *
     * @param  \Cake\Chronos\Chronos  $start
     * @param  \Cake\Chronos\Chronos  $end
     * @param  string                 $unit
     * @param  mixed|null             $timezone
     *
     * @return \Illuminate\Support\Collection
     */
    public static function make(Chronos $start, Chronos $end, string $unit, $timezone = null)
    {
        $period = new Collection;
        $next   = $start;

        if ( ! empty($timezone)) {
            $next = $start->setTimezone($timezone);
            $end  = $end->setTimezone($timezone);
        }

        $period->push($next);

        while ($next->lt($end)) {
            $next = self::getNextDate($unit, $next);

            if ($next->lte($end))
                $period->push($next);
        }

        return $period;
    }

    /**
     * Get the starting date.
     *
     * @param  string        $unit
     * @param  mixed|null    $range
     *
     * @return \Cake\Chronos\Chronos
     */
    public static function getStartingDate(string $unit, $range = null): Chronos
    {
        $range = empty($range) ? 1 : ($range - 1);
        $now   = Chronos::now();

        switch ($unit) {
            case Trend::BY_MONTHS:
                return $now->subMonths($range)->firstOfMonth()->setTime(0, 0);

            case Trend::BY_WEEKS:
                return $now->subWeeks($range)->startOfWeek()->setTime(0, 0);

            case Trend::BY_DAYS:
                return $now->subDays($range)->setTime(0, 0);

            case Trend::BY_HOURS:
                return with($now->subHours($range), function (Chronos $date) {
                    return $date->setTime($date->hour, 0);
                });

            case Trend::BY_MINUTES:
                return with($now->subMinutes($range), function (Chronos $date) {
                    return $date->setTime($date->hour, $date->minute);
                });

            default:
                throw InvalidTrendUnitException::make($unit);
        }
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get next date.
     *
     * @param  string                 $unit
     * @param  \Cake\Chronos\Chronos  $next
     *
     * @return \Cake\Chronos\Chronos
     */
    private static function getNextDate(string $unit, Chronos $next): Chronos
    {
        switch ($unit) {
            case Trend::BY_MONTHS:
                return $next->addMonths(1);

            case Trend::BY_WEEKS:
                return $next->addWeeks(1);

            case Trend::BY_DAYS:
                return $next->addDays(1);

            case Trend::BY_HOURS:
                return $next->addHours(1);

            case Trend::BY_MINUTES:
                return $next->addMinutes(1);

            default:
                throw InvalidTrendUnitException::make($unit);
        }
    }
}
