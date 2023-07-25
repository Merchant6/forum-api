<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\Reply;
use App\Models\Thread;
use App\Traits\RedisHelpers;

class ReplyObserver
{
    use RedisHelpers;

    protected $key = 'reply_';
    protected $threadKey = 'thread_';
    protected $postKey = 'post_';
    protected $postObserver;
    protected $threadObserver;

    public function __construct(PostObserver $postObserver, ThreadObserver $threadObserver)
    {
        $this->postObserver = $postObserver;
        $this->threadObserver = $threadObserver;
    }
    /**
     * Handle the Reply "created" event.
     */
    public function created(Reply $reply): void
    {
        $key = $this->key.$reply->id;
        $data = $this->data($reply);

        $this->set($key, $data);

        //Updating keys for threads and post to add the created or updated reply
        $this->createKey($reply);
    }

    /**
     * Handle the Reply "updated" event.
     */
    public function updated(Reply $reply): void
    {
        $key = $this->key.$reply->id;
        $data = $this->data($reply);

        $this->del($key);
        $this->set($key, $data);

        //Updating keys for threads and post to add the created or updated reply
        $this->createKey($reply);
    }

    /**
     * Handle the Reply "deleted" event.
     */
    public function deleted(Reply $reply): void
    {
        $key = $this->key.$reply->id;
        $this->del($key);
    }

    /**
     * Handle the Reply "restored" event.
     */
    public function restored(Reply $reply): void
    {
        //
    }

    /**
     * Handle the Reply "force deleted" event.
     */
    public function forceDeleted(Reply $reply): void
    {
        //
    }

    public function data(Reply $reply)
    {
        return json_encode([
                    'id' => $reply->id,
                    'post_id' => $reply->post_id,
                    'thread_id' => $reply->thread_id,
                    'user_id' => $reply->user_id,
                    'content' => $reply->content,
                    'created_at' => $reply->created_at,
                    'updated_at' => $reply->updated_at,
                ]);
    }

    public function createKey(Reply $reply)
    {
        //Updating the related thread to reflect the updated replies
        $thread = new Thread();
        $threadKey = $this->threadKey.$reply->thread_id;
        $threadData = $thread->getSingleRecordWithRelations($reply->thread_id);

        $this->del($threadKey);
        $this->set($threadKey, $threadData);

        //Updating the related post to reflect the updated replies
        $post = new Post();
        $postKey = $this->postKey.$reply->post_id;
        $postData = $post->getSingleRecordWithRelations($reply->post_id);

        $this->del($postKey);
        $this->set($postKey, $postData);
    }
}
