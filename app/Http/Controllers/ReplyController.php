<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReplyStoreRequest;
use App\Http\Requests\ReplyUpdateRequest;
use App\Models\Reply;
use App\Traits\RedisHelpers;
use Illuminate\Http\Request;
use App\Repositories\ReplyRepository;
class ReplyController extends Controller
{

    use RedisHelpers;
    protected $replyRepository;
    protected $model;

    public function __construct(ReplyRepository $replyRepository ,Reply $reply)
    {
        $this->replyRepository = $replyRepository;
        $this->model = $reply;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reply = $this->replyRepository->all();
        if($reply)
        {
            return response()->json([
                'data' => $reply
            ], 200);
        }

        return response()->json([
            'error' => 'The reply does not exist.'
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
    public function store(ReplyStoreRequest $request)
    {
        try
        {
         $val = $request->validated();
 
         if($val)
         {
             $this->replyRepository->store($val);
 
             return response()->json([
                 'success' => 'Your reply has been added.'
             ], 200);
         }
 
         return response()->json([
             'error' => 'There was an issue creating your reply, try again later.'
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
        return $this->replyRepository->show($id);
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
    public function update(ReplyUpdateRequest $request, string $id)
    {
        $requestData = $request->validated();
        $updated =  $this->replyRepository->update($id, $requestData);
        
        if($updated)
        {
            return response()->json([
                'success' => 'Your reply has been updated.'
            ], 200);
        }

        return response()->json([
            'error' => 'The reply you want to update is removed or hidden.'
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            $deleted = $this->replyRepository->destroy($id);

            if($deleted)
            {
                return response()->json(['success' => 'The reply has been deleted'], 200);
            }
          
            return response()->json(['error' => 'No reply found, try another.'], 404);

        } 

        catch (\Exception $e) 
        {
            return $e->getMessage();
        }
    }
}
