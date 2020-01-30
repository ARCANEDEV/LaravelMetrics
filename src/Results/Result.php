<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Results;

use Arcanedev\LaravelMetrics\Concerns\ConvertsToArray;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

/**
 * Class     Result
 *
 * @package  Arcanedev\LaravelMetrics\Results
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Result implements Arrayable, Jsonable, JsonSerializable
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use ConvertsToArray;

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The result value.
     *
     * @var mixed|null
     */
    public $value;

    /**
     * The result prefix
     *
     * @var string|null
     */
    public $prefix;

    /**
     * The result suffix.
     *
     * @var string|null
     */
    public $suffix;

    /**
     * The result format.
     *
     * @var string
     */
    public $format;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Result constructor.
     *
     * @param  mixed|null  $value
     */
    public function __construct($value = null)
    {
        $this->value($value);
    }

    /* -----------------------------------------------------------------
     |  Setters
     | -----------------------------------------------------------------
     */

    /**
     * Set the value.
     *
     * @param  mixed  $value
     *
     * @return $this
     */
    public function value($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Set the result prefix.
     *
     * @param  string  $prefix
     *
     * @return $this
     */
    public function prefix(string $prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Set the result suffix.
     *
     * @param  string  $suffix
     *
     * @return $this
     */
    public function suffix(string $suffix)
    {
        $this->suffix = $suffix;

        return $this;
    }

    /**
     * Set the result format.
     *
     * @param  string  $format
     *
     * @return $this
     */
    public function format(string $format)
    {
        $this->format = $format;

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'value'  => $this->value,
            'format' => $this->format,
            'prefix' => $this->prefix,
            'suffix' => $this->suffix,
        ];
    }
}
