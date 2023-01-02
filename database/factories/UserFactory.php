<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use DateTime;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $icon_list = [
            "https://res.cloudinary.com/ds5zchxhn/image/upload/v1671700406/mff4oriwa45o4oou0gzo.jpg",
            "https://res.cloudinary.com/ds5zchxhn/image/upload/v1671705163/juy8hzwxyubwpabzhbtl.jpg",
            "https://res.cloudinary.com/ds5zchxhn/image/upload/v1671705920/xdgpbxzharx9zwxavxms.jpg",
            "https://res.cloudinary.com/ds5zchxhn/image/upload/v1671706277/oakhaomqrmexgx9a53yf.png",
            "https://res.cloudinary.com/ds5zchxhn/image/upload/v1670413084/vyhpuzccgpe5shlylryu.jpg",
            "https://res.cloudinary.com/ds5zchxhn/image/upload/v1670403736/xzldde5qrmcclxdlhj9e.jpg",
            "https://res.cloudinary.com/ds5zchxhn/image/upload/v1670403016/qri9dfloosgeml1lg78m.jpg",
            ];
            
        return [
            'addressname' => fake()->unique()->word(5, 10),
            'nickname' => fake()->word(1),
            'image' => $icon_list[ mt_rand(0, count($icon_list)-1) ],
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'count_follows' => fake()->randomNumber(1,9),
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
