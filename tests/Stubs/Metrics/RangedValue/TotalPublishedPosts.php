<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\RangedValue;

use Arcanedev\LaravelMetrics\Tests\Stubs\Models\Post;
use Illuminate\Http\Request;

/**
 * Class     TotalPublishedPosts
 *
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
}
