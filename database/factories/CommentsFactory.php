<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comments>
 */
class CommentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'comment' => fake()->paragraph(),
            'likes' => fake()->numberBetween($min = 0, $max = 1000000),
            'dislikes' => fake()->numberBetween($min = 0, $max = 1000000),
            'created_at' => fake()->dateTimeThisDecade()
        ];
    }
}
