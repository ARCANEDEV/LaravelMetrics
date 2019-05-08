<?php namespace Arcanedev\LaravelMetrics\Tests\Results;

use Arcanedev\LaravelMetrics\Results\RangedValueResult;
use Arcanedev\LaravelMetrics\Tests\TestCase;

/**
 * Class     RangedResultTest
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Results
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class RangedResultTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $result = new RangedValueResult;

        $expectations = [
            \JsonSerializable::class,
            \Illuminate\Contracts\Support\Jsonable::class,
            \Illuminate\Contracts\Support\Arrayable::class,
            \Arcanedev\LaravelMetrics\Results\Result::class,
            \Arcanedev\LaravelMetrics\Results\RangedValueResult::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $result);
        }

        self::assertNull($result->value);
    }

    /** @test */
    public function it_can_set_value()
    {
        $result = (new RangedValueResult)->value(10);

        self::assertSame(10, $result->value);
    }

    /** @test */
    public function it_can_set_prefix()
    {
        $result = (new RangedValueResult)->prefix('$');

        self::assertSame('$', $result->prefix);
    }

    /** @test */
    public function it_can_set_suffix()
    {
        $result = (new RangedValueResult)->suffix('$');

        self::assertSame('$', $result->suffix);
    }

    /** @test */
    public function it_can_set_format()
    {
        $result = (new RangedValueResult)->format('0,00');

        self::assertSame('0,00', $result->format);
    }

    /** @test */
    public function it_can_convert_to_array()
    {
        $result = (new RangedValueResult(123))
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
        $result = (new RangedValueResult(123))
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
}
