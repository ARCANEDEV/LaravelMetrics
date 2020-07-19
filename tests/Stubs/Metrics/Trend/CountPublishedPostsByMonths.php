<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Trend;

use Arcanedev\LaravelMetrics\Metrics\Trend;
use Arcanedev\LaravelMetrics\Tests\Stubs\Models\Post;
use Illuminate\Http\Request;

/**
 * Class     CountPublishedPostsByMonths
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Trend
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CountPublishedPostsByMonths extends Trend
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
        return $this->countByMonths(Post::class, 'published_at');
    }
}
