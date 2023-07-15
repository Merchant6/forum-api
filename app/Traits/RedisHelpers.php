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

    /**
     * Helper function for Redis::mget()
     * @param mixed $key
     * @return mixed
     */
    public function mget($keys)
    {
        $redisData = Redis::mget($keys);
        $decodedData = array_map('json_decode', $redisData);

        return $decodedData;
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

    public function getSingle(iterable $data, string $key, string $searchParam)
    {
        $output = '';
        foreach($data as $result)
        {
            if($result->$key == $searchParam)
            {
                $output = $result;
                break;
            }
        }

        return $output;
    }

    public function getAll($pattern)
    {
        
        $cursor = 0;
        $keys = [];

        do {
            [$cursor, $result] = Redis::scan($cursor, 'MATCH', $pattern);

            $keys = array_merge($keys, $result);

        } while ($cursor != 0);

        
        $mget = $this->mget($keys);
        return $mget;

    }

    //Only for development purposes to load seeded data to redis cache
    public function loadDataFromDbToCache($key, $data)
    {
        $redisData = []; 
        foreach($data as $value)
        {
            $set = $this->set("{$key}_{$value->id}", json_encode($value));
            $redisData[] = $this->get("{$key}_{$value->id}");
        }

        return $redisData;
    }
}
