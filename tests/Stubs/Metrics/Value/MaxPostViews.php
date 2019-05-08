<?php namespace Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Value;

use Arcanedev\LaravelMetrics\Tests\Stubs\Models\Post;
use Arcanedev\LaravelMetrics\Metrics\Value;
use Illuminate\Http\Request;

/**
 * Class     MaxPostViews
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Value
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class MaxPostViews extends Value
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
        return $this->max(Post::class, 'views');
    }
}
