<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Results;

use Arcanedev\LaravelMetrics\Tests\TestCase;

/**
 * Class     ResultTestCase
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class ResultTestCase extends TestCase
{
    /* -----------------------------------------------------------------
     |  Common Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_set_prefix(): void
    {
        $result = $this->makeResult()->prefix('$');

        self::assertSame('$', $result->prefix);
    }

    /** @test */
    public function it_can_set_suffix(): void
    {
        $result = $this->makeResult()->suffix('$');

        self::assertSame('$', $result->suffix);
    }

    /** @test */
    public function it_can_set_format(): void
    {
        $result = $this->makeResult()->format('0,00');

        self::assertSame('0,00', $result->format);
    }

    /* -----------------------------------------------------------------
     |  Assertions
     | -----------------------------------------------------------------
     */

    /**
     * Assert the given object is a metric result.
     *
     * @param  mixed  $result
     */
    protected static function assertIsMetricResult($result): void
    {
        $expectations = [
            \JsonSerializable::class,
            \Illuminate\Contracts\Support\Jsonable::class,
            \Illuminate\Contracts\Support\Arrayable::class,
            \Arcanedev\LaravelMetrics\Results\Result::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $result);
        }
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Make a result instance.
     *
     * @param  mixed|null  $value
     *
     * @return \Arcanedev\LaravelMetrics\Results\Result|mixed
     */
    abstract protected function makeResult($value = null);
}
