<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Results;

use Arcanedev\LaravelMetrics\Results\TrendResult;

/**
 * Class     TrendResultTest
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class TrendResultTest extends ResultTestCase
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
        static::assertSame([], $result->trend);
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
    public function it_can_set_trend(): void
    {
        $result = $this->makeResult()->trend($trend = [
            '2019-3' => [
                'label' => 'March, 2019',
                'value' => 23,
            ],
            '2019-4' => [
                'label' => 'April, 2019',
                'value' => 30,
            ],
            '2019-5' => [
                'label' => 'May, 2019',
                'value' => 12,
            ],
        ]);

        static::assertEquals($trend, $result->trend);
    }

    /** @test */
    public function it_can_show_latest_value(): void
    {
        $result = $this->makeResult()->trend($trend = [
            '2019-3' => [
                'label' => 'March, 2019',
                'value' => 23,
            ],
            '2019-4' => [
                'label' => 'April, 2019',
                'value' => 30,
            ],
            '2019-5' => [
                'label' => 'May, 2019',
                'value' => 12,
            ],
        ]);

        static::assertNull($result->value);
        static::assertEquals($trend, $result->trend);

        $result->showLatestValue();

        static::assertSame(12, $result->value);
        static::assertEquals($trend, $result->trend);
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
            'trend'  => [],
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
            'trend'  => [],
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
     * @return \Arcanedev\LaravelMetrics\Results\TrendResult|mixed
     */
    protected function makeResult($value = null): TrendResult
    {
        return new TrendResult($value);
    }
}
