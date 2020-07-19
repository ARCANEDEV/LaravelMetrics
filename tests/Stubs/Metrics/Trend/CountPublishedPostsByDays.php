<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Trend;

use Arcanedev\LaravelMetrics\Metrics\Trend;
use Arcanedev\LaravelMetrics\Tests\Stubs\Models\Post;
use Illuminate\Http\Request;

/**
 * Class     CountPublishedPostsByDays
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Trend
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CountPublishedPostsByDays extends Trend
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
        return $this->countByDays(Post::class, 'published_at');
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges(): array
    {
        $days = __('Days');

        return [
            3  => "3 {$days}",
            7  => "7 {$days}",
            14 => "14 {$days}",
            30 => "30 {$days}",
        ];
    }
}
