<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Expressions\IfNull;

use Arcanedev\LaravelMetrics\Expressions\Expression;

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
        return "CASE WHEN `{$this->value}` IS NULL THEN 0 ELSE 1 END";
    }
}
