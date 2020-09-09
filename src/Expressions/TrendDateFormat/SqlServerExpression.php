<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Expressions\TrendDateFormat;

use Arcanedev\LaravelMetrics\Exceptions\InvalidTrendUnitException;
use Arcanedev\LaravelMetrics\Metrics\Trend;

/**
 * Class     SqlServerExpression
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SqlServerExpression extends Expression
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
    public function getValue(): string
    {
        $date = "DATEADD(hour, {$this->interval()}, {$this->wrap($this->value)})";

        switch ($this->unit) {
            case Trend::BY_MONTHS:
                return "FORMAT({$date}, 'yyyy-MM')";

            case Trend::BY_WEEKS:
                return "concat(YEAR({$date}), '-', datepart(ISO_WEEK, {$date}))";

            case Trend::BY_DAYS:
                return "FORMAT({$date}, 'yyyy-MM-dd')";

            case Trend::BY_HOURS:
                return "FORMAT({$date}, 'yyyy-MM-dd HH:00')";

            case Trend::BY_MINUTES:
                return "FORMAT({$date}, 'yyyy-MM-dd HH:mm:00')";

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

        if ($offset >= 0) {
            return (string) $offset;
        }

        return '-'.($offset * -1);
    }
}
