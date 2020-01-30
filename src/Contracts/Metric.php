<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Contracts;

use Illuminate\Contracts\Support\{Arrayable, Jsonable};
use JsonSerializable;

/**
 * Interface     Metric
 *
 * @package  Arcanedev\LaravelMetrics\Contracts
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @method  \Arcanedev\LaravelMetrics\Results\Result|mixed  calculate(\Illuminate\Http\Request $request)
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
}
