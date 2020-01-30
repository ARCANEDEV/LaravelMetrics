<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Metrics\Concerns;

/**
 * Trait     HasRanges
 *
 * @package  Arcanedev\LaravelMetrics\Metrics\Concerns
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  \Illuminate\Http\Request  $request
 *
 * @method  array  ranges()
 */
trait HasRanges
{
    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Convert the ranges for json serialization.
     *
     * @return array
     */
    public function rangesToArray(): array
    {
        $ranges = method_exists($this, 'ranges') ? $this->ranges() : [];

        return array_map(function ($value, $label) {
            return compact('value', 'label');
        }, array_keys($ranges), $ranges);
    }

    /**
     * Calculate the current range.
     *
     * @param  string|int             $range
     * @param  \Cake\Chronos\Chronos  $now
     *
     * @return array
     */
    protected function currentRange($range, $now): array
    {
        return [
            $now->subDays($range ?: 1),
            $now,
        ];
    }

    /**
     * Calculate the previous range.
     *
     * @param  string|int             $range
     * @param  \Cake\Chronos\Chronos  $now
     *
     * @return array
     */
    protected function previousRange($range, $now): array
    {
        if (is_null($range)) {
            $range = 1;
        }

        return [
            $now->subDays($range * 2),
            $now->subDays($range)->subSeconds(1),
        ];
    }
}
