<?php namespace Arcanedev\LaravelMetrics\Expressions\TrendDateFormat;

use Arcanedev\LaravelMetrics\Expressions\Expression as BaseExpression;
use Cake\Chronos\Chronos;
use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class     Expression
 *
 * @package  Arcanedev\LaravelMetrics\Expressions\TrendDateFormat
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Expression extends BaseExpression
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The query builder being used to build the trend.
     *
     * @var \Illuminate\Database\Query\Builder
     */
    public $query;

    /**
     * The unit being measured.
     *
     * @var string
     */
    public $unit;

    /**
     * The users's local timezone.
     *
     * @var string
     */
    public $timezone;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * Create a new raw query expression.
     *
     * @param  mixed                                  $value
     * @param  string                                 $unit
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string                                 $timezone
     *
     * @return void
     */
    public function __construct($value, string $unit, Builder $query, $timezone)
    {
        parent::__construct($value);

        $this->unit     = $unit;
        $this->query    = $query;
        $this->timezone = $timezone;
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the timezone offset for the user's timezone.
     *
     * @return int
     */
    public function offset()
    {
        if ($this->timezone)
            return (new DateTime(Chronos::now()->format('Y-m-d H:i:s'), new DateTimeZone($this->timezone)))->getOffset() / 60 / 60;

        return 0;
    }

    /**
     * Wrap the given value using the query's grammar.
     *
     * @param  string  $value
     *
     * @return string
     */
    protected function wrap($value)
    {
        return $this->query->getQuery()->getGrammar()->wrap($value);
    }
}