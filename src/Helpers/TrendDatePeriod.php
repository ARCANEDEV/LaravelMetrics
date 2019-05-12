<?php namespace Arcanedev\LaravelMetrics\Helpers;

use Arcanedev\LaravelMetrics\Metrics\Trend;
use Cake\Chronos\Chronos;
use Illuminate\Support\Collection;

/**
 * Class     TrendDatePeriod
 *
 * @package  Arcanedev\LaravelMetrics\Helpers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TrendDatePeriod
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * @param  \Cake\Chronos\Chronos  $start
     * @param  \Cake\Chronos\Chronos  $end
     * @param  string                 $unit
     * @param  mixed                  $timezone
     *
     * @return \Illuminate\Support\Collection
     */
    public static function make(Chronos $start, Chronos $end, string $unit, $timezone)
    {
        $period = new Collection;
        $next   = $start;

        if ( ! empty($timezone)) {
            $next = $start->setTimezone($timezone);
            $end  = $end->setTimezone($timezone);
        }

        $period->push($next);

        while ($next->lt($end)) {
            if ($unit === Trend::BY_MONTHS)
                $next = $next->addMonths(1);
            elseif ($unit === Trend::BY_WEEKS)
                $next = $next->addWeeks(1);
            elseif ($unit === Trend::BY_DAYS)
                $next = $next->addDays(1);
            elseif ($unit === Trend::BY_HOURS)
                $next = $next->addHours(1);
            elseif ($unit === Trend::BY_MINUTES)
                $next = $next->addMinutes(1);

            if ($next->lte($end))
                $period->push($next);
        }

        return $period;
    }
}
