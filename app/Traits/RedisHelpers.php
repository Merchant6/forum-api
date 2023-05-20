<?php

namespace App\Traits;

use Illuminate\Support\Facades\Redis;

trait RedisHelpers
{
    public function has($key)
    {
        return $this->get($key) ? true : false;
    }

    public function get($key)
    {
        return Redis::get($key);
    }

    public function set($key, $data)
    {
         return Redis::set($key, $data);
    }
}
