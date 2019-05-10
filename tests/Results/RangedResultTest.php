<?php namespace Arcanedev\LaravelMetrics\Tests\Results;

use Arcanedev\LaravelMetrics\Results\RangedValueResult;

/**
 * Class     RangedResultTest
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Results
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RangedResultTest extends ResultTestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $result = $this->makeResult();

        static::assertIsMetricResult($result);

        self::assertNull($result->value);
    }

    /** @test */
    public function it_can_set_value()
    {
        $result = $this->makeResult()->value(10);

        self::assertSame(10, $result->value);
    }

    /** @test */
    public function it_can_convert_to_array()
    {
        $result = $this->makeResult(123)
            ->prefix('$')
            ->suffix('per unit')
            ->format('0,00')
            ->previous(321, 'Previous value');

        $expected = [
            'value'    => 123,
            'format'   => '0,00',
            'prefix'   => '$',
            'suffix'   => 'per unit',
            'previous' => [
                'value' => 321,
                'label' => 'Previous value'
            ],
        ];

        static::assertEquals($expected, $result->toArray());
    }

    /** @test */
    public function it_can_convert_to_json()
    {
        $result = $this->makeResult(123)
            ->prefix('$')
            ->suffix('per unit')
            ->format('0,00')
            ->previous(321, 'Previous value');

        $expected = json_encode([
            'value'    => 123,
            'format'   => '0,00',
            'prefix'   => '$',
            'suffix'   => 'per unit',
            'previous' => [
                'value' => 321,
                'label' => 'Previous value',
            ],
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
     * @param mixed|null $value
     *
     * @return \Arcanedev\LaravelMetrics\Results\RangedValueResult|mixed
     */
    protected function makeResult($value = null)
    {
        return new RangedValueResult($value);
    }
}
