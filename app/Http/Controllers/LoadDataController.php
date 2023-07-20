<?php

namespace App\Http\Controllers;

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
        $data = $postRepository->all();
        return $this->loadDataFromDbToCache($key, $data);
    }

    public function threadKey(ThreadRepository $threadRepository)
    {
        $key = 'thread';
        $data = $threadRepository->all();
        return $this->loadDataFromDbToCache($key, $data);
    }

    public function replyKey(ReplyRepository $replyRepository)
    {
        $key = 'reply';
        $data = $replyRepository->all();
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
