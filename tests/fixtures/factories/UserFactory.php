<?php

use Arcanedev\LaravelMetrics\Tests\Stubs\Models\User;
use Faker\Generator as Faker;

/** @var  \Illuminate\Database\Eloquent\Factory  $factory */
$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
    ];
});
