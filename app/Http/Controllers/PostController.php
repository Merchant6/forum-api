<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostDataRequest;
use App\Http\Requests\PostDataUpdateRequest;
use App\Models\Post;
use App\Traits\RedisHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Repositories\PostRepository;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    use RedisHelpers;
    protected $postRepository;
    protected $model;
    /**
     * Summary of __construct
     * @param \App\Repositories\PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository, Post $post)
    {
        $this->postRepository = $postRepository;
        $this->model = $post;
    } 

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        if($this->has('post'))
        {
            return response()->json([
                'data' => $this->postRepository->all()
            ], 200);
        }

        return response()->json([
            'error' => 'No posts found'
        ], 404);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostDataRequest $request)
    {
       try
       {
        $data = $request->all();
        $val = $request->validated();

        if($val)
        {
            $this->postRepository->store($data);

            return response()->json([
                'success' => 'Your post has been added.'
            ], 200);
        }

        return response()->json([
            'error' => 'There was an issue creating your post, try again later.'
        ], 400);
       }

       catch(\Exception $e)
       {
         return $e->getMessage();
       }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        return $this->postRepository->show($slug);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostDataUpdateRequest $request, string $id)
    {
        
            try {
                $editData = $this->model->findOrFail($id);
                
                if($editData)
                {
                    $requestData = $request->all();
                    $val = $request->validated();
            
                    if($val)
                    {
                        $this->postRepository->update($id, $requestData);
            
                        return response()->json([
                            'success' => 'Your post has been updated.'
                        ], 200);
                    }
            
                    return response()->json([
                        'error' => 'There was an issue updating your post, try again later.'
                    ], 400);
                }
                
                return response()->json([
                    'error' => 'The post you want to update is removed or hidden.'
                ], 400);
            } 
            
            catch (\Exception $e) 
            {
                return $e->getMessage();
            }
       
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
