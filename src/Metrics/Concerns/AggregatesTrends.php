<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Metrics\Concerns;

use Arcanedev\LaravelMetrics\Metrics\Trend;

/**
 * Trait     AggregatesTrends
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait AggregatesTrends
{
    /* -----------------------------------------------------------------
     |  Count Methods
     | -----------------------------------------------------------------
     */

    /**
     * Calculate the `count` of the metric over months.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string|null                                   $column
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function countByMonths($model, $column = null)
    {
        return $this->count(Trend::BY_MONTHS, $model, $column);
    }

    /**
     * Calculate the `count` of the metric over weeks.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string|null                                   $column
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function countByWeeks($model, $column = null)
    {
        return $this->count(Trend::BY_WEEKS, $model, $column);
    }

    /**
     * Calculate the `count` of the metric over days.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string|null                                   $column
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function countByDays($model, $column = null)
    {
        return $this->count(Trend::BY_DAYS, $model, $column);
    }

    /**
     * Calculate the `count` of the metric over hours.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string|null                                   $column
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function countByHours($model, $column = null)
    {
        return $this->count(Trend::BY_HOURS, $model, $column);
    }

    /**
     * Calculate the `count` of the metric over minutes.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string|null                                   $column
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function countByMinutes($model, $column = null)
    {
        return $this->count(Trend::BY_MINUTES, $model, $column);
    }

    /* -----------------------------------------------------------------
     |  Average Methods
     | -----------------------------------------------------------------
     */

    /**
     * Calculate the `average` of the metric over months.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function averageByMonths($model, $column, $dateColumn = null)
    {
        return $this->average(Trend::BY_MONTHS, $model, $column, $dateColumn);
    }

    /**
     * Calculate the `average` of the metric over weeks.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function averageByWeeks($model, $column, $dateColumn = null)
    {
        return $this->average(Trend::BY_WEEKS, $model, $column, $dateColumn);
    }

    /**
     * Calculate the `average` of the metric over days.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function averageByDays($model, $column, $dateColumn = null)
    {
        return $this->average(Trend::BY_DAYS, $model, $column, $dateColumn);
    }

    /**
     * Calculate the `average` of the metric over hours.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function averageByHours($model, $column, $dateColumn = null)
    {
        return $this->average(Trend::BY_HOURS, $model, $column, $dateColumn);
    }

    /**
     * Calculate the `average` of the metric over minutes.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function averageByMinutes($model, $column, $dateColumn = null)
    {
        return $this->average(Trend::BY_MINUTES, $model, $column, $dateColumn);
    }

    /* -----------------------------------------------------------------
     |  Sum Methods
     | -----------------------------------------------------------------
     */

    /**
     * Calculate the `sum` of the metric over months.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function sumByMonths($model, $column, $dateColumn = null)
    {
        return $this->sum(Trend::BY_MONTHS, $model, $column, $dateColumn);
    }

    /**
     * Calculate the `sum` of the metric over weeks.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string                                        $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function sumByWeeks($model, $column, $dateColumn = null)
    {
        return $this->sum(Trend::BY_WEEKS, $model, $column, $dateColumn);
    }

    /**
     * Calculate the `sum` of the metric over days.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function sumByDays($model, $column, $dateColumn = null)
    {
        return $this->sum(Trend::BY_DAYS, $model, $column, $dateColumn);
    }

    /**
     * Calculate the `sum` of the metric over hours.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string                                        $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function sumByHours($model, $column, $dateColumn = null)
    {
        return $this->sum(Trend::BY_HOURS, $model, $column, $dateColumn);
    }

    /**
     * Calculate the `sum` of the metric over minutes.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function sumByMinutes($model, $column, $dateColumn = null)
    {
        return $this->sum(Trend::BY_MINUTES, $model, $column, $dateColumn);
    }

    /* -----------------------------------------------------------------
     |  Max Methods
     | -----------------------------------------------------------------
     */

    /**
     * Calculate the `max` of the metric over months.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function maxByMonths($model, $column, $dateColumn = null)
    {
        return $this->max(Trend::BY_MONTHS, $model, $column, $dateColumn);
    }

    /**
     * Calculate the `max` of the metric over weeks.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function maxByWeeks($model, $column, $dateColumn = null)
    {
        return $this->max(Trend::BY_WEEKS, $model, $column, $dateColumn);
    }

    /**
     * Calculate the `max` of the metric over days.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function maxByDays($model, $column, $dateColumn = null)
    {
        return $this->max(Trend::BY_DAYS, $model, $column, $dateColumn);
    }

    /**
     * Calculate the `max` of the metric over hours.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function maxByHours($model, $column, $dateColumn = null)
    {
        return $this->max(Trend::BY_HOURS, $model, $column, $dateColumn);
    }

    /**
     * Calculate the `max` of the metric over minutes.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function maxByMinutes($model, $column, $dateColumn = null)
    {
        return $this->max(Trend::BY_MINUTES, $model, $column, $dateColumn);
    }

    /* -----------------------------------------------------------------
     |  Min Methods
     | -----------------------------------------------------------------
     */

    /**
     * Calculate the `min` of the metric over months.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function minByMonths($model, $column, $dateColumn = null)
    {
        return $this->min(Trend::BY_MONTHS, $model, $column, $dateColumn);
    }

    /**
     * Calculate the `min` of the metric over weeks.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function minByWeeks($model, $column, $dateColumn = null)
    {
        return $this->min(Trend::BY_WEEKS, $model, $column, $dateColumn);
    }

    /**
     * Calculate the `min` of the metric over days.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function minByDays($model, $column, $dateColumn = null)
    {
        return $this->min(Trend::BY_DAYS, $model, $column, $dateColumn);
    }

    /**
     * Calculate the `min` of the metric over hours.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function minByHours($model, $column, $dateColumn = null)
    {
        return $this->min(Trend::BY_HOURS, $model, $column, $dateColumn);
    }

    /**
     * Calculate the `min` of the metric over minutes.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    public function minByMinutes($model, $column, $dateColumn = null)
    {
        return $this->min(Trend::BY_MINUTES, $model, $column, $dateColumn);
    }

    /* -----------------------------------------------------------------
     |  Aggregate Methods
     | -----------------------------------------------------------------
     */

    /**
     * Calculate the `count` of the metric.
     *
     * @param  string                                        $unit
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string|null                                   $dateColumn
     * @param  string|null                                   $column
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    abstract public function count($unit, $model, $dateColumn = null, $column = null);

    /**
     * Return a value result showing a average aggregate over time.
     *
     * @param  string                                        $unit
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    abstract public function average(string $unit, $model, $column, $dateColumn = null);

    /**
     * Return a value result showing a sum aggregate over time.
     *
     * @param  string                                        $unit
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    abstract public function sum(string $unit, $model, $column, $dateColumn = null);

    /**
     * Return a value result showing a max aggregate over time.
     *
     * @param  string                                        $unit
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    abstract public function max(string $unit, $model, $column, $dateColumn = null);

    /**
     * Return a value result showing a min aggregate over time.
     *
     * @param  string                                        $unit
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    abstract public function min(string $unit, $model, $column, $dateColumn = null);
}
