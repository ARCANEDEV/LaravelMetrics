<?php namespace Arcanedev\LaravelMetrics\Expressions\TrendDateFormat;

use Arcanedev\LaravelMetrics\Exceptions\InvalidTrendUnitException;
use Arcanedev\LaravelMetrics\Metrics\Trend;

/**
 * Class     MySqlExpression
 *
 * @package  Arcanedev\LaravelMetrics\Expressions\TrendDateFormat
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
     * @return mixed
     */
    public function getValue()
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

        if ($offset > 0)
            return ' + INTERVAL '.$offset.' HOUR';
        elseif ($offset === 0)
            return '';
        else
            return ' - INTERVAL '.($offset * -1).' HOUR';
    }
}
