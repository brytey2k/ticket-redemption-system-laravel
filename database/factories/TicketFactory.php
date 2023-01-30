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
        ];
    }

    public function redeemed(bool $state = true)
    {
        return $this->state(function (array $attributes) use ($state) {
            return [
                'status' => $state ? 'redeemed' : 'not_redeemed',
            ];
        });
    }

}
