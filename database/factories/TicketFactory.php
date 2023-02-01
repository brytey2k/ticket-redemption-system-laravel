<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code' => \Str::random(8),
            'user_id' => User::cursor()->random(),
            'status' => $this->faker->randomElement(['redeemed', 'not_redeemed']),
            'redeemed_at' => now()->format('Y-m-d H:i:s'),
        ];
    }

    public function redeemed(bool $state = true)
    {
        return $this->state(function (array $attributes) use ($state) {
            $status = $state ? 'redeemed' : 'not_redeemed';
            $redeemedAt = $status === 'redeemed' ? $this->faker->dateTime : null;
            return [
                'status' => $state ? 'redeemed' : 'not_redeemed',
                'redeemed_at' => $redeemedAt,
            ];
        });
    }

}
