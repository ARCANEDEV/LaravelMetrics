<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\RangedValue;

use Arcanedev\LaravelMetrics\Tests\Stubs\Models\Post;
use Illuminate\Http\Request;

/**
 * Class     MinPublishedPostViews
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class MinPublishedPostViews extends RangedValue
{
    /**
     * Calculate the metric.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function calculate(Request $request)
    {
        return $this->min(Post::class, 'views', 'published_at');
    }
}
