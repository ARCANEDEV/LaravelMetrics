<?php namespace Arcanedev\LaravelMetrics\Tests\Results;

use Arcanedev\LaravelMetrics\Results\ValueResult;
use Arcanedev\LaravelMetrics\Tests\TestCase;

/**
 * Class     ValueResultTest
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Results
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ValueResultTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $result = new ValueResult;

        $expectations = [
            \JsonSerializable::class,
            \Illuminate\Contracts\Support\Jsonable::class,
            \Illuminate\Contracts\Support\Arrayable::class,
            \Arcanedev\LaravelMetrics\Results\Result::class,
            \Arcanedev\LaravelMetrics\Results\ValueResult::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $result);
        }

        self::assertNull($result->value);
    }

    /** @test */
    public function it_can_set_value()
    {
        $result = (new ValueResult)->value(10);

        self::assertSame(10, $result->value);
    }

    /** @test */
    public function it_can_set_prefix()
    {
        $result = (new ValueResult)->prefix('$');

        self::assertSame('$', $result->prefix);
    }

    /** @test */
    public function it_can_set_suffix()
    {
        $result = (new ValueResult)->suffix('$');

        self::assertSame('$', $result->suffix);
    }

    /** @test */
    public function it_can_set_format()
    {
        $result = (new ValueResult)->format('0,00');

        self::assertSame('0,00', $result->format);
    }

    /** @test */
    public function it_can_convert_to_array()
    {
        $result = (new ValueResult(123))
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
    public function it_can_convert_to_json()
    {
        $result = (new ValueResult(123))
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
}
