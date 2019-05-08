<?php namespace Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\RangedValue;

use Arcanedev\LaravelMetrics\Metrics\RangedValue as BaseRangedValue;

/**
 * Class     RangedValue
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\RangedValue
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class RangedValue extends BaseRangedValue
{
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
