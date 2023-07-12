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
        $data = $this->model->all();
        $getAll = $this->getAll('thread', $data);
        if($getAll)
        {
            return $getAll;
        }

        return $this->model->all();
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
            'user_id' => auth()->user()->id,
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
        $data = $this->get('thread');
        $key = 'id';
        $searchParam = $id;

        $getSingle = $this->getSingle($data, $key, $searchParam);

        if($getSingle)
        {
            return $getSingle;
        }

        $thread = $this->model->where('id', $id)->first();
        return $thread;

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
        $key = $this->model->findOrFail($id);
        return $key->delete();
    }

}