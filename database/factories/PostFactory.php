<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
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
    public function definition(): array
    {
        return [
            'category_id' => Category::get()->random()->id,
            'user_id' => User::get()->random()->id,
            'title' => fake()->text(35),
            'content'=> fake()->text(200),
            'up_votes' => fake()->numberBetween(0, 100),
            'down_votes' => fake()->numberBetween(0, 100)
        ];
    }
}