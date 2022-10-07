<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\YoutubeVideo>
 */
class YoutubeVideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'duration' => fake()->numberBetween($min = 3, $max = 59),
            'likes' => fake()->numberBetween($min = 0, $max = 1000000),
            'dislikes' => fake()->numberBetween($min = 0, $max = 1000000),
            'views' => fake()->numberBetween($min = 0, $max = 10000000),
            // 'channel_name' => fake()->userName(),
            // 'channel_profile' => fake()->imageUrl(360, 360),
            'thumbnail' => fake()->imageUrl(640, 480, 'thumbnail', true),
            'uuid' => "watch?v=" . fake()->uuid(),
            'created_at' => fake()->dateTimeThisDecade()
        ];
    }
}
