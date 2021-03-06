<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Results;

use Arcanedev\LaravelMetrics\Results\ValueResult;

/**
 * Class     ValueResultTest
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ValueResultTest extends ResultTestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated(): void
    {
        $result = $this->makeResult();

        static::assertIsMetricResult($result);

        static::assertNull($result->value);
    }

    /** @test */
    public function it_can_set_value(): void
    {
        $result = $this->makeResult(10);

        static::assertSame(10, $result->value);

        $result = $this->makeResult()->value(10);

        static::assertSame(10, $result->value);
    }

    /** @test */
    public function it_can_convert_to_array(): void
    {
        $result = $this->makeResult(123)
            ->prefix('$')
            ->suffix('per unit')
            ->format('0,00');

        $expected = [
            'value'  => 123,
            'format' => '0,00',
            'prefix' => '$',
            'suffix' => 'per unit',
        ];

        static::assertEquals($expected, $result->toArray());
    }

    /** @test */
    public function it_can_convert_to_json(): void
    {
        $result = $this->makeResult(123)
            ->prefix('$')
            ->suffix('per unit')
            ->format('0,00');

        $expected = json_encode([
            'value'  => 123,
            'format' => '0,00',
            'prefix' => '$',
            'suffix' => 'per unit',
        ]);

        static::assertJson($actual = $result->toJson());
        static::assertJsonStringEqualsJsonString($expected, $actual);

        static::assertJson($actual = json_encode($result));
        static::assertJsonStringEqualsJsonString($expected, $actual);
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
    protected function makeResult($value = null): ValueResult
    {
        return new ValueResult($value);
    }
}
