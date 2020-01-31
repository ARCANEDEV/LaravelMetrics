<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Metrics;

use Arcanedev\LaravelMetrics\Exceptions\InvalidTrendUnitException;
use Arcanedev\LaravelMetrics\Helpers\TrendDatePeriod;
use Arcanedev\LaravelMetrics\Results\TrendResult;
use Cake\Chronos\Chronos;
use Illuminate\Support\Facades\DB;

/**
 * Class     Trend
 *
 * @package  Arcanedev\LaravelMetrics\Metrics
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Trend extends Metric
{
    /* -----------------------------------------------------------------
     |  Constants
     | -----------------------------------------------------------------
     */

    /**
     * Trend metric unit constants.
     */
    const BY_MONTHS  = 'month';
    const BY_WEEKS   = 'week';
    const BY_DAYS    = 'day';
    const BY_HOURS   = 'hour';
    const BY_MINUTES = 'minute';

    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use Concerns\AggregatesTrends,
        Concerns\HasExpressions,
        Concerns\HasRanges,
        Concerns\FormatsTrends;

    /* -----------------------------------------------------------------
     |  Getters
     | -----------------------------------------------------------------
     */

    /**
     * Get the metric type.
     *
     * @return string
     */
    public function type(): string
    {
        return 'trend';
    }

    /* -----------------------------------------------------------------
     |  Main Methods
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
    public function count($unit, $model, $dateColumn = null, $column = null)
    {
        return $this->aggregate('count', $unit, $model, $column, $dateColumn);
    }

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
    public function average(string $unit, $model, $column, $dateColumn = null)
    {
        return $this->aggregate('avg', $unit, $model, $column, $dateColumn);
    }

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
    public function sum(string $unit, $model, $column, $dateColumn = null)
    {
        return $this->aggregate('sum', $unit, $model, $column, $dateColumn);
    }

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
    public function max(string $unit, $model, $column, $dateColumn = null)
    {
        return $this->aggregate('max', $unit, $model, $column, $dateColumn);
    }

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
    public function min(string $unit, $model, $column, $dateColumn = null)
    {
        return $this->aggregate('min', $unit, $model, $column, $dateColumn);
    }

    /**
     * Make a new result instance.
     *
     * @param  mixed|null  $value
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    protected function result($value = null)
    {
        return new TrendResult($value);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Handle the aggregate calculation of the metric.
     *
     * @param  string                                        $method
     * @param  string                                        $unit
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string|null                                   $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    protected function aggregate(string $method, string $unit, $model, ?string $column = null, ?string $dateColumn = null)
    {
        $range          = $this->request->input('range');
        $timezone       = $this->request->input('timezone');
        $twelveHourTime = $this->request->input('twelveHourTime') === 'true';

        $query      = static::getQuery($model);
        $column     = $column ?? $query->getModel()->getCreatedAtColumn();
        $dateColumn = $dateColumn ?? $query->getModel()->getCreatedAtColumn();
        $expression = static::getExpression($query, 'trend_date_format', $dateColumn, [$unit, $query, $timezone]);

        $dates = TrendDatePeriod::make(
            $startingDate = TrendDatePeriod::getStartingDate($unit, $range),
            $endingDate = Chronos::now(),
            $unit,
            $timezone
        )->mapWithKeys(function (Chronos $date) use ($twelveHourTime, $unit) {
            return [
                static::formatAggregateKey($date, $unit) => [
                    'label' => static::formatLabelBy($date, $unit, $twelveHourTime),
                    'value' => 0,
                ]
            ];
        });

        $wrappedColumn = $query->getQuery()->getGrammar()->wrap($column);

        $results = $query->select([
                DB::raw("{$expression} as date_result"),
                DB::raw("{$method}({$wrappedColumn}) as aggregate")
            ])
            ->whereBetween($dateColumn, [$startingDate, $endingDate])
            ->groupBy('date_result')
            ->orderBy('date_result')
            ->get()
            ->mapWithKeys(function ($result) use ($method, $unit, $twelveHourTime) {
                $date  = static::parseDateResult($result->getAttribute('date_result'), $unit);
                $value = $result->getAttribute('aggregate');

                return [
                    static::formatAggregateKey($date, $unit) => [
                        'label' => static::formatLabelBy($date, $unit, $twelveHourTime),
                        'value' => $method === 'count' ? intval($value) : round($value, 0),
                    ],
                ];
            });

        $results = $dates->merge($results)->sortKeys();

        if ($results->count() > $range)
            $results->shift();

        return $this->result()->trend($results->all());
    }

    /**
     * Parse the date result.
     *
     * @param  string  $date
     * @param  string  $unit
     *
     * @return \Cake\Chronos\Chronos
     */
    protected function parseDateResult(string $date, string $unit): Chronos
    {
        switch ($unit) {
            case self::BY_MONTHS:
                [$year, $month] = explode('-', $date);
                return Chronos::create((int) $year, (int) $month, 1);

            case self::BY_WEEKS:
                [$year, $week] = explode('-', $date);
                return (new Chronos)->setISODate((int) $year, (int) $week)->setTime(0, 0);

            case self::BY_DAYS:
                return Chronos::createFromFormat('Y-m-d', $date);

            case self::BY_HOURS:
                return Chronos::createFromFormat('Y-m-d H:00', $date);

            case self::BY_MINUTES:
                return Chronos::createFromFormat('Y-m-d H:i:00', $date);

            default:
                throw InvalidTrendUnitException::make($unit);
        }
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            ['ranges' => $this->rangesToArray()]
        );
    }
}
