<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Exceptions;

use InvalidArgumentException;

/**
 * Class     InvalidTrendUnitException
 *
 * @package  Arcanedev\LaravelMetrics\Exceptions
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class InvalidTrendUnitException extends InvalidArgumentException
{
    /**
     * Make a new exception.
     *
     * @param  string  $unit
     *
     * @return \Arcanedev\LaravelMetrics\Exceptions\InvalidTrendUnitException
     */
    public static function make(string $unit)
    {
        return new static("Invalid trend unit provided [{$unit}]");
    }
}
