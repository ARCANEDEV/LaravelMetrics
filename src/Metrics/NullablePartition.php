<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Metrics;

use Arcanedev\LaravelMetrics\Metrics\Concerns\HasExpressions;
use Arcanedev\LaravelMetrics\Results\PartitionResult;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

/**
 * Class     NullablePartition
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class NullablePartition extends Metric
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use HasExpressions;

    /* -----------------------------------------------------------------
     |  Getters
     | -----------------------------------------------------------------
     */

    /**
     * Get the metric's type.
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
     * @param  \Illuminate\Database\Eloquent\Builder|string       $model
     * @param  string                                             $groupBy
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     *
     * @return \Arcanedev\LaravelMetrics\Results\PartitionResult|mixed
     */
    protected function count($model, string $groupBy, $column = null)
    {
        return $this->aggregate('count', $model, $column, $groupBy);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Handle the aggregate calculation of the metric.
     *
     * @param  string                                             $method
     * @param  \Illuminate\Database\Eloquent\Builder|string       $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  string                                             $groupBy
     *
     * @return \Arcanedev\LaravelMetrics\Results\PartitionResult|mixed
     */
    protected function aggregate(string $method, $model, $column, string $groupBy)
    {
        $query         = static::getQuery($model);
        $wrappedColumn = $column instanceof Expression
            ? (string) $column
            : $query->getQuery()->getGrammar()->wrap(
                $column ?? $query->getModel()->getQualifiedKeyName()
            );

        $groupAlias = "{$groupBy}_count";
        $expression = static::getExpression($query, 'if_null', $groupBy);

        $value = $query->select([
            DB::raw("{$expression} as {$groupAlias}"),
            DB::raw("{$method}({$wrappedColumn}) as aggregate"),
        ])
            ->groupBy($groupAlias)
            ->get()
            ->mapWithKeys(function ($result) use ($groupAlias) {
                return $this->formatAggregateResult($result, $groupAlias);
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
            $key => $result->getAttribute('aggregate'),
        ];
    }

    /**
     * Make a new result instance.
     *
     * @param  mixed|null $value
     *
     * @return \Arcanedev\LaravelMetrics\Results\PartitionResult|mixed
     */
    protected function result($value = null)
    {
        return new PartitionResult($value);
    }
}
