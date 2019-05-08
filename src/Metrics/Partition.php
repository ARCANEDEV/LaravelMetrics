<?php namespace Arcanedev\LaravelMetrics\Metrics;

/**
 * Class     Partition
 *
 * @package  Arcanedev\LaravelMetrics\Metrics
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Partition extends Metric
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
    public static function type(): string
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
     * @param mixed|null $value
     *
     * @return \Arcanedev\LaravelMetrics\Results\PartitionResult|mixed
     */
    protected function result($value = null)
    {
        return new PartitionResult($value);
    }
}
