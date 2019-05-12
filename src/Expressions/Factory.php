<?php namespace Arcanedev\LaravelMetrics\Expressions;

use Arcanedev\LaravelMetrics\Exceptions\ExpressionNotFound;
use Illuminate\Support\Arr;
use InvalidArgumentException;

/**
 * Class     Factory
 *
 * @package  Arcanedev\LaravelMetrics\Expressions
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Factory
{
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
        ],

        'trend_date_format' => [
            'mariadb' => TrendDateFormat\MySqlExpression::class,
            'mysql'   => TrendDateFormat\MySqlExpression::class,
            'pgsql'   => TrendDateFormat\PostgresExpression::class,
            'sqlite'  => TrendDateFormat\SqliteExpression::class,
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
}
