<?php namespace Arcanedev\LaravelMetrics\Concerns;

/**
 * Trait     ConvertsToArray
 *
 * @package  Arcanedev\LaravelMetrics\Concerns
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait ConvertsToArray
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    abstract public function toArray();

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Get the instance as an array to be serialized to JSON.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
