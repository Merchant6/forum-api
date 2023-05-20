<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Models\Post;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Request;
use App\Traits\RedisHelpers;

class PostRepository implements RepositoryInterface
{
    use RedisHelpers;

    protected $model;

    public function __construct(Post $post)
    {
        $this->model = $post;
    }

    public function all()
    {
        $post = $this->model::all();
        $cacheSet = $this->set('post', $post);
        $cacheGet = json_decode($this->get('post'));

        if($this->has('post'))
        {
            return response()->json([
                'data' => $cacheGet
            ], 200);
        }

        return response()->json([
            'error' => 'Not posts found'
        ], 404);

    }

    public function store()
    {

    }

    public function show(string $id)
    {

    }

    public function update(Request $request, string $id)
    {

    }

    public function destroy(string $id)
    {

    }

}