<?php namespace Arcanedev\LaravelMetrics;

use Arcanedev\LaravelMetrics\Contracts\Manager as ManagerContract;
use Arcanedev\LaravelMetrics\Exceptions\MetricNotFound;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;

/**
 * Class     Manager
 *
 * @package  Arcanedev\LaravelMetrics
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Manager implements ManagerContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Illuminate\Contracts\Foundation\Application */
    protected $app;

    /**
     * The registered metrics.
     *
     * @var array
     */
    protected $registered = [];

    /**
     * Selected metrics.
     *
     * @var array
     */
    protected $selected = [];

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * MetricsManager constructor.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the registered metrics.
     *
     * @return array
     */
    public function registered(): array
    {
        return $this->registered;
    }

    /**
     * Get the selected metrics.
     *
     * @return array
     */
    public function selected(): array
    {
        return $this->selected;
    }

    /**
     * Set the selected metrics.
     *
     * @param  array  $metrics
     *
     * @return $this
     */
    public function setSelected(array $metrics)
    {
        $this->selected = $metrics;

        return $this;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get a metric instance.
     *
     * @param  string      $metric
     * @param  mixed|null  $default
     *
     * @return \Arcanedev\LaravelMetrics\Metrics\Metric|mixed
     */
    public function get(string $metric, $default = null)
    {
        return $this->has($metric) ? $this->app->make($metric) : $default;
    }

    /**
     * Register the metrics.
     *
     * @param  array|string  $metrics
     *
     * @return $this
     */
    public function register($metrics)
    {
        foreach (Arr::wrap($metrics) as $metric) {
            if ( ! $this->has($metric)) {
                $this->app->singleton($metric);
                $this->registered[] = $metric;
            }
        }

        return $this;
    }

    /**
     * Check if the metric exists.
     *
     * @param  string  $metric
     *
     * @return bool
     */
    public function has(string $metric): bool
    {
        return $this->app->has($metric);
    }

    /**
     * Check if the selected metrics is not empty
     *
     * @return bool
     */
    public function hasSelected(): bool
    {
        return count($this->selected()) > 0;
    }

    /**
     * Build the selected metrics.
     *
     * @return array
     */
    public function buildSelected(): array
    {
        return array_map(function ($class) {
            return with($this->get($class), function () use ($class) {
                throw new MetricNotFound('Metric not found: '.$class);
            });
        }, $this->selected());
    }
}
