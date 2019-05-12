<?php namespace Arcanedev\LaravelMetrics\Expressions\TrendDateFormat;

use Arcanedev\LaravelMetrics\Exceptions\InvalidTrendUnitException;
use Arcanedev\LaravelMetrics\Metrics\Trend;

/**
 * Class     PostgresExpression
 *
 * @package  Arcanedev\LaravelMetrics\Expressions\TrendDateFormat
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class PostgresExpression extends Expression
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
                return "to_char({$this->wrap($this->value)}{$interval}, 'YYYY-MM')";

            case Trend::BY_WEEKS:
                return "to_char({$this->wrap($this->value)}{$interval}, 'IYYY-IW')";

            case Trend::BY_DAYS:
                return "to_char({$this->wrap($this->value)}{$interval}, 'YYYY-MM-DD')";

            case Trend::BY_HOURS:
                return "to_char({$this->wrap($this->value)}{$interval}, 'YYYY-MM-DD HH24:00')";

            case Trend::BY_MINUTES:
                return "to_char({$this->wrap($this->value)}{$interval}, 'YYYY-MM-DD HH24:mi:00')";

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
            return ' + interval \''.$offset.' hour\'';
        elseif ($offset === 0)
            return '';
        else
            return ' - interval \''.($offset * -1).' HOUR\'';
    }
}
