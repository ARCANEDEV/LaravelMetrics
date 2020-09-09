<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Expressions\IfNull;

use Arcanedev\LaravelMetrics\Expressions\Expression;

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
        return "IF(ISNULL(`{$this->value}`), 0, 1)";
    }
}
