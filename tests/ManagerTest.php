<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests;

use Arcanedev\LaravelMetrics\Contracts\Manager as ManagerContract;
use Arcanedev\LaravelMetrics\Tests\Stubs\Metrics\Value\{AveragePostViews, TotalPosts};
use Illuminate\Support\Collection;

/**
 * Class     ManagerTest
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ManagerTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\LaravelMetrics\Contracts\Manager */
    private $manager;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = $this->app->get(ManagerContract::class);
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated(): void
    {
        $expectations = [
            \Arcanedev\LaravelMetrics\Contracts\Manager::class,
            \Arcanedev\LaravelMetrics\Manager::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->manager);
        }

        static::assertEmpty($this->manager->registered());
        static::assertEmpty($this->manager->selected());
        static::assertFalse($this->manager->hasSelected());
    }

    /** @test */
    public function it_can_set_selected_metrics(): void
    {
        $metrics = [TotalPosts::class, AveragePostViews::class];

        static::assertFalse($this->manager->hasSelected());
        static::assertEmpty($this->manager->selected());

        $this->manager->setSelected($metrics);

        static::assertTrue($this->manager->hasSelected());
        static::assertEquals($metrics, $this->manager->selected());
    }

    /** @test */
    public function it_can_register_metrics(): void
    {
        $metrics = [TotalPosts::class, AveragePostViews::class];

        static::assertEmpty($this->manager->selected());

        $this->manager->register($metrics);

        static::assertEquals($metrics, $this->manager->registered());

        foreach ($metrics as $metric) {
            static::assertTrue($this->manager->isRegistered($metric));
        }
    }

    /** @test */
    public function it_can_get_only_registered_metric(): void
    {
        $metric = $this->manager->get(TotalPosts::class);

        static::assertNull($metric);

        $this->manager->register([TotalPosts::class]);

        $metric = $this->manager->get(TotalPosts::class);

        static::assertInstanceOf(TotalPosts::class, $metric);
    }

    /** @test */
    public function it_can_make_and_register_metric(): void
    {
        static::assertEmpty($this->manager->registered());

        $metric = $this->manager->make(TotalPosts::class);

        static::assertInstanceOf(TotalPosts::class, $metric);

        static::assertEquals([TotalPosts::class], $this->manager->registered());
    }

    /** @test */
    public function it_can_get_selected_metrics_instances(): void
    {
        $metrics = [TotalPosts::class, AveragePostViews::class];

        $this->manager->setSelected($metrics);

        static::assertEquals($metrics, $this->manager->selected());
        static::assertEquals([], $this->manager->registered());

        $actual = $this->manager->makeSelected();

        static::assertInstanceOf(Collection::class, $actual);
        static::assertCount(2, $actual);
        static::assertEquals($metrics, $this->manager->registered());
    }
}
