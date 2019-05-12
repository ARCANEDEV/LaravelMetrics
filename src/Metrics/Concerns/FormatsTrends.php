<?php namespace Arcanedev\LaravelMetrics\Metrics\Concerns;

use Arcanedev\LaravelMetrics\Exceptions\InvalidTrendUnitException;
use Cake\Chronos\Chronos;

/**
 * Trait     FormatsTrends
 *
 * @package  Arcanedev\LaravelMetrics\Metrics\Concerns
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait FormatsTrends
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Format aggregate key.
     *
     * @param  \Cake\Chronos\Chronos  $date
     * @param  string                 $unit
     *
     * @return string
     */
    protected static function formatAggregateKey(Chronos $date, string $unit): string
    {
        switch ($unit) {
            case static::BY_MONTHS:
                return static::formatAggregateKeyByMonths($date);

            case static::BY_WEEKS:
                return static::formatAggregateKeyByWeeks($date);

            case static::BY_DAYS:
                return static::formatAggregateKeyByDays($date);

            case static::BY_HOURS:
                return static::formatAggregateKeyByHours($date);

            case static::BY_MINUTES:
                return static::formatAggregateKeyByMinutes($date);

            default:
                throw InvalidTrendUnitException::make($unit);
        }
    }

    /**
     * Format the label.
     *
     * @param  \Cake\Chronos\Chronos  $date
     * @param  string                 $unit
     * @param  bool                   $twelveHourTime
     *
     * @return string
     */
    protected static function formatLabelBy(Chronos $date, string $unit, bool $twelveHourTime = false): string
    {
        switch ($unit) {
            case static::BY_MONTHS:
                return static::formatLabelByMonths($date);

            case static::BY_WEEKS:
                return static::formatLabelByWeeks($date);

            case static::BY_DAYS:
                return static::formatLabelByDays($date);

            case static::BY_HOURS:
                return static::formatLabelByHours($date, $twelveHourTime);

            case static::BY_MINUTES:
                return static::formatLabelByMinutes($date, $twelveHourTime);

            default:
                throw InvalidTrendUnitException::make($unit);
        }
    }

    /* -----------------------------------------------------------------
     |  Formatters
     | -----------------------------------------------------------------
     */

    /**
     * Format the given date by months.
     *
     * @param  \Cake\Chronos\Chronos  $date
     *
     * @return string
     */
    protected static function formatAggregateKeyByMonths(Chronos $date): string
    {
        return $date->format('Y-m');
    }

    /**
     * Format the given date by weeks.
     *
     * @param  \Cake\Chronos\Chronos  $date
     *
     * @return string
     */
    protected static function formatAggregateKeyByWeeks(Chronos $date): string
    {
        return sprintf('%s %s',
            static::formatAggregateKeyByDays($date->startOfWeek()),
            static::formatAggregateKeyByDays($date->endOfWeek())
        );
    }

    /**
     * Format the given date by days.
     *
     * @param  \Cake\Chronos\Chronos  $date
     *
     * @return string
     */
    protected static function formatAggregateKeyByDays(Chronos $date): string
    {
        return $date->format('Y-m-d');
    }

    /**
     * Format the given date by hours.
     *
     * @param \Cake\Chronos\Chronos $date
     *
     * @return string
     */
    protected static function formatAggregateKeyByHours(Chronos $date): string
    {
        return $date->format('Y-m-d H:00');
    }

    /**
     * Format the given date by minutes.
     *
     * @param \Cake\Chronos\Chronos $date
     *
     * @return string
     */
    protected static function formatAggregateKeyByMinutes(Chronos $date): string
    {
        return $date->format('Y-m-d H:i');
    }

    /**
     * Get the label by Months.
     *
     * @param  \Cake\Chronos\Chronos  $date
     *
     * @return string
     */
    protected static function formatLabelByMonths(Chronos $date): string
    {
        return __($date->format('F')).' '.$date->format('Y');
    }

    /**
     * Get the label by Weeks.
     *
     * @param  \Cake\Chronos\Chronos  $date
     *
     * @return string
     */
    protected static function formatLabelByWeeks(Chronos $date): string
    {
        return sprintf('%s %s - %s %s',
            __($date->startOfWeek()->format('F')),
            $date->startOfWeek()->format('j'),

            __($date->endOfWeek()->format('F')),
            $date->endOfWeek()->format('j')
        );
    }

    /**
     * Get the label by Days.
     *
     * @param  \Cake\Chronos\Chronos  $date
     *
     * @return string
     */
    protected static function formatLabelByDays(Chronos $date): string
    {
        return __($date->format('F')).' '.$date->format('j, Y');
    }

    /**
     * Get the label by Hours.
     *
     * @param  \Cake\Chronos\Chronos  $date
     * @param  bool                   $twelveHourTime
     *
     * @return string
     */
    protected static function formatLabelByHours(Chronos $date, bool $twelveHourTime = false): string
    {
        return sprintf('%s %s - %s',
            __($date->format('F')),
            $date->format('j'),
            $date->format($twelveHourTime ? 'g:00 A' : 'G:00')
        );
    }

    /**
     * Get the label by Minutes.
     *
     * @param  \Cake\Chronos\Chronos  $date
     * @param  bool                   $twelveHourTime
     *
     * @return string
     */
    protected static function formatLabelByMinutes(Chronos $date, bool $twelveHourTime): string
    {
        return sprintf('%s %s - %s',
            __($date->format('F')),
            $date->format('j'),
            $date->format($twelveHourTime ? 'g:i A' : 'G:i')
        );
    }
}
