<?php namespace Arcanedev\LaravelMetrics\Tests;

use Arcanedev\LaravelMetrics\Tests\Stubs\Models\Post;
use Cake\Chronos\Chronos;
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
            '--realpath' => realpath(__DIR__ .'/fixtures/migrations'),
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
     */
    protected function createPosts($now = null)
    {
        $now = $now ?: Chronos::now();

        factory(Post::class)->create(['views' => 50, 'published_at' => $now->subDays(30)]);
        factory(Post::class)->create(['views' => 40, 'published_at' => $now->subDays(14)]);
        factory(Post::class)->create(['views' => 30, 'published_at' => $now->subDays(7)]);
        factory(Post::class)->create(['views' => 20, 'published_at' => $now->subDays(3)]);
        factory(Post::class)->create(['views' => 10, 'published_at' => $now]);
    }
}
