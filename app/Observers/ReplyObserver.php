<?php

namespace App\Observers;

use App\Models\Reply;
use App\Traits\RedisHelpers;

class ReplyObserver
{
    use RedisHelpers;

    protected $key = 'reply_';
    /**
     * Handle the Reply "created" event.
     */
    public function created(Reply $reply): void
    {
        $key = $this->key.$reply->id;
        $data = $this->data($reply);

        $this->set($key, $data);
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
}
