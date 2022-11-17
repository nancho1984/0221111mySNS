<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'body' => fake()->text($maxNbChars = 50),
            'image' => fake()->text($maxNbChars = 5),
            'tag1' => fake()->text($maxNbChars = 5),
            'tag2' => fake()->text($maxNbChars = 5),
            'tag3' => fake()->text($maxNbChars = 5),
            'tag4' => fake()->text($maxNbChars = 5),
            'tag5' => fake()->text($maxNbChars = 5),
            'user_id' => 1,
        ];
    }
}
