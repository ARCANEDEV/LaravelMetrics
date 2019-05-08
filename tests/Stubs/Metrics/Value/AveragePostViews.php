<?php namespace Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Value;

use Arcanedev\LaravelMetrics\Tests\Stubs\Models\Post;
use Arcanedev\LaravelMetrics\Metrics\Value;
use Illuminate\Http\Request;

/**
 * Class     AveragePostViews
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Value
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class AveragePostViews extends Value
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
        return $this->average(Post::class, 'views');
    }
}
