<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Models\Reply;
use App\Traits\RedisHelpers;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class ReplyRepository implements RepositoryInterface
{
    use RedisHelpers;

    protected $model;

    protected $key;

    /**
     * Summary of __construct
     * @param \App\Models\Post $post
     */
    public function __construct(Reply $reply)
    {
        $this->model = $reply;
        $this->key = 'reply_*';
    }


    /**
     * Set and Get data from
     * @return mixed
     */
    public function all()
    {
        $data = $this->model->all();
        $getAll = $this->getAll($this->key);
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
            'thread_id' => $data['thread_id'],
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
        $key = 'reply_'.$id;

        $getSingle = $this->getSingle($key);

        if($getSingle)
        {
            return $getSingle;
        }

        $dummy = $this->model->where('id', $id)->first();
        return $dummy;
    

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