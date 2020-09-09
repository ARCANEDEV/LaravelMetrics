<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Expressions;

use Arcanedev\LaravelMetrics\Exceptions\ExpressionNotFound;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Traits\Macroable;

/**
 * Class     Factory
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Factory
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use Macroable;

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The registered expressions.
     *
     * @var array
     */
    protected static $expressions = [
        'if_null' => [
            'mariadb' => IfNull\MySqlExpression::class,
            'mysql'   => IfNull\MySqlExpression::class,
            'pgsql'   => IfNull\PostgresExpression::class,
            'sqlite'  => IfNull\SqliteExpression::class,
            'sqlsrv'  => IfNull\SqlServerExpression::class,
        ],

        'trend_date_format' => [
            'mariadb' => TrendDateFormat\MySqlExpression::class,
            'mysql'   => TrendDateFormat\MySqlExpression::class,
            'pgsql'   => TrendDateFormat\PostgresExpression::class,
            'sqlite'  => TrendDateFormat\SqliteExpression::class,
            'sqlsrv'  => TrendDateFormat\SqlServerExpression::class,
        ],
    ];

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Make an expression.
     *
     * @param  string  $driver
     * @param  string  $name
     * @param  mixed   $value
     * @param  array   $params
     *
     * @return \Arcanedev\LaravelMetrics\Expressions\Expression|mixed
     */
    public static function make(string $driver, string $name, $value, array $params = []): Expression
    {
        $expression = Arr::get(static::$expressions, "{$name}.{$driver}");

        if (is_null($expression))
            throw ExpressionNotFound::make($name, $driver);


        return new $expression($value, ...$params);
    }

    /**
     * Resolve the expression.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string                                 $name
     * @param  mixed                                  $value
     * @param  array                                  $params
     *
     * @return \Arcanedev\LaravelMetrics\Expressions\Expression|mixed
     */
    public static function resolveExpression(Builder $query, string $name, $value, array $params = [])
    {
        $driver = $query->getConnection()->getDriverName();

        if (static::hasMacro($driver)) {
            return static::$driver($name, $value, $params);
        }

        return static::make($driver, $name, $value, $params);
    }
}
