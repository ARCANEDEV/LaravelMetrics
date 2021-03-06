<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Partition;

use Illuminate\Http\Request;

/**
 * Class     CountUserTypesWithCustomLabelsAndColors
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CountUserTypesWithCustomLabelsAndColors extends CountUserTypes
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Calculate the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Arcanedev\LaravelMetrics\Results\PartitionResult
     */
    public function calculate(Request $request)
    {
        return parent::calculate($request)->label(function ($key) {
            return $this->getLabel($key);
        })->color(function ($key) {
            return $this->getColor($key);
        });
    }

    /**
     * Get the label name.
     *
     * @param  string  $key
     *
     * @return string
     */
    private function getLabel($key)
    {
        $labels = [
            'gold'   => __('Gold'),
            'silver' => __('Silver'),
            'bronze' => __('Bronze'),
        ];

        return $labels[$key] ?? 'Unknown';
    }

    /**
     * Get the color.
     *
     * @param  string  $key
     *
     * @return string
     */
    private function getColor($key)
    {
        $colors = [
            'gold'   => '#FFD700',
            'silver' => '#C0C0C0',
            'bronze' => '#CD7F32',
        ];

        return $colors[$key] ?? 'Unknown';
    }
}
