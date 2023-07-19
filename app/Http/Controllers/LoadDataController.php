<?php

namespace App\Http\Controllers;

use App\Repositories\PostRepository;
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

    public function load(PostRepository $postRepository, ThreadRepository $threadRepository)
    {
        $loadData = [
            $this->postKey($postRepository),
            $this->threadKey($threadRepository)
        ];

        return $loadData;
    }
}
