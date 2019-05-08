<?php namespace Arcanedev\LaravelMetrics\Metrics;

use Arcanedev\LaravelMetrics\Results\RangedValueResult;

/**
 * Class     RangedValue
 *
 * @package  Arcanedev\LaravelMetrics\Metrics
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class RangedValue extends Metric
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use Concerns\HasRanges;

    /* -----------------------------------------------------------------
     |  Getters
     | -----------------------------------------------------------------
     */

    /**
     * Get the metric type.
     *
     * @return string
     */
    public static function type(): string
    {
        return 'ranged-value';
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Calculate the `count` of the metric.
     *
     * @param  \Illuminate\Database\Eloquent\Model|string  $model
     * @param  string|null                                 $column
     * @param  string|null                                 $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\RangedValueResult|mixed
     */
    protected function count($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate('count', $model, $column, $dateColumn);
    }

    /**
     * Calculate the `average` of the metric.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\RangedValueResult|mixed
     */
    public function average($model, $column, $dateColumn = null)
    {
        return $this->aggregate('avg', $model, $column, $dateColumn);
    }

    /**
     * Calculate the `sum` of the metric.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\RangedValueResult|mixed
     */
    public function sum($model, $column, $dateColumn = null)
    {
        return $this->aggregate('sum', $model, $column, $dateColumn);
    }

    /**
     * Calculate the `max` of the metric.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\RangedValueResult|mixed
     */
    public function max($model, $column, $dateColumn = null)
    {
        return $this->aggregate('max', $model, $column, $dateColumn);
    }

    /**
     * Calculate the `min` of the metric.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string|null                                   $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\RangedValueResult|mixed
     */
    public function min($model, $column, $dateColumn = null)
    {
        return $this->aggregate('min', $model, $column, $dateColumn);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * @param  string                                      $method
     * @param  \Illuminate\Database\Eloquent\Model|string  $model
     * @param  string|null                                 $column
     * @param  string|null                                 $dateColumn
     *
     * @return \Arcanedev\LaravelMetrics\Results\RangedValueResult|mixed
     */
    private function aggregate(string $method, $model, $column = null, $dateColumn = null)
    {
        $query      = static::getQuery($model);
        $column     = $column ?? $query->getModel()->getQualifiedKeyName();
        $dateColumn = $dateColumn ?? $query->getModel()->getCreatedAtColumn();
        $range      = $this->getSelectedRange();

        $current  = (clone $query)->whereBetween($dateColumn, $this->currentRange($range))->{$method}($column);
        $previous = (clone $query)->whereBetween($dateColumn, $this->previousRange($range))->{$method}($column);

        return $this->result(round($current, 0))
                    ->previous(round($previous, 0));
    }

    /**
     * Prepare the metric for JSON serialization.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            $this->rangesToArray()
        );
    }

    /**
     * Make a new result instance.
     *
     * @param mixed|null $value
     *
     * @return \Arcanedev\LaravelMetrics\Results\RangedValueResult|mixed
     */
    protected function result($value = null)
    {
        return new RangedValueResult($value);
    }
}
