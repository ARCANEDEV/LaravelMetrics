<?php namespace Arcanedev\LaravelMetrics\Metrics;

use Arcanedev\LaravelMetrics\Concerns\ConvertsToArray;
use Arcanedev\LaravelMetrics\Contracts\Metric as MetricContract;
use Arcanedev\LaravelMetrics\Metrics\Concerns\HasCachedResults;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;

/**
 * Class     Metric
 *
 * @package  Arcanedev\LaravelMetrics\Metrics
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Metric implements MetricContract
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use Macroable,
        ConvertsToArray,
        HasCachedResults;

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The Http request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the metric's title.
     *
     * @return string
     */
    public function title(): string
    {
        $class = class_basename(static::class);

        return Str::title(Str::snake($class, ' '));
    }

    /**
     * Get the Http request instance.
     *
     * @return \Illuminate\Http\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set the Http request instance.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Resolve & calculate the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Arcanedev\LaravelMetrics\Results\Result|mixed
     */
    public function resolve(Request $request)
    {
        $this->setRequest($request);

        $resolver = function () use ($request) {
            return $this->calculate($request);
        };

        return ($cacheFor = $this->cacheFor())
            ? $this->cacheResult($cacheFor, $resolver)
            : $resolver();
    }

    /**
     * Make a new result instance.
     *
     * @param  mixed|null  $value
     *
     * @return \Arcanedev\LaravelMetrics\Results\Result
     */
    abstract protected function result($value = null);

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'class' => static::class,
            'type'  => $this->type(),
            'title' => $this->title(),
        ];
    }

    /**
     * Get the query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected static function getQuery($model): Builder
    {
        return $model instanceof Builder ? $model : (new $model)->newQuery();
    }
}
