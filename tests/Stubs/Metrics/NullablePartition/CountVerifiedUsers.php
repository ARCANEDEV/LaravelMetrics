<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\NullablePartition;

use Arcanedev\LaravelMetrics\Metrics\NullablePartition;
use Arcanedev\LaravelMetrics\Tests\Stubs\Models\User;
use Illuminate\Http\Request;

/**
 * Class     CountVerifiedUsers
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\NullablePartition
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CountVerifiedUsers extends NullablePartition
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
        return $this->count(User::class, 'verified_at')
            ->labels([
                0 => __('Not verified'),
                1 => __('Verified'),
            ])
            ->colors([
                0 => '#6C757D',
                1 => '#007BFF',
            ]);
    }
}
