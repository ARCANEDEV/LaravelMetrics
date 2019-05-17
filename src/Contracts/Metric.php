<?php namespace Arcanedev\LaravelMetrics\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\Request;
use JsonSerializable;

/**
 * Interface     Metric
 *
 * @package  Arcanedev\LaravelMetrics\Contracts
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Metric extends Arrayable, Jsonable, JsonSerializable
{
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the metric's title.
     *
     * @return string
     */
    public function title(): string;

    /**
     * Get the metric's type.
     *
     * @return string
     */
    public function type(): string;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Calculate the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Arcanedev\LaravelMetrics\Results\Result|mixed
     */
    public function calculate(Request $request);
}
