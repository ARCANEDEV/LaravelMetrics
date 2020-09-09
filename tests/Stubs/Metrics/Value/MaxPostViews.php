<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Value;

use Arcanedev\LaravelMetrics\Tests\Stubs\Models\Post;
use Arcanedev\LaravelMetrics\Metrics\Value;
use Illuminate\Http\Request;

/**
 * Class     MaxPostViews
 *
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
