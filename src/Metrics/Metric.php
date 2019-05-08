<?php namespace Arcanedev\LaravelMetrics\Metrics;

use Arcanedev\LaravelMetrics\Concerns\ConvertsToArray;
use Closure;
use DateInterval;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use JsonSerializable;

/**
 * Class     Metric
 *
 * @package  Arcanedev\LaravelMetrics\Metrics
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Metric implements Arrayable, Jsonable, JsonSerializable
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use ConvertsToArray;

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
     * Get the metric's type.
     *
     * @return string
     */
    abstract public function type(): string;

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
     * Set the Http request.
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

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|void
     */
    public function cacheFor()
    {
        //
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
     * Calculate the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return mixed
     */
    abstract public function calculate(Request $request);

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
            'metric' => static::class,
            'type'   => $this->type(),
            'title'  => $this->title(),
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

    /**
     * Cache the result.
     *
     * @param  mixed     $cacheFor
     * @param  \Closure  $callback
     *
     * @return mixed
     */
    protected function cacheResult($cacheFor, Closure $callback)
    {
        $cacheFor = is_numeric($cacheFor)
            ? new DateInterval(sprintf('PT%dS', $cacheFor * 60))
            : $cacheFor;

        return Cache::remember($this->getCacheKey(), $cacheFor, $callback);
    }

    /**
     * Get the cache key.
     *
     * @return string
     */
    protected function getCacheKey(): string
    {
        $prefix = config('metrics.cache.prefix', 'arcanedev.metrics');

        return sprintf(
            "{$prefix}.%s.%s",
            Str::slug(str_replace('\\', '_', static::class)),
            $this->request->input('range', 'no-range')
        );
    }
}
