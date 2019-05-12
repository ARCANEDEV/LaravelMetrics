<?php namespace Arcanedev\LaravelMetrics\Exceptions;

use InvalidArgumentException;

/**
 * Class     ExpressionNotFound
 *
 * @package  Arcanedev\LaravelMetrics\Exceptions
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ExpressionNotFound extends InvalidArgumentException
{
    /**
     * Make a new exception.
     *
     * @param  string  $name
     * @param  string  $driver
     *
     * @return \Arcanedev\LaravelMetrics\Exceptions\ExpressionNotFound
     */
    public static function make(string $name, string $driver)
    {
        return new static("Expression `{$name}` not found for `{$driver}` driver");
    }
}
