<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\RangedValue;

use Arcanedev\LaravelMetrics\Tests\Stubs\Models\Post;
use Illuminate\Http\Request;

/**
 * Class     AveragePublishedPostViews
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class AveragePublishedPostViews extends RangedValue
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
        return $this->average(Post::class, 'views', 'published_at');
    }
}
