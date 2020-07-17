<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Metrics\Concerns;

use Illuminate\Http\Request;

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
     * @param  int                        $range
     * @param  \DateTimeZone|string|null  $timezone
     *
     * @return array
     */
    protected function currentRange(int $range, $timezone): array
    {
        return [
            now($timezone)->subDays($range),
            now($timezone),
        ];
    }

    /**
     * Calculate the previous range.
     *
     * @param  int                        $range
     * @param  \DateTimeZone|string|null  $timezone
     *
     * @return array
     */
    protected function previousRange(int $range, $timezone): array
    {
        return [
            now($timezone)->subDays($range * 2),
            now($timezone)->subDays($range)->subSeconds(1),
        ];
    }

    /**
     * Get the current timezone.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \DateTimeZone|string|null
     */
    protected function getCurrentTimezone(Request $request)
    {
        return $request->input('timezone');
    }
}
