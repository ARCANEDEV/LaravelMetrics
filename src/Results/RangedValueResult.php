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
     |  Constants
     | -----------------------------------------------------------------
     */

    public const GROWTH_CONSTANT       = 'constant';
    public const GROWTH_INCREASE       = 'increase';
    public const GROWTH_DECREASE       = 'decrease';
    public const GROWTH_NOT_PRIOR_DATA = 'no_prior_data';

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
     * @param  string|null  $growth
     *
     * @return $this
     */
    public function change($value, $label = null, $growth = null)
    {
        $this->change = compact('value', 'label', 'growth');

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

        if (is_null($previous) || $previous === 0)
            return $this->change(null, __('No Prior Data'), static::GROWTH_NOT_PRIOR_DATA);

        $change = static::calculateChange($current, $previous);

        switch (true) {
            case $change === 0:
                return $this->change($change, __('Constant'), static::GROWTH_CONSTANT);

            case $change > 0:
                return $this->change($change, abs($change).'% '.__('Increase'), static::GROWTH_INCREASE);

            case $change < 0:
                return $this->change($change, abs($change).'% '.__('Decrease'), static::GROWTH_DECREASE);
        }
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
