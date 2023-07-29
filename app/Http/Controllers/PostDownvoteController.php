<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Repositories\PostRepository;
use App\Traits\RedisHelpers;
use Illuminate\Http\Request;

class PostDownvoteController extends Controller
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

            $downVotes = $post[0]->down_votes;

            $key = "post_{$id}_downvotes";

            if(!$this->has($key))
            {
                return $this->set($key, $downVotes);
            }

            $this->incr($key);
            $this->get($key);

            $updated = $this->postRepository->update($id, ['down_votes' =>  $this->get($key)]);

            if($updated)
            {
                return response()->json([
                    'success' => 'You downvoted the post.'
                ], 200);
            }

            return response()->json([
                'error' => 'There was an error downvoting the post, try again later.'
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
