<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Traits\RedisHelpers;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class PostUpvoteController extends Controller
{
    use RedisHelpers;
    protected $post;
    public static $upvotes = 0;
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function show(string $id)
    {
        try 
        {
            $post = $this->post->getSingleRecordWithRelations($id);

            $postArray = [
                'id' => $post[0]->id, 
                'title' => $post[0]->title, 
                'up_votes' => $post[0]->up_votes, 
                'down_votes' => $post[0]->down_votes
            ];

            return $postArray;
        } 
        catch (\Exception $e) 
        {
            return $e->getMessage();
        }
    }

    public function update(string $id)
    {
        try
        {
            $post = $this->post->getSingleRecordWithRelations($id);

            $upVotes = $post[0]->up_votes;

            $key = "post_{$id}_upvotes";

            if(!$this->has($key))
            {
                return $this->set($key, $upVotes);
            }

            $this->incr($key);
            $this->get($key);
            $updated = $this->post->findOrFail($id)->update(['up_votes' =>  $this->get($key)]);
            if($updated)
            {
                return response()->json([
                    'success' => 'You upvoted the post, enjoy Laravel.'
                ], 200);
            }

            return response()->json([
                'error' => 'There was an error upvoting the post, try again later.'
            ], 200);
            
        }    
        catch(\Exception $e)
        {
            return response()->json([
                'error' => 'Something went wrong.'
            ], 500);
        }
    
    }
}
