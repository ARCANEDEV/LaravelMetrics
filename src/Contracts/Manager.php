<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Contracts;

use Illuminate\Support\Collection;

/**
 * Interface     Manager
 *
 * @package  Arcanedev\LaravelMetrics\Contracts
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Manager
{
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the registered metrics.
     *
     * @return array
     */
    public function registered(): array;

    /**
     * Get the selected metrics.
     *
     * @return array
     */
    public function selected(): array;

    /**
     * Set the selected metrics.
     *
     * @param  array  $metrics
     *
     * @return $this
     */
    public function setSelected(array $metrics);

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
    public function get(string $metric, $default = null);

    /**
     * Get/Make the given metric from the container.
     *
     * @param  string  $metric
     *
     * @return \Arcanedev\LaravelMetrics\Metrics\Metric|mixed
     */
    public function make(string $metric);

    /**
     * Register the metrics into the container.
     *
     * @param  array|string  $metrics
     *
     * @return $this
     */
    public function register($metrics);

    /**
     * Check if the metric exists.
     *
     * @param  string  $metric
     *
     * @return bool
     */
    public function has(string $metric): bool;

    /**
     * Check if the metric is registered.
     *
     * @param  string  $metric
     *
     * @return bool
     */
    public function isRegistered(string $metric): bool;

    /**
     * Check if the selected metrics is not empty
     *
     * @return bool
     */
    public function hasSelected(): bool;

    /**
     * Make the selected metrics.
     *
     * @return \Illuminate\Support\Collection
     */
    public function makeSelected(): Collection;
}
