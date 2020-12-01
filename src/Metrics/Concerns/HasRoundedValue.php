<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Metrics\Concerns;

/**
 * Trait     HasRoundedValue
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait HasRoundedValue
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Rounding precision.
     *
     * @var int
     */
    public $roundingPrecision = 0;

    /**
     * Rounding mode.
     *
     * @var int
     */
    public $roundingMode = PHP_ROUND_HALF_UP;

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Set the precision level used when rounding the value.
     *
     * @param  int  $precision
     * @param  int  $mode
     *
     * @return $this
     */
    public function precision($precision = 0, $mode = PHP_ROUND_HALF_UP)
    {
        $this->roundingPrecision = $precision;
        $this->roundingMode      = $mode;

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Round the value.
     *
     * @param  int|float  $value
     *
     * @return float
     */
    public function roundValue($value)
    {
        return round((float) $value, $this->roundingPrecision, $this->roundingMode);
    }
}
