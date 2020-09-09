<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests;

use Arcanedev\LaravelMetrics\Tests\Stubs\Database\Factories\{UserFactory, PostFactory};
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * Class     TestCase
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Arcanedev\LaravelMetrics\MetricServiceProvider::class,
        ];
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Load the migrations.
     */
    protected function loadMigrations()
    {
        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--path'     => realpath(__DIR__ .'/fixtures/migrations'),
        ]);
    }

    /**
     * Create posts for the tests.
     *
     * @param  \Illuminate\Support\Carbon|null  $now
     *
     * @return \Illuminate\Support\Collection
     */
    protected function createPosts($now = null)
    {
        $now = $now ?: Carbon::now();

        return new Collection([
            PostFactory::new(['views' => 50, 'published_at' => (clone $now)->subDays(30)])->create(),
            PostFactory::new(['views' => 40, 'published_at' => (clone $now)->subDays(14)])->create(),
            PostFactory::new(['views' => 30, 'published_at' => (clone $now)->subDays(7)])->create(),
            PostFactory::new(['views' => 20, 'published_at' => (clone $now)->subDays(3)])->create(),
            PostFactory::new(['views' => 10, 'published_at' => $now])->create(),
        ]);
    }

    /**
     * Create users for tests.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function createUsers()
    {
        $now = Carbon::now();

        return new Collection([
            // GOLD
            UserFactory::new(['points' => 2000])->gold()->verified($now)->premium()->create(),
            UserFactory::new(['points' => 1000])->gold()->verified($now)->count(2)->create(),

            // SILVER
            UserFactory::new(['points' => 300])->silver()->verified($now)->count(2)->create(),
            UserFactory::new(['points' => 250])->silver()->count(2)->create(),

            // BRONZE
            UserFactory::new(['points' => 100])->bronze()->verified($now)->count(3)->create(),
            UserFactory::new(['points' => 50])->bronze()->count(5)->create(),
        ]);
    }
}
