<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Results;

use Closure;
use Illuminate\Support\Collection;

/**
 * Class     PartitionResult
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class PartitionResult extends Result
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The custom label names.
     *
     * @var array
     */
    public $labels = [];

    /**
     * The custom label colors.
     *
     * @var array
     */
    public $colors = [];

    /**
     * The sort direction [asc, desc].
     *
     * @var string|null
     */
    private $sort;

    /* -----------------------------------------------------------------
     |  Setters
     | -----------------------------------------------------------------
     */

    /**
     * Set the value.
     *
     * @param  mixed  $value
     *
     * @return $this|mixed
     */
    public function value($value)
    {
        return parent::value(
            Collection::make($value)
        );
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Format the labels for the partition result.
     *
     * @param  \Closure  $callback
     *
     * @return $this
     */
    public function label(Closure $callback)
    {
        return $this->labels(
            $this->mapValue($callback)->toArray()
        );
    }

    /**
     * Set the labels for the partition result.
     *
     * @param  array  $labels
     *
     * @return $this
     */
    public function labels(array $labels)
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * Set the colors for the partition result.
     *
     * @param  \Closure  $callback
     *
     * @return $this
     */
    public function color(Closure $callback)
    {
        return $this->colors(
            $this->mapValue($callback)->toArray()
        );
    }

    /**
     * Set the custom label colors.
     *
     * @param  array  $colors
     *
     * @return $this
     */
    public function colors(array $colors)
    {
        $this->colors = $colors;

        return $this;
    }

    /**
     * Set the sort direction.
     *
     * @param  string  $direction
     *
     * @return $this
     */
    public function sort(string $direction = 'asc')
    {
        $direction = strtolower($direction);

        if (in_array($direction, ['asc', 'desc']))
            $this->sort = $direction;

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Common Methods
     | -----------------------------------------------------------------
     */

    /**
     * Convert the metric object to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $value = $this->mapValue(function ($key, $value) {
            return array_filter($this->formatValue($key, $value), function ($value) {
                return ! is_null($value);
            });
        })->unless(is_null($this->sort), function (Collection $values) {
            return $values->sortBy('value', SORT_REGULAR, $this->sort === 'desc');
        })->values()->all();

        return array_merge(parent::toArray(), compact('value'));
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Transform the value by the given callback
     *
     * @param  \Closure  $callback
     *
     * @return \Illuminate\Support\Collection
     */
    private function mapValue(Closure $callback)
    {
        return $this->value->map(function ($value, $key) use ($callback) {
            return $callback($key, $value);
        });
    }

    /**
     * Format the value.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     *
     * @return array
     */
    protected function formatValue($key, $value): array
    {
        return [
            'color' => $this->colors[$key] ?? null,
            'label' => $this->labels[$key] ?? $key,
            'value' => $value,
        ];
    }
}
