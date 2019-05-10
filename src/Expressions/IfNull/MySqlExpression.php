<?php namespace Arcanedev\LaravelMetrics\Expressions\IfNull;

use Arcanedev\LaravelMetrics\Expressions\Expression;

/**
 * Class     MySqlExpression
 *
 * @package  Arcanedev\LaravelMetrics\Expressions\IfNull
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
    public function getValue()
    {
        return "IF(ISNULL(`{$this->value}`), 0, 1)";
    }
}
