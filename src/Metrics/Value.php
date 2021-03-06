<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Metrics;

use Arcanedev\LaravelMetrics\Metrics\Concerns\HasRoundedValue;
use Arcanedev\LaravelMetrics\Results\ValueResult;

/**
 * Class     Value
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Value extends Metric
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use HasRoundedValue;

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the metric type.
     *
     * @return string
     */
    public function type(): string
    {
        return 'value';
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Calculate the `count` of the metric.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string       $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     *
     * @return \Arcanedev\LaravelMetrics\Results\ValueResult|mixed
     */
    public function count($model, $column = null)
    {
        return $this->aggregate('count', $model, $column);
    }

    /**
     * Calculate the `average` of the metric.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     *
     * @return \Arcanedev\LaravelMetrics\Results\ValueResult|mixed
     */
    public function average($model, string $column)
    {
        return $this->aggregate('avg', $model, $column);
    }

    /**
     * Calculate the `sum` of the metric.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     *
     * @return \Arcanedev\LaravelMetrics\Results\ValueResult|mixed
     */
    public function sum($model, string $column)
    {
        return $this->aggregate('sum', $model, $column);
    }

    /**
     * Calculate the `max` of the metric.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     *
     * @return \Arcanedev\LaravelMetrics\Results\ValueResult|mixed
     */
    public function max($model, string $column)
    {
        return $this->aggregate('max', $model, $column);
    }

    /**
     * Calculate the `min` of the metric.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     *
     * @return \Arcanedev\LaravelMetrics\Results\ValueResult|mixed
     */
    public function min($model, string $column)
    {
        return $this->aggregate('min', $model, $column);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Handle the aggregate calculation of the metric.
     *
     * @param  string                                             $method
     * @param  \Illuminate\Database\Eloquent\Builder|string       $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     *
     * @return \Arcanedev\LaravelMetrics\Results\ValueResult|mixed
     */
    protected function aggregate(string $method, $model, $column = null)
    {
        $query  = static::getQuery($model);
        $column = $column ?? $query->getModel()->getQualifiedKeyName();

        $value  = $query->{$method}($column);

        return $this->result($method === 'count' ? $value : $this->roundValue($value));
    }

    /**
     * Make a new result instance.
     *
     * @param  mixed|null  $value
     *
     * @return \Arcanedev\LaravelMetrics\Results\ValueResult|mixed
     */
    protected function result($value = null)
    {
        return new ValueResult($value);
    }
}
