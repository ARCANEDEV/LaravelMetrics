<?php namespace Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\RangedValue;

use Illuminate\Http\Request;

/**
 * Class     CachedMetric
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\RangedValue
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CachedMetric extends RangedValue
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Calculate the metric.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Arcanedev\LaravelMetrics\Results\Result|mixed
     */
    public function calculate(Request $request)
    {
        return $this->result(404);
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|void
     */
    public function cacheFor()
    {
        return 60;
    }
}
