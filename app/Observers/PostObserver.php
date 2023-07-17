<?php

namespace App\Observers;

use App\Models\Post;
use App\Traits\RedisHelpers;
use Illuminate\Support\Facades\Log;

class PostObserver
{
    use RedisHelpers;

    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        $key = 'post_'.$post->id;
        $data = $this->data($post);

        $this->set($key, $data);
       
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        $key = 'post_'.$post->id;
        $data = $this->data($post);

        $this->del($key);
        $this->set($key, $data);
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        //
    }

    public function data(Post $post)
    {
        return json_encode([
                'id' => $post->id,
                'title' => $post->title,
                'content' => $post->content,
                'up_votes' => $post->up_votes,
                'down_votes' => $post->down_votes,
                'created_at' => $post->created_at,
                'updated_at' => $post->updated_at,
                'slug' => $post->slug,
                ]);
    }
}
