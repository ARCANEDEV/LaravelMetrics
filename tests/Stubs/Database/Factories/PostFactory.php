<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Stubs\Database\Factories;

use Arcanedev\LaravelMetrics\Tests\Stubs\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class     PostFactory
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class PostFactory extends Factory
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * @inheritDoc
     */
    public function definition(): array
    {
        return [
            'title'        => $this->faker->title,
            'content'      => $this->faker->paragraphs(5, true),
            'views'        => $this->faker->randomNumber(3),
            'published_at' => now()
        ];
    }
}
