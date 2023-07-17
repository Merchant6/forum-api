<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Models\Post;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Request;
use App\Traits\RedisHelpers;
use Illuminate\Support\Str;

class PostRepository implements RepositoryInterface
{
    use RedisHelpers;

    protected $model;

    /**
     * Summary of __construct
     * @param \App\Models\Post $post
     */
    public function __construct(Post $post)
    {
        $this->model = $post;
    }

    
    /**
     * Set and Get data from 
     * @return mixed
     */
    public function all()
    {
        $data = $this->model->all();
        $getAll = $this->getAll('post*');
        if($getAll)
        {
            return $getAll;
        }

        return $data;

    }

    /**
     * Save newly created post to DB
     * @param mixed $data
     * @return mixed
     */
    public function store($data)
    {
        $slug = Str::slug($data['title']);
        
        return $this->model::create([
            'category_id' => $data['category_id'],
            'user_id' => auth('api')->user()->id,
            'title' => $data['title'],
            'content' => $data['content'],
            'slug' => Str::slug($data['title'])
        ]);
    }

    /**
     * Filter outs a specific post from Redis or from DB
     * @param string $value
     * @return mixed
     */
    public function show(string $id)
    {
        // $data = $this->get('post');
        $key = 'post_'.$id;
        // $searchParam = $slug;

        $getSingle = $this->getSingle($key);

        if($getSingle)
        {
            return $getSingle;
        }

        $post = $this->model->where('id', $id)->first();
        return $post;
    
    }

    public function update(string $id, $requestData)
    {
        $findData = $this->model->findOrFail($id);
        if($findData)
        {
            return $this->model->whereId($id)->update($requestData); 
        }
        
    }

    public function destroy(string $id)
    {
        $post = $this->model->findOrFail($id);
        return $post->delete();
    }

}