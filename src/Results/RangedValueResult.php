<?php namespace Arcanedev\LaravelMetrics\Results;

/**
 * Class     RangedValueResult
 *
 * @package  Arcanedev\LaravelMetrics\Results
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RangedValueResult extends ValueResult
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The previous value.
     *
     * @var array
     */
    public $previous = [
        'value' => null,
        'label' => null,
    ];

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Set the previous value.
     *
     * @param  mixed   $value
     * @param  string  $label
     *
     * @return $this
     */
    public function previous($value, $label = null)
    {
        $this->previous = compact('value', 'label');

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
        return array_merge(
            parent::toArray(),
            ['previous' => $this->previous]
        );
    }
}
