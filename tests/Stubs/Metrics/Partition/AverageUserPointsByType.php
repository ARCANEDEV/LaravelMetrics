<?php namespace Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Partition;

use Arcanedev\LaravelMetrics\Metrics\Partition;
use Arcanedev\LaravelMetrics\Tests\Stubs\Models\User;
use Illuminate\Http\Request;

/**
 * Class     AverageUserTypes
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Partition
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class AverageUserPointsByType extends Partition
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Calculate the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Arcanedev\LaravelMetrics\Results\PartitionResult
     */
    public function calculate(Request $request)
    {
        return $this->average(User::class, 'points', 'type');
    }
}
