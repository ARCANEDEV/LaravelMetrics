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
     * @param  int                             $range
     * @param  \Cake\Chronos\ChronosInterface  $now
     *
     * @return array
     */
    protected function currentRange(int $range, $now): array
    {
        return [
            $now->subDays($range),
            $now,
        ];
    }

    /**
     * Calculate the previous range.
     *
     * @param  int                             $range
     * @param  \Cake\Chronos\ChronosInterface  $now
     *
     * @return array
     */
    protected function previousRange(int $range, $now): array
    {
        return [
            $now->subDays($range * 2),
            $now->subDays($range)->subSeconds(1),
        ];
    }
}
