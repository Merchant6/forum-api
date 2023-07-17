<?php

namespace App\Http\Controllers;

use App\Repositories\PostRepository;
use App\Traits\RedisHelpers;
use Illuminate\Http\Request;

class LoadDataController extends Controller
{
    use RedisHelpers;
    protected $postRepository;

    public function postKey(PostRepository $postRepository)
    {
        $key = 'post_';
        $data = $postRepository->all();
        return $this->loadDataFromDbToCache($key, $data);
    }
}
