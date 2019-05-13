<?php namespace Arcanedev\LaravelMetrics;

use Arcanedev\LaravelMetrics\Contracts\Manager as ManagerContract;
use Arcanedev\LaravelMetrics\Metrics\Metric;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

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
        return $this->has($metric) ? $this->make($metric) : $default;
    }

    /**
     * Get/Make the given metric from the container.
     *
     * @param  string  $metric
     *
     * @return \Arcanedev\LaravelMetrics\Metrics\Metric|mixed
     */
    public function make(string $metric): Metric
    {
        $this->register($metric);

        return $this->app->make($metric);
    }

    /**
     * Register the metrics into the container.
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
        return $this->app->has($metric)
            || $this->isRegistered($metric);
    }

    /**
     * Check if the metric is registered.
     *
     * @param  string  $metric
     *
     * @return bool
     */
    public function isRegistered(string $metric): bool
    {
        return in_array($metric, $this->registered);
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
     * Make the selected metrics.
     *
     * @return \Illuminate\Support\Collection
     */
    public function makeSelected(): Collection
    {
        $selected = array_combine($this->selected(), $this->selected());

        return Collection::make($selected)->transform(function ($class) {
            return $this->make($class);
        });
    }
}
