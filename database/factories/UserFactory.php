<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'country' => str_replace(["'", '"'], '', fake()->country()),
            'city' => str_replace(["'", '"'], '', fake()->city()),
            'address' => str_replace(["'", '"'], '', fake()->address()),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'referral_code' => $this->generateUniqueReferralCode(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Generate unique referral code.
     */
    private function generateUniqueReferralCode()
    {
        do {
            $referralCode = 'me' . rand(10000, 99999);
        } while (DB::table('users')->where('referral_code', $referralCode)->exists());

        return $referralCode;
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
