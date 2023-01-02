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
        $image_list = [
            "https://res.cloudinary.com/ds5zchxhn/image/upload/v1672218361/qxdjh3gd8gnc3tswd6zw.jpg",
            "https://res.cloudinary.com/ds5zchxhn/image/upload/v1672218392/v1quwtuvauoiovark9bb.jpg",
            "https://res.cloudinary.com/ds5zchxhn/image/upload/v1672218428/jkyynturuhftmbfpzxll.jpg",
            "https://res.cloudinary.com/ds5zchxhn/image/upload/v1672218454/tueytqtv3epxxvbvb4h7.jpg",
            "https://res.cloudinary.com/ds5zchxhn/image/upload/v1672218332/dq2ouorejtswv3ipctxw.jpg",
            "https://res.cloudinary.com/ds5zchxhn/image/upload/v1672218297/yojdvjifctmxyawekhlw.jpg",
            "https://res.cloudinary.com/ds5zchxhn/image/upload/v1672218276/hzmbvw7d6ofhdn9ivhfi.jpg",
            "https://res.cloudinary.com/ds5zchxhn/image/upload/v1672218255/qurdaizmrryxmbn0xkkx.jpg",
            ];
        
        return [
            'body' => fake()->text($maxNbChars = 50),
            'user_id' => mt_rand(1, 50),
            'image' => $image_list[ mt_rand(0, count($image_list)-1) ],
            'tag1' => fake()->text($maxNbChars = 5),
            'tag2' => fake()->text($maxNbChars = 5),
            'tag3' => fake()->text($maxNbChars = 5),
            'tag4' => fake()->text($maxNbChars = 5),
            'tag5' => fake()->text($maxNbChars = 5),
            'count_likes' => fake()->randomNumber(1,9),
        ];
    }
}
