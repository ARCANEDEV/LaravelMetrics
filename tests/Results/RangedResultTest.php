<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Results;

use Arcanedev\LaravelMetrics\Results\RangedValueResult;

/**
 * Class     RangedResultTest
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RangedResultTest extends ResultTestCase
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
        $result = $this->makeResult()->value(10);

        static::assertSame(10, $result->value);

        $excepted = [
            'value'    => 10,
            'format'   => null,
            'prefix'   => null,
            'suffix'   => null,
            'previous' => [
                'value' => null,
                'label' => null,
            ],
            'change' => [
                'value'  => null,
                'label'  => 'No Prior Data',
                'growth' => 'no_prior_data',
            ],
        ];

        static::assertEquals($excepted, $result->toArray());
    }

    /** @test */
    public function it_can_convert_to_array(): void
    {
        $result = $this->makeResult(150)
            ->prefix('$')
            ->suffix('per unit')
            ->format('0,00')
            ->previous(50, 'Previous value');

        $expected = [
            'value'    => 150,
            'format'   => '0,00',
            'prefix'   => '$',
            'suffix'   => 'per unit',
            'previous' => [
                'value' => 50,
                'label' => 'Previous value',
            ],
            'change' => [
                'value'  => 66.67,
                'label'  => '66.67% Increase',
                'growth' => 'increase',
            ],
        ];

        static::assertEquals($expected, $result->toArray());
    }

    /** @test */
    public function it_can_convert_to_json(): void
    {
        $result = $this->makeResult(100)
            ->prefix('$')
            ->suffix('per unit')
            ->format('0,00')
            ->previous(200, 'Previous value');

        $expected = json_encode([
            'value'    => 100,
            'format'   => '0,00',
            'prefix'   => '$',
            'suffix'   => 'per unit',
            'previous' => [
                'value' => 200,
                'label' => 'Previous value',
            ],
            'change' => [
                'value'  => -100,
                'label'  => '100% Decrease',
                'growth' => 'decrease',
            ],
        ]);

        static::assertJson($actual = $result->toJson());
        static::assertJsonStringEqualsJsonString($expected, $actual);

        static::assertJson($actual = json_encode($result));
        static::assertJsonStringEqualsJsonString($expected, $actual);
    }

    /** @test */
    public function it_can_calculate_change(): void
    {
        $result = $this->makeResult(10);

        $expected = [
            'value'    => 10,
            'format'   => null,
            'prefix'   => null,
            'suffix'   => null,
            'previous' => [
                'value' => null,
                'label' => null,
            ],
            'change'   => [
                'value'  => null,
                'label'  => 'No Prior Data',
                'growth' => 'no_prior_data'
            ],
        ];

        static::assertEquals($expected, $result->toArray());

        $result = $this->makeResult()->previous(10);

        $expected = [
            'value'    => null,
            'format'   => null,
            'prefix'   => null,
            'suffix'   => null,
            'previous' => [
                'value' => 10,
                'label' => null,
            ],
            'change'   => [
                'value'  => -100.0,
                'label'  => '100% Decrease',
                'growth' => 'decrease',
            ],
        ];

        static::assertEquals($expected, $result->toArray());

        $result = $this->makeResult(10)->previous(10);

        $expected = [
            'value'    => 10,
            'format'   => null,
            'prefix'   => null,
            'suffix'   => null,
            'previous' => [
                'value' => 10,
                'label' => null,
            ],
            'change'   => [
                'value'  => 0,
                'label'  => 'Constant',
                'growth' => 'constant',
            ],
        ];

        static::assertEquals($expected, $result->toArray());
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Make a result instance.
     *
     * @param mixed|null $value
     *
     * @return \Arcanedev\LaravelMetrics\Results\RangedValueResult|mixed
     */
    protected function makeResult($value = null): RangedValueResult
    {
        return new RangedValueResult($value);
    }
}
