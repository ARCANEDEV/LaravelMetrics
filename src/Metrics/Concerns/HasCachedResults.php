<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Metrics\Concerns;

use Closure;
use DateInterval;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Trait     HasCachedResults
 *
 * @package  Arcanedev\LaravelMetrics\Metrics\Concerns
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait HasCachedResults
{
    /* -----------------------------------------------------------------
     |  Getters
     | -----------------------------------------------------------------
     */

    /**
     * Get the Http request instance.
     *
     * @return \Illuminate\Http\Request
     */
    abstract public function getRequest();

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|void
     */
    public function cacheFor()
    {
        //
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

        return Cache::remember($this->getCacheKey(), $cacheFor, $callback);
    }

    /**
     * Get the cache's key.
     *
     * @return string
     */
    protected function getCacheKey(): string
    {
        $request = $this->getRequest();

        return sprintf(
            '%s.%s.%s.%s.%s',
            Str::slug($this->getCachePrefix(), '.'),
            Str::slug(str_replace('\\', '_', static::class)),
            $request->input('range', 'no-range'),
            $request->input('timezone', 'no-timezone'),
            $request->input('twelveHourTime', '24-hour-time')
        );
    }

    /**
     * Get the cache's prefix.
     *
     * @return string
     */
    protected function getCachePrefix(): string
    {
        return config()->get('metrics.cache.prefix', 'arcanedev.metrics');
    }
}
