<?php namespace Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Value;

use Arcanedev\LaravelMetrics\Metrics\Value;
use Arcanedev\LaravelMetrics\Tests\Stubs\Models\Post;
use Illuminate\Http\Request;

/**
 * Class     AverageViewsCount
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Value
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class AverageViewsCount extends Value
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The value's precision when rounding.
     *
     * @var int
     */
    public $roundingPrecision = 1;

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
