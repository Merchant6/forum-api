<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThreadStoreRequest;
use App\Http\Requests\ThreadUpdateRequest;
use App\Models\Thread;
use App\Repositories\ThreadRepository;
use App\Traits\RedisHelpers;
use Illuminate\Http\Request;

class ThreadController extends Controller
{

    use RedisHelpers;
    protected $threadRepository;
    protected $threadModel;

    public function __construct(ThreadRepository $threadRepository ,Thread $thread)
    {
        $this->threadRepository = $threadRepository;
        $this->threadModel = $thread;
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
        $data = $request->all();
        $val = $request->validated();

        if($val)
        {
            $this->threadRepository->store($data);

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
        $requestData = $request->input();
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
        //
    }
}
