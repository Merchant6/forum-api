<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Repositories\PostRepository;
use App\Traits\RedisHelpers;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class PostUpvoteController extends Controller
{
    use RedisHelpers;
    
    protected $post;
    protected $postRepository;

    public function __construct(Post $post, PostRepository $postRepository)
    {
        $this->post = $post;
        $this->postRepository = $postRepository;
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

            $updated = $this->postRepository->update($id, ['up_votes' =>  $this->get($key)]);

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
