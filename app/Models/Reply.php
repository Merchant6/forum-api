<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'post_id',
        'thread_id',
        'user_id',
        'content'
    ];


    /**
     * Belongs To Post::class
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Belongs To Thread::class
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * Belongs To User::class
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Returns this model's data with all related data like threads and replies
     * @return object
     */
    public function getAllRecordsWithRelations()
    {
        $reply = $this->with(['user:id,username'])
        ->get(['id', 'post_id', 'thread_id', 'user_id', 'content']);

        return $reply;
    }

    public function getSingleRecordWithRelations(string $id)
    {
        $post = $this->where('id', $id)
        ->with(['user:id,username'])
        ->get(['id', 'post_id', 'user_id', 'content']);

        return $post;
    }
}
