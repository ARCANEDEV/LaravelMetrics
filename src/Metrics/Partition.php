<?php namespace Arcanedev\LaravelMetrics\Metrics;

use Arcanedev\LaravelMetrics\Results\PartitionResult;
use Illuminate\Support\Facades\DB;

/**
 * Class     Partition
 *
 * @package  Arcanedev\LaravelMetrics\Metrics
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Partition extends Metric
{
    /* -----------------------------------------------------------------
     |  Getters
     | -----------------------------------------------------------------
     */

    /**
     * Get the metric type.
     *
     * @return string
     */
    public function type(): string
    {
        return 'partition';
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Calculate the `count` of the metric.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $groupBy
     * @param  string|null                                   $column
     *
     * @return \Arcanedev\LaravelMetrics\Results\PartitionResult|mixed
     */
    public function count($model, string $groupBy, $column = null)
    {
        return $this->aggregate('count', $model, $column, $groupBy);
    }

    /**
     * Calculate the `average` of the metric.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string                                        $groupBy
     *
     * @return \Arcanedev\LaravelMetrics\Results\PartitionResult|mixed
     */
    public function average($model, string $column, string $groupBy)
    {
        return $this->aggregate('avg', $model, $column, $groupBy);
    }

    /**
     * Calculate the `sum` of the metric.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string                                        $groupBy
     *
     * @return \Arcanedev\LaravelMetrics\Results\PartitionResult|mixed
     */
    public function sum($model, string $column, string $groupBy)
    {
        return $this->aggregate('sum', $model, $column, $groupBy);
    }

    /**
     * Calculate the `max` of the metric.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string                                        $groupBy
     *
     * @return \Arcanedev\LaravelMetrics\Results\PartitionResult|mixed
     */
    public function max($model, string $column, string $groupBy)
    {
        return $this->aggregate('max', $model, $column, $groupBy);
    }

    /**
     * Calculate the `min` of the metric.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string                                        $groupBy
     *
     * @return \Arcanedev\LaravelMetrics\Results\PartitionResult|mixed
     */
    public function min($model, string $column, string $groupBy)
    {
        return $this->aggregate('min', $model, $column, $groupBy);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Handle the aggregate calculation of the metric.
     *
     * @param  string                                        $method
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string                                        $column
     * @param  string                                        $groupBy
     *
     * @return \Arcanedev\LaravelMetrics\Results\PartitionResult|mixed
     */
    protected function aggregate($method, $model, $column, $groupBy)
    {
        $query         = static::getQuery($model);
        $wrappedColumn = $query->getQuery()->getGrammar()->wrap(
            $column ?? $query->getModel()->getQualifiedKeyName()
        );

        $value = $query->select([$groupBy, DB::raw("{$method}({$wrappedColumn}) as aggregate")])
            ->groupBy($groupBy)
            ->get()
            ->mapWithKeys(function ($result) use ($groupBy, $method) {
                return array_map(function ($value) use ($method) {
                    return $method === 'count' ? $value : round($value, 0);
                }, $this->formatAggregateResult($result, $groupBy));
            })
            ->all();

        return $this->result($value);
    }

    /**
     * Format the aggregate result for the partition.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $result
     * @param  string                               $groupBy
     *
     * @return array
     */
    protected function formatAggregateResult($result, $groupBy): array
    {
        $key = $result->getAttribute(last(explode('.', $groupBy)));

        return [
            $key => $result->getAttribute('aggregate')
        ];
    }

    /**
     * Make a new result instance.
     *
     * @param mixed|null $value
     *
     * @return \Arcanedev\LaravelMetrics\Results\PartitionResult|mixed
     */
    protected function result($value = null)
    {
        return new PartitionResult($value);
    }
}
