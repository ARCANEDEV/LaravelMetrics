<?php namespace Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\RangedValue;

use Arcanedev\LaravelMetrics\Tests\Stubs\Models\Post;
use Arcanedev\LaravelMetrics\Metrics\RangedValue;
use Illuminate\Http\Request;

/**
 * Class     TotalPublishedPosts
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\RangedValue
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TotalPublishedPosts extends RangedValue
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
        return $this->count(Post::class, 'id', 'published_at');
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
