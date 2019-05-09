<?php namespace Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Value;

use Arcanedev\LaravelMetrics\Metrics\Value;
use Cake\Chronos\Chronos;
use Illuminate\Http\Request;

/**
 * Class     CachedMetric
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Value
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CachedMetric extends Value
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
     * @return mixed
     */
    public function calculate(Request $request)
    {
        return $this->result(200);
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|void
     */
    public function cacheFor()
    {
        return Chronos::now()->addMinutes(5);
    }
}
