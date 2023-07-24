<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Models\Thread;
use App\Traits\RedisHelpers;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class ThreadRepository implements RepositoryInterface
{
    use RedisHelpers;

    protected $model;

    /**
     * Summary of __construct
     * @param \App\Models\Post $post
     */
    public function __construct(Thread $thread)
    {
        $this->model = $thread;
    }


    /**
     * Set and Get data from
     * @return mixed
     */
    public function all()
    {
        $data = $this->model->getAllRecordsWithRelations();
        $getAll = $this->getAll('thread*');
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
        return $this->model::create([
            'post_id' => $data['post_id'],
            'user_id' => auth('api')->user()->id,
            'content' => $data['content']
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
        $key = 'thread_'.$id;
        // $searchParam = $slug;

        $getSingle = $this->get($key);

        // if($getSingle)
        // {
        //     return $getSingle;
        // }

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