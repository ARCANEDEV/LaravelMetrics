<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Partition;

use Arcanedev\LaravelMetrics\Metrics\Partition;
use Arcanedev\LaravelMetrics\Tests\Stubs\Models\User;
use Illuminate\Http\Request;

/**
 * Class     MinUserPointsByType
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class MinUserPointsByType extends Partition
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
        return $this->min(User::class, 'points', 'type');
    }
}
