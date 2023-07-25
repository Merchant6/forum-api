<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\Thread;
use App\Traits\RedisHelpers;

class ThreadObserver
{
    use RedisHelpers;

    protected $key = 'thread_';
    protected $postKey = 'post_';
    protected $postObserver;

    public function __construct(PostObserver $postObserver)
    {
        $this->postObserver = $postObserver;
    }


    /**
     * Handle the Thread "created" event.
     */
    public function created(Thread $thread): void
    {
        $key = $this->key.$thread->id; 
        $data = $this->data($thread);

        $this->set($key, $data);

        //Updating the related post to reflect the updated thread
        $post = new Post();
        $postKey = $this->postKey.$thread->post_id;
        $postData = $post->getSingleRecordWithRelations($thread->post_id);

        error_log($postData);

        $this->del($postKey);
        $this->set($postKey, $postData);
        
    }

    /**
     * Handle the Thread "updated" event.
     */
    public function updated(Thread $thread): void
    {
        $key = $this->key.$thread->id;
        $data = $this->data($thread);

        $this->del($key);
        $this->set($key, $data);

        //Updating the related post to reflect the updated thread
        $post = new Post();
        $postKey = $this->postKey.$thread->post_id;
        $postData = $this->postObserver->data($post);

        $this->del($postKey);
        $this->set($postKey, $postData);

    }

    /**
     * Handle the Thread "deleted" event.
     */
    public function deleted(Thread $thread): void
    {
        $key = $this->key.$thread->id;
        $this->del($key);
    }

    /**
     * Handle the Thread "restored" event.
     */
    public function restored(Thread $thread): void
    {
        //
    }

    /**
     * Handle the Thread "force deleted" event.
     */
    public function forceDeleted(Thread $thread): void
    {
        //
    }

    public function data(Thread $thread)
    {
        return json_encode([
                    'id' => $thread->id,
                    'post_id' => $thread->post_id,
                    'user_id' => $thread->user_id,
                    'content' => $thread->content,
                    'created_at' => $thread->created_at,
                    'updated_at' => $thread->updated_at,
                ]);
    }
}
