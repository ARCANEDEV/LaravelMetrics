<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Expressions\TrendDateFormat;

use Arcanedev\LaravelMetrics\Exceptions\InvalidTrendUnitException;
use Arcanedev\LaravelMetrics\Metrics\Trend;

/**
 * Class     SqliteExpression
 *
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
     * @return string
     */
    public function getValue(): string
    {
        $interval = $this->interval();

        switch ($this->unit) {
            case Trend::BY_MONTHS:
                return "strftime('%Y-%m', datetime({$this->wrap($this->value)}, {$interval}))";

            case Trend::BY_WEEKS:
                return "strftime('%Y', datetime({$this->wrap($this->value)}, {$interval})) || '-' || (strftime('%W', datetime({$this->wrap($this->value)}, {$interval})) + (1 - strftime('%W', strftime('%Y', datetime({$this->wrap($this->value)})) || '-01-04')))";

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

        if ($offset > 0) {
            return '\'+'.$offset.' hour\'';
        }

        if ($offset === 0) {
            return '\'+0 hour\'';
        }

        return '\'-'.($offset * -1).' hour\'';
    }
}
