<?php

declare(strict_types=1);

namespace Arcanedev\LaravelMetrics\Tests\Stubs\Database\Factories;

use Arcanedev\LaravelMetrics\Tests\Stubs\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class     UserFactory
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class UserFactory extends Factory
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
    protected $model = User::class;

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
            'name'       => $this->faker->name,
            'email'      => $this->faker->unique()->safeEmail,
            'type'       => $this->faker->randomElement(['bronze', 'silver', 'gold']),
            'points'     => $this->faker->randomNumber(3),
            'is_premium' => false,
        ];
    }

    /**
     * Set the type as gold member.
     *
     * @return $this
     */
    public function gold(): self
    {
        return $this->state(['type' => 'gold']);
    }

    /**
     * Set the type as silver member.
     *
     * @return $this
     */
    public function silver(): self
    {
        return $this->state(['type' => 'silver']);
    }

    /**
     * Set the type as silver member.
     *
     * @return $this
     */
    public function bronze(): self
    {
        return $this->state(['type' => 'bronze']);
    }

    /**
     * Set the user as a premium member.
     *
     * @return $this
     */
    public function premium(): self
    {
        return $this->state(['is_premium' => true]);
    }

    /**
     * Set as a verified user.
     *
     * @param  \Carbon\Carbon  $verifiedAt
     *
     * @return $this
     */
    public function verified($verifiedAt = null): self
    {
        return $this->state([
            'verified_at' => $verifiedAt ?: Carbon::now(),
        ]);
    }
}
