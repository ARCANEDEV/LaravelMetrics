<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Metrics\Concerns;

use Arcanedev\LaravelMetrics\Expressions\{Expression, Factory};

/**
 * Trait     HasExpressions
 *
 * @package  Arcanedev\LaravelMetrics\Metrics\Concerns
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait HasExpressions
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get an database expression.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string                                 $name
     * @param  mixed|null                             $value
     * @param  array                                  $params
     *
     * @return \Arcanedev\LaravelMetrics\Expressions\Expression|mixed
     */
    protected static function getExpression($query, string $name, $value = null, array $params = []): Expression
    {
        return Factory::resolveExpression($query, $name, $value, $params);
    }
}
