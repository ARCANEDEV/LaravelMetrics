<?php namespace Arcanedev\LaravelMetrics\Expressions\TrendDateFormat;

use Arcanedev\LaravelMetrics\Exceptions\InvalidTrendUnitException;
use Arcanedev\LaravelMetrics\Expressions\TrendDateFormat\Expression;
use Arcanedev\LaravelMetrics\Metrics\Trend;

/**
 * Class     SqliteExpression
 *
 * @package  Arcanedev\LaravelMetrics\Expressions\TrendDateFormat
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SqliteExpression extends Expression
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
                return "strftime('%Y-%m', datetime({$this->wrap($this->value)}, {$interval}))";

            case Trend::BY_WEEKS:
                return "strftime('%Y-%W', datetime({$this->wrap($this->value)}, {$interval}))";

            case Trend::BY_DAYS:
                return "strftime('%Y-%m-%d', datetime({$this->wrap($this->value)}, {$interval}))";

            case Trend::BY_HOURS:
                return "strftime('%Y-%m-%d %H:00', datetime({$this->wrap($this->value)}, {$interval}))";

            case Trend::BY_MINUTES:
                return "strftime('%Y-%m-%d %H:%M:00', datetime({$this->wrap($this->value)}, {$interval}))";

            default:
                throw InvalidTrendUnitException::make($this->unit);
        }
    }

    /**
     * Get the interval.
     *
     * @return string
     */
    private function interval(): string
    {
        $offset = $this->offset();

        if ($offset > 0)
            return '\'+'.$offset.' hour\'';
        elseif ($offset === 0)
            return '\'+0 hour\'';

        return '\'-'.($offset * -1).' hour\'';
    }
}
