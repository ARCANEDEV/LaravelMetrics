<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Value;

use Arcanedev\LaravelMetrics\Metrics\Value;
use Illuminate\Http\Request;

/**
 * Class     MetricExtendedWithMacro
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Value
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class MetricExtendedWithMacro extends Value
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
        return $this->result(404);
    }

    /**
     * Check if authorized to see.
     *
     * @param  string  $role
     *
     * @return bool
     */
    public function authorize($role)
    {
        return $role === 'admin';
    }
}
