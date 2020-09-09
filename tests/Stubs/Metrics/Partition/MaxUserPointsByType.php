<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Partition;

use Arcanedev\LaravelMetrics\Metrics\Partition;
use Arcanedev\LaravelMetrics\Tests\Stubs\Models\User;
use Illuminate\Http\Request;

/**
 * Class     MaxUserPointsByType
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class MaxUserPointsByType extends Partition
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
        return $this->max(User::class, 'points', 'type');
    }
}
