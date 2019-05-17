<?php namespace Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Trend;

use Arcanedev\LaravelMetrics\Metrics\Trend;
use Arcanedev\LaravelMetrics\Tests\Stubs\Models\Post;
use Illuminate\Http\Request;

/**
 * Class     CountPublishedPostsByMinutes
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Trend
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CountPublishedPostsByMinutes extends Trend
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
        return $this->countByMinutes(Post::class, 'published_at');
    }
}
