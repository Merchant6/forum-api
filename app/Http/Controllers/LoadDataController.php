<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Reply;
use App\Models\Thread;
use App\Repositories\PostRepository;
use App\Repositories\ReplyRepository;
use App\Repositories\ThreadRepository;
use App\Traits\RedisHelpers;
use Illuminate\Http\Request;

class LoadDataController extends Controller
{
    use RedisHelpers;
    // protected $postRepository;
    // protected $threadRepositry;

    public function postKey(PostRepository $postRepository)
    {
        $key = 'post';
        // $data = $postRepository->all();
        // $posts = Post::with(['user:id,username', 'threads.user:id,username', 'threads.replies.user:id,username'])
        // ->get(['id', 'user_id', 'category_id', 'title', 'content', 'slug']);

        $post = new Post();
        $posts = $post->getAllRecordsWithRelations();

        // Convert the data to an array
        $data = $posts;

        // // Serialize the data to JSON format
        // $data = json_encode($posts);


        return $this->loadDataFromDbToCache($key, $data);
    }

    public function threadKey(ThreadRepository $threadRepository)
    {
        $key = 'thread';

        $thread = new Thread();
        $threads = $thread->getAllRecordsWithRelations();
        $data = $threads;
        return $this->loadDataFromDbToCache($key, $data);
    }

    public function replyKey(ReplyRepository $replyRepository)
    {
        $key = 'reply';

        $reply = new Reply();
        $replies = $reply->getAllRecordsWithRelations();
        $data = $replies;
        return $this->loadDataFromDbToCache($key, $data);
    }

    public function load(PostRepository $postRepository, ThreadRepository $threadRepository, ReplyRepository $replyRepository)
    {
        $loadData = [
            $this->postKey($postRepository),
            $this->threadKey($threadRepository),
            $this->replyKey($replyRepository)
        ];

        return $loadData;
    }
}
