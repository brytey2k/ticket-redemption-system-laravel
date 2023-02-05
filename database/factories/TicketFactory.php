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
        $status = $this->faker->randomElement(['redeemed', 'not_redeemed']);
        return [
            'code' => \Str::random(10),
            'user_id' => $status === 'redeemed' ? User::cursor()->random() : null,
            'status' => $status,
            'redeemed_at' => $status === 'redeemed' ? now()->format('Y-m-d H:i:s') : null,
        ];
    }

    public function redeemed(bool $state = true)
    {
        return $this->state(function (array $attributes) use ($state) {
            $status = $state ? 'redeemed' : 'not_redeemed';
            $redeemedAt = $status === 'redeemed' ? $this->faker->dateTime : null;

            if($status === 'redeemed') {
                return [
                    'status' => 'redeemed',
                    'redeemed_at' => $redeemedAt,
                ];
            } else {
                return [
                    'status' => 'not_redeemed',
                    'redeemed_at' => null,
                    'user_id' => null,
                ];
            }
        });
    }

}
