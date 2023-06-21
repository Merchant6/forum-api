<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Models\Post;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Request;
use App\Traits\RedisHelpers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
        $cacheGet = $this->get('post');

        if($this->has('post'))
        {
            return response()->json([
                'data' => $cacheGet
            ], 200);
        }

        return response()->json([
            'error' => 'No posts found'
        ], 404);

    }

    public function store($data)
    {
        $slug = Str::slug($data['title']);
        
        return $this->model::create([
            'category_id' => $data['category_id'],
            'user_id' => auth()->user()->id,
            'title' => $data['title'],
            'content' => $data['content'],
            'slug' => Str::slug($data['title'])
        ]);
    }

    public function show(string $value)
    {
        if($this->has('post'))
        {
            $data = $this->get('post');
            $key = 'slug';

            // foreach($data as $result)
            // {
            //     if($result->$key == $slug)
            //     {
            //         $value = $result;
            //         break;
            //     }

            //     $value = response()->json(['error' => 'Resource not found.']);
            // }
            return $this->getSingle($data, $key, $value);
        }

        $post = Post::where('slug', $value)->first();
        return $post;
    }

    public function update(Request $request, string $id)
    {

    }

    public function destroy(string $id)
    {

    }

}