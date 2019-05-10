<?php

use Arcanedev\LaravelMetrics\Tests\Stubs\Models\User;
use Faker\Generator as Faker;

/** @var  \Illuminate\Database\Eloquent\Factory  $factory */
$factory->define(User::class, function (Faker $faker) {
    return [
        'name'       => $faker->name,
        'email'      => $faker->unique()->safeEmail,
        'type'       => $faker->randomElement(['bronze', 'silver', 'gold']),
        'points'     => $faker->randomNumber(3),
        'is_premium' => false,
    ];
});

$factory->state(User::class, 'premium', ['is_premium' => true]);

$factory->state(User::class, 'bronze', ['type' => 'bronze']);
$factory->state(User::class, 'silver', ['type' => 'silver']);
$factory->state(User::class, 'gold', ['type' => 'gold']);

$factory->state(User::class, 'verified', ['verified_at' => now()]);
