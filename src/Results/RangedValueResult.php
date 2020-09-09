<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Results;

/**
 * Class     RangedValueResult
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RangedValueResult extends ValueResult
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The previous property.
     *
     * @var array
     */
    public $previous = [
        'value' => null,
        'label' => null,
    ];

    /**
     * The change property.
     *
     * @var array
     */
    public $change = [
        'value' => null,
        'label' => null,
    ];

    /* -----------------------------------------------------------------
     |  Getters & Setters
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
        parent::value($value);

        return $this->updateChange();
    }

    /**
     * Set the previous property.
     *
     * @param  mixed        $value
     * @param  string|null  $label
     *
     * @return $this
     */
    public function previous($value, $label = null)
    {
        $this->previous = compact('value', 'label');

        return $this->updateChange();
    }

    /**
     * Set the change property.
     *
     * @param  mixed        $value
     * @param  string|null  $label
     *
     * @return $this
     */
    public function change($value, $label = null)
    {
        $this->change = compact('value', 'label');

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Update the change property.
     *
     * @return $this
     */
    protected function updateChange()
    {
        $current  = $this->value;
        $previous = $this->previous['value'];
        $change   = null;
        $label    = __('No Prior Data');

        if ($previous && $previous > 0) {
            $change = static::calculateChange($current, $previous);
            $label  = $change === 0
                ? 'Constant'
                : (abs($change) . '% ' . __($change > 0 ? 'Increase' : 'Decrease'));
        }

        return $this->change($change, $label);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'previous' => $this->previous,
            'change'   => $this->change,
        ]);
    }

    /**
     * Calculate the change.
     *
     * @param  float|int  $current
     * @param  float|int  $previous
     *
     * @return float|int
     */
    protected static function calculateChange($current, $previous)
    {
        $diff = $current - $previous;

        if (is_null($current) || $current === 0)
            $current = $previous;

        return $diff !== 0
            ? round(($diff / $current) * 100, 2)
            : 0;
    }
}
