<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reply>
 */
class ReplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $post = Post::get()->random()->id;
        $thread = Thread::where('post_id', $post)->first();
        
        return [
            'post_id' => $post,
            'thread_id' => !empty($thread) ? $thread->id :0,
            'user_id' => User::get()->random()->id,
            'content' => fake()->paragraph(1),
        ];
    }
}
