<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThreadStoreRequest;
use App\Http\Requests\ThreadUpdateRequest;
use App\Models\Thread;
use App\Repositories\ThreadRepository;
use App\Traits\RedisHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ThreadController extends Controller
{

    use RedisHelpers;
    protected $threadRepository;
    protected $model;

    public function __construct(ThreadRepository $threadRepository ,Thread $thread)
    {
        $this->threadRepository = $threadRepository;
        $this->model = $thread;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $thread = $this->threadRepository->all();
        if($thread)
        {
            return response()->json([
                'data' => $thread
            ], 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ThreadStoreRequest $request)
    {
        try
       {
        $val = $request->validated();

        if($val)
        {
            $this->threadRepository->store($val);

            return response()->json([
                'success' => 'Your comment has been added.'
            ], 200);
        }

        return response()->json([
            'error' => 'There was an issue creating your comment, try again later.'
        ], 404);
       }

       catch(\Exception $e)
       {
         return $e->getMessage();
       }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->threadRepository->show($id);
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
    public function update(ThreadUpdateRequest $request, string $id)
    {
        $requestData = $request->validated();
        $updated =  $this->threadRepository->update($id, $requestData);
        
        if($updated)
        {
            return response()->json([
                'success' => 'Your comment has been updated.'
            ], 200);
        }

        return response()->json([
            'error' => 'The comment you want to update is removed or hidden.'
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $deleted = $this->threadRepository->destroy($id);

            if($deleted)
            {
                return response()->json(['success' => 'The post has been deleted'], 200);
            }
          
            return response()->json(['error' => 'No post found, try another.'], 404);

            // return $this->postRepository->destroy($id);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        
    }
}
