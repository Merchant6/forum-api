<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'post_id',
        'user_id',
        'content',
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
     * Belongs To User::class
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Has Many relation with Reply::class
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * Returns this model's data with all related data like threads and replies
     * @return object
     */
    public function getAllRecordsWithRelations()
    {
        $posts = $this->with(['user:id,username'])
        ->with(['replies' => function ($q) {
            $q->select('id', 'post_id', 'thread_id' ,'user_id', 'content');
            $q->with(['user:id,username']);
        }])
        ->paginate(50);
        // ->get(['id', 'user_id', 'post_id', 'content']);

        return $posts;
    }

    public function getSingleRecordWithRelations(string $id)
    {
        $post = $this->where('id', $id)
        ->with(['user:id,username'])
        ->with(['replies' => function ($q) {
            $q->select('id', 'post_id', 'thread_id', 'user_id', 'content');
            $q->with(['user:id,username']);
        }])
        ->get(['id', 'post_id', 'user_id', 'content']);

        return $post;
    }

}
