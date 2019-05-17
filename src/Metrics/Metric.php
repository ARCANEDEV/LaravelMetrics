<?php namespace Arcanedev\LaravelMetrics\Metrics;

use Arcanedev\LaravelMetrics\Concerns\ConvertsToArray;
use Arcanedev\LaravelMetrics\Contracts\Metric as MetricContract;
use Closure;
use DateInterval;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

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
        if (is_numeric($cacheFor))
            $cacheFor = new DateInterval(sprintf('PT%dS', $cacheFor * 60));

        $key = sprintf(
            '%s.%s.%s.%s.%s',
            Str::slug($this->getCachePrefix(), '.'),
            Str::slug(str_replace('\\', '_', static::class)),
            $this->request->input('range', 'no-range'),
            $this->request->input('timezone', 'no-timezone'),
            $this->request->input('twelveHourTime', '24-hour-time')
        );

        return Cache::remember($key, $cacheFor, $callback);
    }

    /**
     * Get the cache's prefix.
     *
     * @return string
     */
    protected function getCachePrefix(): string
    {
        return config('metrics.cache.prefix', 'arcanedev.metrics');
    }
}
