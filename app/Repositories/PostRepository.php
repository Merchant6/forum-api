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
        $data = $this->model->getAllRecordsWithRelations();
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
        return $this->model->create([
            'category_id' => $data['category_id'],
            'user_id' => auth('api')->user()->id,
            'title' => $data['title'],
            'content' => $data['content'],
        ]);
    }

    /**
     * Filter outs a specific post from Redis or from DB
     * @param string $value
     * @return mixed
     */
    public function show(string $id)
    {
        $key = 'post_'.$id;

        $getSingle = $this->get($key);

        if($getSingle)
        {
            return $getSingle;
        }

        $post = $this->model->getSingleRecordWithRelations($id);
        return $post;
    
    }

    public function update(string $id, $requestData)
    {
        return $this->model->findOrFail($id)->update($requestData);    
    
    }

    public function destroy(string $id)
    {
        return $this->model->whereId($id)->first()->delete();
    }

}