<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Results;

use Arcanedev\LaravelMetrics\Results\PartitionResult;
use Illuminate\Support\Collection;

/**
 * Class     PartitionResultTest
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class PartitionResultTest extends ResultTestCase
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

        static::assertInstanceOf(Collection::class, $result->value);
        static::assertTrue($result->value->isEmpty());
    }

    /** @test */
    public function it_can_set_value(): void
    {
        $result = $this->makeResult(10);

        static::assertInstanceOf(Collection::class, $result->value);
        static::assertCount(1, $result->value);
        static::assertEquals([10], $result->value->all());

        $result = $this->makeResult()->value(10);

        static::assertInstanceOf(Collection::class, $result->value);
        static::assertCount(1, $result->value);
        static::assertEquals([10], $result->value->all());

        $result->value($expected = ['key' => 'hello', 'value' => 'world']);

        static::assertInstanceOf(Collection::class, $result->value);
        static::assertCount(2, $result->value);
        static::assertEquals($expected, $result->value->all());
    }

    /** @test */
    public function it_can_convert_to_array(): void
    {
        $result = $this->makeResult(['plan-1' => 123, 'plan-2' => 321])
            ->prefix('$')
            ->suffix('per unit')
            ->format('0,00');

        $expected = [
            'value'  => [
                ['value' => 123, 'label' => 'plan-1'],
                ['value' => 321, 'label' => 'plan-2'],
            ],
            'format' => '0,00',
            'prefix' => '$',
            'suffix' => 'per unit',
        ];

        static::assertEquals($expected, $result->toArray());
    }

    /** @test */
    public function it_can_convert_to_json(): void
    {
        $result = $this->makeResult(['plan-1' => 123, 'plan-2' => 321])
            ->prefix('$')
            ->suffix('per unit')
            ->format('0,00');

        $expected = json_encode([
            'value'  => [
                ['value' => 123, 'label' => 'plan-1'],
                ['value' => 321, 'label' => 'plan-2'],
            ],
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
     * Make a result.
     *
     * @param  mixed|null  $value
     *
     * @return \Arcanedev\LaravelMetrics\Results\PartitionResult
     */
    protected function makeResult($value = null): PartitionResult
    {
        return new PartitionResult($value);
    }
}
