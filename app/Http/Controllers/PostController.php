<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Repositories\PostRepository;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->postRepository->all();
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
    public function store(Request $request)
    {
       try
       {
        $data = $request->all();
        $val = Validator::make($data,[
            'category_id' => ['required', 'integer'],
            // 'user_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:100', 'min:10'],
            'content' => ['required', 'string', 'min:10'],
            'up_votes' => ['nullable', 'integer'],
            'down_votes' => ['nullable', 'integer']
        ]);

        if(!$val->fails())
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
