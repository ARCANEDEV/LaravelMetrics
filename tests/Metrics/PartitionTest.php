<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Metrics;

use Arcanedev\LaravelMetrics\Metrics\Partition;
use Arcanedev\LaravelMetrics\Results\PartitionResult;
use Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Partition\{
    AverageUserPointsByType, CountUserTypes, CountUserTypesWithCustomLabelsAndColors, MaxUserPointsByType,
    MinUserPointsByType, SumUserPointsByType
};
use Illuminate\Support\Collection;

/**
 * Class     PartitionTest
 *
 * @package  Arcanedev\LaravelMetrics\Tests\Metrics
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class PartitionTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_calculate_count()
    {
        $this->createUsers();

        static::assertIsPartitionMetric($metric = new CountUserTypes);

        $result = $this->calculate($metric);

        $expected = [
            'value'  => [
                [
                    'label' => 'bronze',
                    'value' => 8,
                ],
                [
                    'label' => 'gold',
                    'value' => 3,
                ],
                [
                    'label' => 'silver',
                    'value' => 4,
                ],
            ],
            'format' => null,
            'prefix' => null,
            'suffix' => null,
        ];

        static::assertInstanceOf(Collection::class, $result->value);
        static::assertEquals($expected, $result->toArray());
    }

    /** @test */
    public function it_can_calculate_average()
    {
        $this->createUsers();

        static::assertIsPartitionMetric($metric = new AverageUserPointsByType);

        $result = $this->calculate($metric);

        $expected = [
            'value'  => [
                [
                    'label' => 'bronze',
                    'value' => 69.0,
                ],
                [
                    'label' => 'gold',
                    'value' => 1333.0,
                ],
                [
                    'label' => 'silver',
                    'value' => 275.0,
                ],
            ],
            'format' => null,
            'prefix' => null,
            'suffix' => null,
        ];

        static::assertInstanceOf(Collection::class, $result->value);
        static::assertEquals($expected, $result->toArray());
    }

    /** @test */
    public function it_can_calculate_sum()
    {
        $this->createUsers();

        static::assertIsPartitionMetric($metric = new SumUserPointsByType);

        $result = $this->calculate($metric);

        $expected = [
            'value'  => [
                [
                    'label' => 'bronze',
                    'value' => 550.0,
                ],
                [
                    'label' => 'gold',
                    'value' => 4000.0,
                ],
                [
                    'label' => 'silver',
                    'value' => 1100.0,
                ],
            ],
            'format' => null,
            'prefix' => null,
            'suffix' => null,
        ];

        static::assertInstanceOf(Collection::class, $result->value);
        static::assertEquals($expected, $result->toArray());
    }

    /** @test */
    public function it_can_calculate_max()
    {
        $this->createUsers();

        static::assertIsPartitionMetric($metric = new MaxUserPointsByType);

        $result = $this->calculate($metric);

        $expected = [
            'value'  => [
                [
                    'label' => 'bronze',
                    'value' => 100.0,
                ],
                [
                    'label' => 'gold',
                    'value' => 2000.0,
                ],
                [
                    'label' => 'silver',
                    'value' => 300.0,
                ],
            ],
            'format' => null,
            'prefix' => null,
            'suffix' => null,
        ];

        static::assertInstanceOf(Collection::class, $result->value);
        static::assertEquals($expected, $result->toArray());
    }

    /** @test */
    public function it_can_calculate_min()
    {
        $this->createUsers();

        static::assertIsPartitionMetric($metric = new MinUserPointsByType);

        $result = $this->calculate($metric);

        $expected = [
            'value'  => [
                [
                    'label' => 'bronze',
                    'value' => 50.0,
                ],
                [
                    'label' => 'gold',
                    'value' => 1000.0,
                ],
                [
                    'label' => 'silver',
                    'value' => 250.0,
                ],
            ],
            'format' => null,
            'prefix' => null,
            'suffix' => null,
        ];

        static::assertInstanceOf(Collection::class, $result->value);
        static::assertEquals($expected, $result->toArray());
    }

    /** @test */
    public function it_can_calculate_and_sort()
    {
        $this->createUsers();
        static::assertIsPartitionMetric($metric = new CountUserTypes);

        $result = $this->calculate($metric);

        $expected = [
            'value'  => [
                [
                    'label' => 'bronze',
                    'value' => 8,
                ],
                [
                    'label' => 'gold',
                    'value' => 3,
                ],
                [
                    'label' => 'silver',
                    'value' => 4,
                ],
            ],
            'format' => null,
            'prefix' => null,
            'suffix' => null,
        ];

        static::assertInstanceOf(Collection::class, $result->value);
        static::assertEquals($expected, $result->toArray());

        $result->sort('asc');

        $expected = [
            'value'  => [
                [
                    'label' => 'gold',
                    'value' => 3,
                ],
                [
                    'label' => 'silver',
                    'value' => 4,
                ],
                [
                    'label' => 'bronze',
                    'value' => 8,
                ],
            ],
            'format' => null,
            'prefix' => null,
            'suffix' => null,
        ];

        static::assertEquals($expected, $result->toArray());

        $result->sort('desc');

        $expected = [
            'value'  => [
                [
                    'label' => 'bronze',
                    'value' => 8,
                ],
                [
                    'label' => 'silver',
                    'value' => 4,
                ],
                [
                    'label' => 'gold',
                    'value' => 3,
                ],
            ],
            'format' => null,
            'prefix' => null,
            'suffix' => null,
        ];

        static::assertEquals($expected, $result->toArray());
    }

    /** @test */
    public function it_can_calculate_with_custom_labels_and_colors()
    {
        $this->createUsers();
        static::assertIsPartitionMetric($metric = new CountUserTypesWithCustomLabelsAndColors);

        $result = $this->calculate($metric);

        $expected = [
            'value'  => [
                [
                    'label' => 'Bronze',
                    'value' => 8,
                    'color' => '#CD7F32',
                ],
                [
                    'label' => 'Gold',
                    'value' => 3,
                    'color' => '#FFD700',
                ],
                [
                    'label' => 'Silver',
                    'value' => 4,
                    'color' => '#C0C0C0',
                ],
            ],
            'format' => null,
            'prefix' => null,
            'suffix' => null,
        ];

        static::assertInstanceOf(Collection::class, $result->value);
        static::assertEquals($expected, $result->toArray());
    }

    /* -----------------------------------------------------------------
     |  Custom Assertions
     | -----------------------------------------------------------------
     */

    /**
     * Assert the given object is a partition metric instance.
     *
     * @param  object  $metric
     */
    protected static function assertIsPartitionMetric($metric)
    {
        static::assertIsMetric($metric);
        static::assertInstanceOf(Partition::class, $metric);
        static::assertSame('partition', $metric->type());
    }

    /**
     * Assert the given object is a partition result instance.
     *
     * @param  mixed   $actual
     * @param  string  $message
     */
    protected static function assertIsPartitionResult($actual, string $message = '')
    {
        static::assertInstanceOf(PartitionResult::class, $actual, $message);
    }
}
