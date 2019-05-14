<?php namespace Arcanedev\LaravelMetrics\Results;

/**
 * Class     TrendResult
 *
 * @package  Arcanedev\LaravelMetrics\Results
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TrendResult extends Result
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The trend data of the result.
     *
     * @var array
     */
    public $trend = [];

    /* -----------------------------------------------------------------
     |  Setters
     | -----------------------------------------------------------------
     */

    /**
     * Set the latest value of the trend as the primary value.
     *
     * @return $this
     */
    public function showLatestValue()
    {
        return $this->value(
            last($this->trend)['value'] ?? null
        );
    }

    /**
     * Set the trend of data for the metric.
     *
     * @param  array  $trend
     *
     * @return $this
     */
    public function trend(array $trend)
    {
        $this->trend = $trend;

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            ['trend'  => $this->trend]
        );
    }
}
