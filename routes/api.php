<?php

use App\Http\Controllers\LoadDataController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\UserAuthController;
use App\Models\Post;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/register', [UserAuthController::class, 'register']);
Route::post('/login', [UserAuthController::class, 'login']);



Route::middleware(['auth:api'])->group(function () {

    Route::get('/data', [LoadDataController::class, 'load']);

    Route::get('int', function(){
        $post = Post::all();
        $thread = Thread::all();
        $reply = Reply::all();
    
        error_log($post->count());
        return [$post->count(), $thread->count(), $reply->count()];
    });

    
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/posts/{id}', [PostController::class, 'show']);
    Route::post('/post/update/{id}', [PostController::class, 'update']);
    Route::delete('/post/delete/{id}', [PostController::class, 'destroy']);

    Route::get('/threads', [ThreadController::class, 'index']);
    Route::post('/thread', [ThreadController::class, 'store']);
    Route::get('/thread/{id}', [ThreadController::class, 'show']);
    Route::post('/thread/update/{id}', [ThreadController::class, 'update']);
    Route::get('/thread/delete/{id}', [ThreadController::class, 'destroy']);
    
    Route::get('/replies', [ReplyController::class, 'index']);
    Route::post('/reply', [ReplyController::class, 'store']);
    Route::get('/reply/{id}', [ReplyController::class, 'show']);
    Route::post('/reply/update/{id}', [ReplyController::class, 'update']);
    Route::get('/reply/delete/{id}', [ReplyController::class, 'destroy']);
});


