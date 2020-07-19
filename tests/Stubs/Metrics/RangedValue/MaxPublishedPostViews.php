<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\RangedValue;

use Arcanedev\LaravelMetrics\Tests\Stubs\Models\Post;
use Illuminate\Http\Request;

/**
 * Class     MaxPublishedPostViews
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\RangedValue
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class MaxPublishedPostViews extends RangedValue
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
        return $this->max(Post::class, 'views', 'published_at');
    }
}
