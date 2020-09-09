<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Expressions\TrendDateFormat;

use Arcanedev\LaravelMetrics\Exceptions\InvalidTrendUnitException;
use Arcanedev\LaravelMetrics\Metrics\Trend;

/**
 * Class     MySqlExpression
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class MySqlExpression extends Expression
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the value of the expression.
     *
     * @return string
     */
    public function getValue(): string
    {
        $interval = $this->interval();

        switch ($this->unit) {
            case Trend::BY_MONTHS:
                return "date_format({$this->wrap($this->value)}{$interval}, '%Y-%m')";

            case Trend::BY_WEEKS:
                return "date_format({$this->wrap($this->value)}{$interval}, '%x-%v')";

            case Trend::BY_DAYS:
                return "date_format({$this->wrap($this->value)}{$interval}, '%Y-%m-%d')";

            case Trend::BY_HOURS:
                return "date_format({$this->wrap($this->value)}{$interval}, '%Y-%m-%d %H:00')";

            case Trend::BY_MINUTES:
                return "date_format({$this->wrap($this->value)}{$interval}, '%Y-%m-%d %H:%i:00')";

            default:
                throw InvalidTrendUnitException::make($this->unit);
        }
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the interval.
     *
     * @return string
     */
    private function interval(): string
    {
        $offset = $this->offset();

        if ($offset > 0) {
            return " + INTERVAL {$offset} HOUR";
        }

        if ($offset === 0) {
            return '';
        }

        return ' - INTERVAL '.($offset * -1).' HOUR';
    }
}
