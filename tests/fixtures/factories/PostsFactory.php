<?php

use Arcanedev\LaravelMetrics\Tests\Stubs\Models\Post;
use Faker\Generator as Faker;

/** @var  \Illuminate\Database\Eloquent\Factory  $factory */
$factory->define(Post::class, function (Faker $faker) {
    return [
        'title'        => $faker->title,
        'content'      => $faker->paragraphs(5, true),
        'views'        => $faker->randomNumber(3),
        'published_at' => now()
    ];
});
