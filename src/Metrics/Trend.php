<?php namespace Arcanedev\LaravelMetrics\Metrics;

/**
 * Class     Trend
 *
 * @package  Arcanedev\LaravelMetrics\Metrics
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Trend extends Metric
{
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
        return 'partition';
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

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
}
