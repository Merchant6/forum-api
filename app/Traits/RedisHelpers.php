<?php

namespace App\Traits;

use Illuminate\Support\Facades\Redis;

trait RedisHelpers
{
    /**
     * Helper function to check if key exists or not
     * @param mixed $key
     * @return bool
     */
    public function has($key)
    {
        return $this->get($key) ? true : false;
    }

    /**
     * Helper function for Redis::get()
     * @param mixed $key
     * @return mixed
     */
    public function get($key)
    {
        return json_decode(Redis::get($key));
    }

    public function getSingle(iterable $data, string $key, string $slug)
    {
        $output = '';
        foreach($data as $result)
        {
            if($result->$key == $slug)
            {
                $output = $result;
                break;
            }
        }

        return $output;
    }

    /**
     * Helper for Redis::set()
     * @param mixed $key
     * @param mixed $data
     * @return mixed
     */
    public function set($key, $data)
    {
         return Redis::set($key, $data);
    }

    public function getAll($key, $data)
    {
        $set = $this->set($key, $data);
        $get = $this->get($key);

        return $get;
    }
}
