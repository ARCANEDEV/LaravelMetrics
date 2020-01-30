<?php namespace Arcanedev\LaravelMetrics\Tests;

use Arcanedev\LaravelMetrics\Tests\Stubs\Models\Post;
use Arcanedev\LaravelMetrics\Tests\Stubs\Models\User;
use Cake\Chronos\Chronos;
use Illuminate\Support\Collection;
use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * Class     TestCase
 *
 * @package  Arcanedev\LaravelMetrics\Tests
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
     * Load the factories.
     */
    protected function loadFactories()
    {
        $this->withFactories(__DIR__.'/fixtures/factories');
    }

    /**
     * Create posts for the tests.
     *
     * @param  \Cake\Chronos\Chronos|null  $now
     *
     * @return \Illuminate\Support\Collection
     */
    protected function createPosts($now = null)
    {
        $now = $now ?: Chronos::now();

        return new Collection([
            factory(Post::class)->create(['views' => 50, 'published_at' => $now->subDays(30)]),
            factory(Post::class)->create(['views' => 40, 'published_at' => $now->subDays(14)]),
            factory(Post::class)->create(['views' => 30, 'published_at' => $now->subDays(7)]),
            factory(Post::class)->create(['views' => 20, 'published_at' => $now->subDays(3)]),
            factory(Post::class)->create(['views' => 10, 'published_at' => $now]),
        ]);
    }

    /**
     * Create users for tests.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function createUsers()
    {
        return new Collection([
            // GOLD
            factory(User::class, 1)->states(['gold', 'premium', 'verified'])->create(['points' => 2000]),
            factory(User::class, 2)->states(['gold', 'verified'])->create(['points' => 1000]),

            // SILVER
            factory(User::class, 2)->states(['silver', 'verified'])->create(['points' => 300]),
            factory(User::class, 2)->states(['silver'])->create(['points' => 250]),

            // BRONZE
            factory(User::class, 3)->states(['bronze', 'verified'])->create(['points' => 100]),
            factory(User::class, 5)->states(['bronze'])->create(['points' => 50]),
        ]);
    }
}
