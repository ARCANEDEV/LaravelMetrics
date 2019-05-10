<?php namespace Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Partition;

use Arcanedev\LaravelMetrics\Metrics\Partition;
use Arcanedev\LaravelMetrics\Tests\Stubs\Models\User;
use Illuminate\Http\Request;

/**
 * Class     SumUserPointsByType
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Partition
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SumUserPointsByType extends Partition
{
    /* -----------------------------------------------------------------
     |  Properties
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
        return $this->sum(User::class, 'points', 'type');
    }
}
