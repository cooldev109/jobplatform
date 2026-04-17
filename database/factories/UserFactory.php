<?php

namespace Database\Factories;

use App\Domains\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'user_type' => User::TYPE_CANDIDATE,
            'remember_token' => Str::random(10),
        ];
    }

    public function candidate(): static
    {
        return $this->state(fn () => ['user_type' => User::TYPE_CANDIDATE]);
    }

    public function companyOwner(): static
    {
        return $this->state(fn () => ['user_type' => User::TYPE_COMPANY_OWNER]);
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }
}
