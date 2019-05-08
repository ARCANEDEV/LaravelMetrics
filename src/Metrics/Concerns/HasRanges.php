<?php namespace Arcanedev\LaravelMetrics\Metrics\Concerns;

use Illuminate\Support\Carbon;

/**
 * Trait     HasRanges
 *
 * @package  Arcanedev\LaravelMetrics\Metrics\Concerns
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @property  \Illuminate\Http\Request  $request
 */
trait HasRanges
{
    /* -----------------------------------------------------------------
     |  Getters
     | -----------------------------------------------------------------
     */

    /**
     * Get the selected range.
     *
     * @return mixed
     */
    public function getSelectedRange()
    {
        return $this->request->input('range');
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges(): array
    {
        return [];
    }

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
        $ranges = array_map(function ($value, $label) {
            return compact('value', 'label');
        }, array_keys($this->ranges()), $this->ranges());

        return compact('ranges');
    }

    /**
     * Calculate the current range.
     *
     * @param  string|int  $range
     *
     * @return array
     */
    protected function currentRange($range): array
    {
        return [
            Carbon::now()->subDays($range),
            Carbon::now(),
        ];
    }



    /**
     * Calculate the previous range.
     *
     * @param  string|int  $range
     *
     * @return array
     */
    protected function previousRange($range): array
    {
        return [
            now()->subDays($range * 2),
            now()->subDays($range),
        ];
    }
}
