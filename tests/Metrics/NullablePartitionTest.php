<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Metrics;

use Arcanedev\LaravelMetrics\Metrics\NullablePartition;
use Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\NullablePartition\CountVerifiedUsers;
use Illuminate\Support\Collection;

/**
 * Class     NullablePartitionTest
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class NullablePartitionTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_calculate_count(): void
    {
        $this->createUsers();

        static::assertIsNullablePartitionMetric($metric = new CountVerifiedUsers);

        $result = $this->calculate($metric);

        $expected = [
            'value'  => [
                [
                    'label' => 'Not verified',
                    'value' => '7',
                    'color' => '#6C757D',
                ],
                [
                    'label' => 'Verified',
                    'value' => '8',
                    'color' => '#007BFF',
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
     |  Assertions
     | -----------------------------------------------------------------
     */

    /**
     * Assert the given object is a nullable partition metric instance.
     *
     * @param  object  $metric
     */
    protected static function assertIsNullablePartitionMetric($metric): void
    {
        static::assertIsMetric($metric);
        static::assertInstanceOf(NullablePartition::class, $metric);
        static::assertSame('partition', $metric->type());
    }
}
